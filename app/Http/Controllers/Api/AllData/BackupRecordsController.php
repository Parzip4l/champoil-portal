<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\ScheduleBackup;
use App\User;
use App\Employee;
use App\Absen;
use App\ModelCG\Schedule;
use App\ModelCG\Project;

class BackupRecordsController extends Controller
{
    public function index(Request $request)
    {
        $nik = $request->input('nik');
        $periode = $request->input('period') ?? date('Y-m');
        $startDate = date('Y-m-d', strtotime("$periode-21 -1 month")); // Start date: 21st of the previous month
        $endDate = date('Y-m-d', strtotime("$periode-20")); // End date: 20th of the current month
        $company = User::where('name', $nik)->pluck('company')->first();
        $perPage = $request->input('perPage', 300); // Default to 10 records per page

        $employee = Employee::select('nama', 'nik', 'jabatan')
                            ->where('unit_bisnis', $company)
                            ->where('resign_status', 0)
                            ->whereIn('organisasi', ['Frontline Officer', 'FRONTLINE OFFICER'])
                            ->paginate($perPage); // Apply pagination

        foreach ($employee as $emp) {
            $emp->backup = ScheduleBackup::where('employee', $emp->nik)
                                         ->whereBetween('tanggal', [$startDate, $endDate]) // Apply date range filter
                                         ->get();
            foreach ($emp->backup as $backup) {
                $backup->tanggal_replace = date('l, d F Y', strtotime($backup->tanggal));
                $backup->pengganti = karyawan_bynik($backup->employee)->nama;
                $project_pengganti = Schedule::where('employee', $emp->nik)
                                             ->where('tanggal', $backup->tanggal)->pluck('project')->first();
                $backup->project_pengganti = Project::where('id', $project_pengganti)->pluck('name')->first();
                $backup->project_backup = Project::where('id', $backup->project)->pluck('name')->first();
                $backup->digantikan = Employee::where('nik', $backup->man_backup)->pluck('nama')->first();
                $backup->status = Absen::where('project', $backup->project)
                                       ->where('tanggal', $backup->tanggal)
                                       ->where('nik', $backup->employee)
                                       ->exists() ? 'Hadir' : 'Tidak Hadir';
            }
        }
        $schedule = ScheduleBackup::all(); // Example: Fetch all backup records
        return response()->json([
            'message' => 'Backup records retrieved successfully.',
            'data' => $employee // Paginated data
        ]);
    }
}
