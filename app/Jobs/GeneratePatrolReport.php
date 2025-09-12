<?php

namespace App\Jobs;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\ReportJob;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GeneratePatrolReport implements ShouldQueue
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
            $pdf = Pdf::loadView('pages.report.patrol_pdf', [
                'patroli' => $final_list,
                'tanggal' => $tanggal,
                'jam' => "$jam1-$jam2",
            ]);

            $fileName = 'report_' . date('YmdHis') . ".pdf";
            $publicPath = public_path('reports');

            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $fileName;
            $pdf->save($filePath);

            // Update job
            $reportJob->status = 'done';
            $reportJob->save();
        } catch (\Exception $e) {
            $reportJob->status = 'failed';
            $reportJob->error_message = $e->getMessage();
            $reportJob->save();
        }
    }
}
