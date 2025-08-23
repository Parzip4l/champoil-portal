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
                $schedulesQuery->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

                // Generate complete range of dates
                $dates = [];
                $currentDate = $startDate->copy(); // Ensure $startDate is a Carbon instance
                while ($currentDate->lte($endDate)) {
                    $dates[] = $currentDate->toDateString(); // Normalize dates to string format
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

                // Initialize schedule array for the employee if not already done
                if (!isset($data[$employeeName]['schedule'])) {
                    $data[$employeeName]['schedule'] = array_fill_keys($dates, null); // Fill all dates with null initially
                }
                
                // Normalize the tanggal field to a date string
                $normalizedDate = Carbon::parse($schedule->tanggal)->toDateString();

                // Assign the shift to the specific date
                if (in_array($normalizedDate, $dates)) { // Ensure the date exists in the range
                    $data[$employeeName]['schedule'][$normalizedDate] = $schedule->shift;
                }
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
        // dd($result);
        $html['result'] = $result;
        return view('review', $html);
    }
}
