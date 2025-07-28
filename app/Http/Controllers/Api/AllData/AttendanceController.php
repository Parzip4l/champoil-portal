<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request){

        $project = $request->project_id ?? "All";
        // Validate request
        if($request->start == null && $request->end == null){
            return response()->json([
                'status' => 'success',
                'data' => []
            ]);
        }else{

            $data = Schedule::select('employee','project', DB::raw('COUNT(*) as total_schedules'))
                ->whereBetween('tanggal', [$request->start, $request->end]);

            if($project != "ALL") {
                $data->where('project', $project);
            }

            $data = $data->groupBy('employee','project')
                ->get();

            $start_date = $request->start;
            $end_date = $request->end;

            $result = []; // Initialize an array to store the final data

            foreach ($data as $row) {
                $employee = karyawan_bynik($row->employee);

                if (!$employee) {
                    continue; // Skip if employee data is not found
                }

                $employeeData = [
                    'employee' => $row->employee,
                    'employee_name' => $employee->nama,
                    'project_name'=>project_byID($row->project)->name,
                    'total_schedules' => $row->total_schedules,
                    'schedules' => [] // Initialize schedules as an array
                ];

                $schedules = Schedule::where('employee', $row->employee)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->get();

                foreach ($schedules as $schedule) {
                    $absen = Absen::where('nik', $row->employee)
                        ->where('tanggal', $schedule->tanggal)
                        ->first();

                    $employeeData['schedules'][$schedule->tanggal] = [
                        'shift' => $schedule->shift,
                        'project' => $absen->project->name ?? '-',
                        'clock_in' => $absen->clock_in ?? '-',
                        'clock_out' => $absen->clock_out ?? '-',
                        'status' => $absen->status ?? '-'
                    ];
                }

                $result[] = $employeeData; // Add the employee data to the result array
            }

            if (empty($result)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        }
            
    }

    public function exportAbsensi(Request $request)
    {
        $project = $request->project_id ?? "All";
        $data = Schedule::select('employee', 'project', DB::raw('COUNT(*) as total_schedules'))
            ->whereBetween('tanggal', [$request->start, $request->end]);

        if ($project != "ALL") {
            $data->whereIn('project', $project);
        }

        $data = $data->groupBy('employee', 'project')->get();
        $start_date = $request->start;
        $end_date = $request->end;

        $result = []; // Initialize an array to store the final data
        $dates = []; // Collect all unique dates for headers

        foreach ($data as $row) {
            $employee = karyawan_bynik($row->employee);

            if (!$employee) {
                continue; // Skip if employee data is not found
            }

            $employeeData = [
                'Employee NIK' => $row->employee,
                'Employee Name' => $employee->nama,
                'Jabatan' => $employee->jabatan,
                'Project Name' => project_byID($row->project)->name ?? '-',
                'Total Schedules' => $row->total_schedules
            ];

            $schedules = Schedule::where('employee', $row->employee)
                ->whereBetween('tanggal', [$start_date, $end_date])
                ->get();

            foreach ($schedules as $schedule) {
                $absen = Absen::where('nik', $row->employee)
                    ->where('tanggal', $schedule->tanggal)
                    ->first();

                $employeeData[$schedule->tanggal] = [
                    'Shift' => $schedule->shift,
                    'Project' => $absen->project->name ?? '-',
                    'Clock In' => $absen->clock_in ?? '-',
                    'Clock Out' => $absen->clock_out ?? '-',
                    'Status' => $absen->status ?? '-'
                ];

                if (!in_array($schedule->tanggal, $dates)) {
                    $dates[] = $schedule->tanggal; // Add unique dates to the list
                }
            }

            $result[] = $employeeData; // Add the employee data to the result array
        }

        if (empty($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Sheet 1: Schedules
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Schedules');

        // Set the header row for Schedules
        $headers = ['Employee NIK', 'Employee Name', 'Jabatan', 'Project Name', 'Total Schedules','Total Masuk','Total Off', ...$dates];
        $sheet1->fromArray($headers, null, 'A1');

        // Populate the data rows for Schedules
        $rowIndex = 2;
        foreach ($result as $row) {
            $totalMasuk=0;
            $totalOff=0;
            foreach ($dates as $date) {
                $schedule = $row[$date] ?? ['Shift' => '-', 'Clock In' => '-', 'Clock Out' => '-'];
                if($schedule['Shift']=='OFF'){
                    $totalOff++;
                }else{
                    $totalMasuk++;
                }
            }

            $rowData = [
                "'{$row['Employee NIK']}", // Add single quote to preserve formatting
                $row['Employee Name'],
                $row['Project Name'],
                $row['Jabatan'],
                $row['Total Schedules'],
                $totalMasuk,
                $totalOff
            ];



            foreach ($dates as $date) {
                $schedule = $row[$date] ?? ['Shift' => '-', 'Clock In' => '-', 'Clock Out' => '-'];
                $rowData[] = $schedule['Shift'];
            }

            $sheet1->fromArray($rowData, null, "A$rowIndex");
            $rowIndex++;
        }

        // Sheet 2: Attendance
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Attendance');

        // Set the header row for Attendance
        $attendanceHeaders = ['Employee NIK', 'Employee Name', 'Project Name', 'Total Schedules', ...$dates];
        $sheet2->fromArray($attendanceHeaders, null, 'A1');

        // Populate the data rows for Attendance
        $rowIndex = 2;
        foreach ($result as $row) {
            $rowData = [
                "'{$row['Employee NIK']}", // Add single quote to preserve formatting
                $row['Employee Name'],
                $row['Project Name'],
                $row['Total Schedules']
            ];

            foreach ($dates as $date) {
                $schedule = $row[$date] ?? ['Shift' => '-', 'Clock In' => '-', 'Clock Out' => '-', 'Status' => '-'];
                $rowData[] = $schedule['Status'];
            }

            $sheet2->fromArray($rowData, null, "A$rowIndex");
            $rowIndex++;
        }

        // Sheet 3: Schedule Backup
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Schedule Backup');

        // Set the header row for Schedule Backup
        $backupHeader = [
            'Nama Menggantikan', 
            'Jabatan Yang Menggantikan', 
            'Project Menggantikan', 
            'Nama Digantikan',
            'Jabatan Yang Digantikan',
            'Project Digantikan',
            'Tanggal Backup',
        ];
        $sheet3->fromArray($backupHeader, null, 'A1');

        // Fetch and populate Schedule Backup data
        $scheduleBackupData = ScheduleBackup::whereBetween('tanggal',[$request->start, $request->end])->get(); // Replace with appropriate query
        $rowIndex = 2;
        foreach ($scheduleBackupData as $backup) {
            $project_menggantikan = Schedule::where('employee',$backup->employee)->where('tanggal',$backup->tanggal)->first();
            if($project_menggantikan){
                $project_menggantikan = $project_menggantikan->project;
            }else{
                $project_menggantikan = '-';
            }
            $sheet3->fromArray([
                karyawan_bynik($backup->employee)->nama ?? '-',
                karyawan_bynik($backup->employee)->jabatan ?? '-',
                project_byID($project_menggantikan)->name ?? '-',
                karyawan_bynik($backup->man_backup)->nama ?? '-',
                karyawan_bynik($backup->man_backup)->jabatan ?? '-',
                project_byID($backup->project)->name ?? '-',
                $backup->tanggal ?? '-',
            ], null, "A$rowIndex");
            $rowIndex++;
        }

        // Sheet 4: Attendance Backup
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Attendance Backup');

        // Set the header row for Attendance Backup
        $attendanceBackupHeader = ['Employee NIK', 'Employee Name', 'Project Name Backup', 'Status', 'Tanggal Backup'];
        $sheet4->fromArray($attendanceBackupHeader, null, 'A1');

        // Fetch and populate Attendance Backup data
        $attendanceBackupData = ScheduleBackup::join('absens', function ($join) {
            $join->on('absens.nik', '=', 'schedule_backups.employee')
                 ->whereColumn('absens.project_backup', '=', 'schedule_backups.project');
        })
        ->whereBetween('schedule_backups.tanggal', [$request->start, $request->end])
        ->get();
    
        $rowIndex = 2;
        foreach ($attendanceBackupData as $backup) {
            $sheet4->fromArray([
                "'{$backup->nik}'", // Add single quote to preserve formatting
                karyawan_bynik($backup->nik)->nama ?? '-',
                project_byID($backup->project_backup)->name ?? '-',
                $backup->status ?? '-',
                $backup->tanggal ?? '-',
            ], null, "A$rowIndex");
            $rowIndex++;
        }

        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Overtime');

        // Set the header row for Attendance
        $attendanceLembur = [
            'Nama', 
            'Jabatan', 
            'Project', 
            'Tanggal', 
            'Jam Masuk Lembur', 
            'Jam Keluar Lembur', 
            'Jam Lembur', 
            'Nominal Lembur'
        ];        
        $sheet5->fromArray($attendanceLembur, null, 'A1');
        $overTime = RequestAbsen::whereBetween('tanggal',[$request->start,$request->end])
                    ->where('status','Lembur')
                    ->where('aprrove_status','Approved')
                    ->get();
    
        $rowIndex = 2;
        foreach ($overTime as $lembur) {
            $clockIn = $lembur->clock_in ? Carbon::parse($lembur->clock_in) : null;
            $clockOut = $lembur->clock_out ? Carbon::parse($lembur->clock_out) : null;

            $totalHours = ($clockIn && $clockOut) ? $clockIn->diffInHours($clockOut) : '-'; 
            $sheet5->fromArray([
                karyawan_bynik($lembur->employee)->nama ?? '-', // Add single quote to preserve formatting
                karyawan_bynik($lembur->employee)->jabatan ?? '-',
                project_byID($lembur->project)->name ?? '-',
                $lembur->tanggal ?? '-',
                $lembur->clock_in ?? '-',
                $lembur->clock_out ?? '-',
                $totalHours ?? '-',
            ], null, "A$rowIndex");
            $rowIndex++;
        }
        // Ensure the 'exports' directory exists
        $exportsPath = storage_path('app/exports');
        if (!File::exists($exportsPath)) {
            File::makeDirectory($exportsPath, 0755, true);
        }

        // Save the Excel file to storage
        $filename = "attendance_export_" . now()->format('Ymd') . ".xlsx";
        $filePath = "exports/$filename";
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path("app/$filePath"));

        // Return the download link
        return response()->json([
            'status' => 'success',
            'message' => 'File exported successfully',
            'download_url' => Storage::url($filePath)
        ]);
    }

    public function getShift(Request $request)
    {
        $data = [
            ['value' => 'BCK-PG', 'label' => 'BACKUP PAGI'],
            ['value' => 'BCK-MD', 'label' => 'BACKUP MIDDLE'],
            ['value' => 'BCK-ML', 'label' => 'BACKUP MALAM'],
            ['value' => 'BCK-NS', 'label' => 'BACKUP NON SHIFT']
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function replaceBackup(Request $request)
    {
        $project = $request->project_id;
        $shift = $request->shift;
        $tanggal = $request->tanggal;
        

        $employee = Employee::join('schedules', 'karyawan.nik', '=', 'schedules.employee')
            ->where('schedules.project', $project)
            ->where('schedules.shift', $shift)
            ->where('schedules.tanggal', $tanggal)
            ->select('karyawan.nik', 'karyawan.nama')
            ->get();


        if ($employee->isEmpty()) { 
            return response()->json([
                'status' => 'error',
                'message' => 'No employees found for the given project, shift, and date.'
            ], 404);
        }
        $data = [];
        foreach ($employee as $emp) {
            $data[] = [
                'value' => $emp->nik,
                'label' => strtoupper($emp->nama)
            ];  
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => $data
        ]);
    }

    public function storeBackup(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee' => 'required',
                'project' => 'required',
                'tanggal' => 'required|date',
                'shift' => 'required',
                'periode' => 'required',
                'man_backup' => 'required'
            ]);

            $schedule = Schedule::where('employee', $validated['employee'])
                ->where('tanggal', $validated['tanggal'])
                ->first();

            if ($schedule->shift == $validated['shift']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Karena shift utama sama dengan shift backup, tidak bisa melakukan backup.'
                ], 400);
            }

            // Additional validation for PG shift
            if ($validated['shift'] == 'PG') {
                $previousDaySchedule = Schedule::where('employee', $validated['employee'])
                    ->where('tanggal', Carbon::parse($validated['tanggal'])->subDay())
                    ->first();

                if ($previousDaySchedule && $previousDaySchedule->shift == 'ML') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda Tidak Bisa Mengganti Shift Pagi, Karena Anda Kemarin Masuk Malam.'
                    ], 400);
                }
            }

            ScheduleBackup::create([
                'employee' => $validated['employee'],
                'project' => $validated['project'],
                'tanggal' => $validated['tanggal'],
                'shift' => $validated['shift'],
                'periode' => $validated['periode'],
                'man_backup' => $validated['man_backup'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Backup request submitted successfully',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the backup request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
