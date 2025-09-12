<?php

namespace App\Jobs;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\ReportJob;
use App\ModelCG\PatrliProject as  PatroliProject;
use App\ModelCG\PatroliProjectAct;
use App\ModelCG\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateProjectReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportJobId;

    public function __construct($reportJobId)
    {
        $this->reportJobId = $reportJobId;
    }

    public function handle()
    {
        $reportJob = ReportJob::find($this->reportJobId);
        if (!$reportJob) return;

        $reportJob->status = 'processing';
        $reportJob->save();

        $params = json_decode($reportJob->params, true);
        $tanggal = $params['tanggal'] ?? null;
        $project_id = $params['project_id'] ?? null;
        $jam1 = $params['jam1'] ?? '00:00';
        $jam2 = $params['jam2'] ?? '23:59';

        if (!$project_id || !$tanggal) {
            $reportJob->status = 'failed';
            $reportJob->save();
            return;
        }

        // Parse tanggal
        $explode = explode(' to ', $tanggal);
        $jml_tgl = count($explode);

        if ($jml_tgl > 1) {
            $date1 = $explode[0];
            $date2 = $explode[1];
        } else {
            $date1 = $explode[0];
            $date2 = $explode[0];
        }

        $start = Carbon::parse($date1)->startOfDay();
        $end = Carbon::parse($date2)->endOfDay();

        // Create the date period with daily intervals
        $dates = CarbonPeriod::create($start, '1 day', $end);

        // Adjust patrol data query
        $data_patrol = Task::select(
            'master_tasks.id',
            'master_tasks.judul',
            'patrolis.employee_code',
            'patrolis.created_at as jam_patrol',
            DB::raw('MIN(patrolis.image) as image'),
            DB::raw('MIN(patrolis.description) as description'),
            DB::raw('MAX(CASE WHEN patrolis.image IS NOT NULL AND patrolis.image != "" THEN patrolis.image ELSE NULL END) as image'),
            DB::raw('MAX(CASE WHEN patrolis.image IS NOT NULL AND patrolis.image != "" THEN patrolis.description ELSE NULL END) as description')
        )
        ->leftJoin('patrolis', function($join) use ($date1, $jam1, $date2, $jam2) {
            $join->on('patrolis.unix_code', '=', 'master_tasks.unix_code')
                 ->whereBetween('patrolis.created_at', ["$date1 $jam1:00", "$date2 $jam2:00"]);
        })
        ->where('master_tasks.project_id', $project_id)
        ->groupBy(
            'master_tasks.id',
            'master_tasks.judul',
            'patrolis.employee_code',
            'patrolis.created_at'
        )
        ->orderBy('master_tasks.id', 'asc')
        ->orderBy('patrolis.created_at', 'asc')
        ->get();

        // Restructure data to handle date logic
        $organized_patrols = [];
        foreach ($data_patrol as $record) {
            $patrol_date = date('Y-m-d H:i:s', strtotime($record->jam_patrol));
            $organized_patrols[$patrol_date][] = $record;
        }

        $final_list = [];
        foreach ($organized_patrols as $patrol_date => $patrols) {
            foreach ($patrols as $patrol) {
                $final_list[] = $patrol;
            }

            if (strtotime($patrol_date) < strtotime($date2)) {
                foreach ($patrols as $patrol) {
                    $new_record = clone $patrol;
                    $new_record->jam_patrol = date('Y-m-d H:i:s', strtotime($patrol_date . ' +1 day'));
                    $final_list[] = $new_record;
                }
            }
        }

        // Sort the final list by `created_at` (jam_patrol) ascending
        usort($final_list, function ($a, $b) {
            return strtotime($a->jam_patrol) <=> strtotime($b->jam_patrol);
        });

        // Generate PDF
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        try {
            // Parse request inputs
            $tanggal = $params['tanggal'];
            $project_id = $params['project_id'];
            $jam1 = $params['jam1'];
            $jam2 = $params['jam2'];

            $explode = explode(' to ', $tanggal);
            $jml_tgl = count($explode);

            if ($jml_tgl > 1) {
                $date1 = Carbon::parse($explode[0]);
                $date2 = Carbon::parse($explode[1]);
            } else {
                $date1 = Carbon::parse($explode[0]);
                $date2 = Carbon::parse($explode[0]);
            }

            $start = $date1->format('Y-m-d') . " $jam1";
            $end = $date2->format('Y-m-d') . " $jam2";

            // Fetch project details
            $project = Project::find($project_id);

            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            // Fetch patrol records
            $records = PatroliProject::select('patrli_projects.judul', 'patroli_project_acts.*')
                ->join('patroli_project_acts', 'patroli_project_acts.patroli_atc_id', '=', 'patrli_projects.id')
                ->whereBetween('patroli_project_acts.created_at', [$start, $end])
                ->orderBy('patroli_project_acts.created_at', 'asc')
                ->get();

            if ($records->isEmpty()) {
                return response()->json(['error' => 'No patrol records found for the specified criteria'], 404);
            }

            foreach ($records as $row) {
                $row->image = $row->images;
            }

            $data = [
                'patroli' => $records,
                'jam' => "$jam1 - $jam2",
                'filter' => "$date1 $jam1 - $date2 $jam2",
                'project' => $project->name ?? 'Unknown Project',
                'tanggal' => $tanggal ?? '',
                'title' => "PATROLI PROJECT",
                'code' => 'project'
            ];
            ini_set('memory_limit', '4096M');
            set_time_limit(0);

            // Generate the PDF
            if ($project_id == 582307) {
                $pdf = Pdf::loadView('pages.operational.patroli_project.patrol_pdf_dt', $data);
            } else {
                $pdf = Pdf::loadView('pages.operational.patroli_project.global', $data);
            }

            $pdf->setOption('no-outline', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setPaper('legal', 'portrait');

            // Create unique file name for the PDF
            $fileName = 'report_' . date('Ymd') . ".pdf";
            $publicPath = public_path('reports');

            // Ensure the directory exists
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $fileName;

            // Save the PDF
            $pdf->save($filePath);

            $fileUrl = asset('reports/' . $fileName);

            // Return JSON response with file details
            return response()->json([
                'message' => 'PDF file generated successfully',
                'path' => $fileUrl,
                'file_name' => $fileName,
                'project' => $project->name
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'error' => 'Failed to generate PDF',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
