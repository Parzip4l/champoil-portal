<?php

namespace App\Jobs;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\ReportJob;
use App\ModelCG\PatrliProject as PatroliProject;
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

        try {
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

            $project = Project::find($project_id);

            if (!$project) {
                $reportJob->status = 'failed';
                $reportJob->save();
                return;
            }

            $records = PatroliProject::select('patrli_projects.judul', 'patroli_project_acts.*')
                ->join('patroli_project_acts', 'patroli_project_acts.patroli_atc_id', '=', 'patrli_projects.id')
                ->whereBetween('patroli_project_acts.created_at', [$start, $end])
                ->orderBy('patroli_project_acts.created_at', 'asc')
                ->get();

            if ($records->isEmpty()) {
                $reportJob->status = 'failed';
                $reportJob->save();
                return;
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

            $pdf = Pdf::loadView(
                $project_id == 582307 ? 'pages.operational.patroli_project.patrol_pdf_dt' : 'pages.operational.patroli_project.global',
                $data
            );

            $pdf->setOption('no-outline', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setPaper('legal', 'portrait');

            $fileName = 'report_' . date('Ymd') . ".pdf";
            $publicPath = public_path('reports');

            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $fileName;
            $pdf->save($filePath);

            $fileUrl = asset('reports/' . $fileName);

            $reportJob->status = 'completed';
            $reportJob->file_path = $fileUrl;
            $reportJob->save();
        } catch (\Exception $e) {
            $reportJob->status = 'failed';
            $reportJob->error_message = $e->getMessage();
            $reportJob->save();
        }
    }
}
