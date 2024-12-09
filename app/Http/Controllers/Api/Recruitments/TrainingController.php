<?php

namespace App\Http\Controllers\Api\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\JobApplpicant;
use App\ModelCG\AbsensiTraining;
use App\ModelCG\Project;
use App\Employee;
use App\ModelCG\Schedule;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $result = [];
        // Define the query for AbsensiTraining grouped by user_id
        $records = Employee::where('unit_bisnis', 'Kas')
                            ->where('resign_status', 0)
                            ->where('joindate', '>', '2024-09-25')
                            ->whereIn('organisasi', ['Frontline Officer', 'FRONTLINE OFFICER'])
                            ->get();

        // If records are not empty, process them
        if ($records->isNotEmpty()) {
            foreach ($records as $row) {
                // Get the latest schedule for the employee
                $last_schedule = Schedule::where('employee', $row->nik)
                                         ->latest('id')
                                         ->first();

                // Assign project name based on the latest schedule
                if ($last_schedule) {
                    // Directly retrieve project name associated with the last schedule
                    $project = Project::find($last_schedule->project);
                    $row->project = $project ? $project->name : '-';
                } else {
                    $row->project = '-'; // Handle case when no schedule exists
                }


                // Fetch recruitment data based on employee's NIK
                $recruitments = JobApplpicant::where('nomor_induk', $row->nik)->latest('id')->first();
                
                // Initialize default training status
                $nilai = [
                    "MONDAY" => "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>",
                    "TUESDAY" =>  "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>",
                    "WEDNESDAY" =>  "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>",
                    "THURSDAY" =>  "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>",
                    "FRIDAY" =>  "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>"
                ];

                // Process training data if recruitment exists
                if ($recruitments) {
                    // Fetch training data for the recruitment, excluding 'TK' status
                    $training = AbsensiTraining::where('user_id', $recruitments->id)
                                                ->where('status', 'H');

                    // Only process if training records exist
                    if ($training->count() > 0 && $training->count() < 5) {
                        $day = [];  // Initialize an empty array to store days

                        foreach ($training->get() as $row2) {
                            $date = $row2->tanggal;
                            $day[] = strtoupper(strftime('%A', strtotime($date)));
                        }

                        // Mark training days
                        foreach ($day as $key) {
                            if (in_array($key, ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY"])) {
                                $nilai[$key] = "<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";  // Set corresponding day to 1
                            }
                        }
                    }

                    // Assign training and total count to the employee record
                    $row->training = $nilai;
                    $row->jumlah_training = $training->count();
                    $row->recruitments_id = $recruitments->id;
                } else {
                    // If no recruitment found, assign default values
                    $row->training = [];
                    $row->recruitments_id = "";
                    $row->jumlah_training = 0;
                }

                // Add the employee data to the result array
                $result[] = [
                    "nik" => $row->nik,
                    "nama" => $row->nama,
                    "project" => $row->project,
                    "training" => $row->training,
                    "jumlah_training" => $row->jumlah_training,
                    "recruitments_id" => $row->recruitments_id
                ];
            }
        }

        // Return response with the result data
        return response()->json([
            'success' => true,
            'message' => 'Absensi data grouped by user_id retrieved successfully.',
            'data' => $result
        ], 200);
    }
}
