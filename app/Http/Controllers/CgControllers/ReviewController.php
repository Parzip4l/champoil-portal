<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\Project;
use App\ModelCG\Schedule;
use Carbon\Carbon;

class ReviewController extends Controller
{
    //
    public function index(Request $request)
    {
        $result = [];
        $html['projects'] = Project::where('company', 'Kas')->get();

        if ($request->has('project_id')) {
            $schedulesQuery = Schedule::where('project', $request->project_id);

            // Filter by month_year if provided
            if ($request->has('month_year')) {
                [$month, $year] = explode('-', $request->month_year);

                // Convert month name to numeric value
                $month = Carbon::parse($month . ' 1')->month;

                $startDate = Carbon::createFromDate($year, $month, 21)->subMonth()->startOfDay();
                $endDate = Carbon::createFromDate($year, $month, 20)->endOfDay();
                $schedulesQuery->whereBetween('tanggal', [$startDate, $endDate]);

                // Generate complete range of dates
                $dates = [];
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $dates[] = $currentDate->toDateString();
                    $currentDate->addDay();
                }
            } else {
                $dates = [];
            }

            $schedules = $schedulesQuery->get();
            $data = [];

            foreach ($schedules as $schedule) {
                $project = Project::find($schedule->project);
                $schedule->project_name = $project ? $project->name : 'Unknown Project'; // Handle null project
                $schedule->employee_name = $schedule->employee; // Assuming 'employee' is a property of Schedule

                $employeeName = karyawan_bynik($schedule->employee_name)->nama;

                $data[$employeeName]['nama'] = $employeeName;
                $data[$employeeName]['nik'] = $schedule->employee_name;
                $data[$employeeName]['project'] = $schedule->project_name;
                $data[$employeeName]['schedule'][$schedule->tanggal] = $schedule->shift; // Directly store shift with date as key
            }

            // Ensure all dates are included in the result
            foreach ($data as &$employeeData) {
                foreach ($dates as $date) {
                    if (!isset($employeeData['schedule'][$date])) {
                        $employeeData['schedule'][$date] = null; // Fill missing dates with null
                    }
                }
            }

            // Re-index the array to ensure it is properly formatted
            $data = array_values($data);

            $result = [
                'dates' => $dates,
                'data' => $data,
            ];
        }

        $html['result'] = $result;
        return view('review', $html);
    }
}
