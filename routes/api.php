<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\LmsController;
use App\Http\Controllers\Api\Patroli\PatroliController;
use App\Http\Controllers\Api\Task\TaskController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Api\EmployeeController as ApiEmployee;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReferalController;
use App\Http\Controllers\Api\Project\ProjectController;
use App\Http\Controllers\Api\VersionController;
use App\Http\Controllers\Api\Analytics\AnalyticsController;
use App\Http\Controllers\Api\Analytics\EmployeeAnalytics;
use App\Http\Controllers\Api\AllData\AllDataController;
use App\Http\Controllers\Api\Schedules\ScheduleController;
use App\Http\Controllers\Api\AllData\TaskManagementApi;
use App\Http\Controllers\Api\KoperasiApi\AllKoperasiController;
use App\Http\Controllers\Api\Schedular\DailyContrtoller;
use App\Http\Controllers\Api\Emergency\EmergencyApi;
use App\Http\Controllers\Api\Emergency\FirebaseTokenController;
use App\Http\Controllers\Api\VoiceOfController;
use App\Http\Controllers\Api\Recruitments\TrainingController;
use App\Http\Controllers\Api\Patroli\PatroliProojectController;
use App\Http\Controllers\Api\Patroli\LapsitController;


Route::prefix('v1')->group(function () {

    // Authentication
    Route::post('/login', [ApiLoginController::class, 'login']);
    Route::post('/logout', [ApiLoginController::class, 'logout']);
    Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

    // Employee
    Route::get('/user', [ApiLoginController::class, 'getUser']);
    Route::get('/employee', [EmployeeController::class, 'ApiEmployee']);
    Route::get('/mySlip', [ApiLoginController::class, 'payslipuser']);
    Route::get('/Payslip/{id}', [ApiLoginController::class, 'PayslipDetails']);
    Route::get('/employee/{nik}', [ApiLoginController::class, 'getEmployeeByNik']);
    Route::get('/profile/{nik}', [ApiLoginController::class, 'getMyProfile']);
    Route::post('/profile-update', [ProfileController::class, 'update']);
    Route::post('/absence-request', [ApiLoginController::class, 'submitAbsenceRequest']);
    Route::get('/history-absence-request', [ApiLoginController::class, 'HistoryDataRequest']);
    Route::get('/employee-resign', [App\Http\Controllers\Api\EmployeeController::class, 'resign'])->name('employee-resign');
    Route::get('/parentMenu/{id}', [App\Http\Controllers\MenuController::class, 'parentChild'])->name('parentMenu');
    Route::get('/all-employee', [App\Http\Controllers\Api\EmployeeController::class, 'all_employee'])->name('all-employee');

    // Task Management
        // Task Master
        Route::get('/task-management', [TaskManagementApi::class, 'index']);
        Route::get('task-management/all', [TaskManagementApi::class, 'allTask']);
        Route::get('tasks/{id}', [TaskManagementApi::class, 'show']);
        Route::post('/create-master-task', [TaskManagementApi::class, 'store']);
        Route::put('tasks-update/{id}', [TaskManagementApi::class, 'update']);
        Route::delete('/tasks/{id}', [TaskManagementApi::class, 'destroy']);
        Route::get('/tasks/{id}/download-attachment', [TaskManagementApi::class, 'downloadAttachment']);
        Route::post('/tasks/{id}/comments', [TaskManagementApi::class, 'storeComment']);
            // Subtask
            Route::post('subtasks', [TaskManagementApi::class, 'storeSubtask']);
            Route::get('subtasks/{id}', [TaskManagementApi::class, 'showSubtask']);
            Route::delete('subtask-delete/{id}', [TaskManagementApi::class, 'deleteSubtask']);
            Route::put('/subtasks/{id}/status', [TaskManagementApi::class, 'updateStatus']);
            Route::get('/subtasks/{id}/download-attachment', [TaskManagementApi::class, 'downloadAttachmentSubtask']);
            Route::put('subtask/complete/{id}', [TaskManagementApi::class, 'completeSubtask']);
            // tracking
            Route::post('/subtasks/{id}/start-tracking', [TaskManagementApi::class, 'startTracking']);
            Route::post('/subtasks/{id}/pause', [TaskManagementApi::class, 'pauseTracking'])->name('subtasks.pause');
            Route::post('/subtasks/{id}/resume', [TaskManagementApi::class, 'resumeTracking'])->name('subtasks.resume');
            Route::post('/subtasks/{id}/stop-tracking', [TaskManagementApi::class, 'stopTracking']);
            Route::get('/running-task', [TaskManagementApi::class, 'getRunningSubtasks']);
            // Filter
            Route::get('/tasks/filter/priority/{priority}', [TaskManagementApi::class, 'filterByPriority'])->name('tasks.filterByPriority');
            Route::get('/tasks/filter/progress/{progress}', [TaskManagementApi::class, 'filterByProgress'])->name('tasks.filterByProgress');
            Route::get('/tasks-data/search', [TaskManagementApi::class, 'searchTasks'])->name('tasks.searchTasks');

    // Koperasi
    Route::get('/koperasi-page', [AllKoperasiController::class, 'index']);
    Route::get('/koperasi-terms', [AllKoperasiController::class, 'terms']);
    Route::post('/anggota-join', [AllKoperasiController::class, 'keanggotaan']);
    Route::get('/my-savings', [AllKoperasiController::class, 'dataSaving']);
    Route::get('/cek-limit', [AllKoperasiController::class, 'cekLimit']);
    Route::post('/pengajuan-pinjaman', [AllKoperasiController::class, 'pengajuanPinjaman']);
    Route::get('/member-cek', [AllKoperasiController::class, 'cekAnggota']);
    Route::post('/kalkulasi-pinjaman', [AllKoperasiController::class, 'kalkulasiPinjaman']);

    /**
     * Referal
     */

    //  Emergency 

    Route::get('/emergency-page', [EmergencyApi::class, 'index']);
    Route::post('/emergency-request', [EmergencyApi::class, 'EmergencyRequest']);
    Route::put('/emergency-request/accept/{id}', [EmergencyApi::class, 'updateStatusSetuju']);
    Route::put('/emergency-request/reject/{id}', [EmergencyApi::class, 'updateStatusRejected']);
    Route::middleware('auth:api')->post('/firebase-token', [FirebaseTokenController::class, 'store']);


    Route::get('/referal-search', [ReferalController::class, 'search_referal']);
    Route::get('/referal-list', [ReferalController::class, 'referal_data']);

    // Log Absen
    Route::get('/log-absensi', [ApiLoginController::class, 'MyLogsAbsen']);

    // Schedule 
    Route::get('/mySchedule', [ApiLoginController::class, 'myschedule']);
    Route::get('/mySchedule-Backup', [ApiLoginController::class, 'BackupSchedule']);

    // Clock in/out (tanpa middleware auth:api)
    Route::post('/clockin', [ApiLoginController::class, 'clockin']);
    Route::post('/clockout', [ApiLoginController::class, 'clockout']);

    // Backup
    Route::post('/clockin-backup', [ApiLoginController::class, 'clockinbackup']);
    Route::post('/clockout-backup', [ApiLoginController::class, 'clockout_backup']);
    Route::get('/backup-log', [ApiLoginController::class, 'LogBackup']);

    // Request Type
    Route::get('/type-absence-request', [ApiLoginController::class, 'TypeAttendenceRequest']);
    
    // Update Attendence Request
    Route::get('/all-request', [ApiLoginController::class, 'AbsenRequest']);
    Route::put('/attendence/{id}/update-status', [ApiLoginController::class, 'updateStatusSetuju']);
    Route::put('/attendence/{id}/update-reject', [ApiLoginController::class, 'updateStatusReject']);

    // Download Files Attendence Request
    Route::get('/download-files-attendence/{id}', [ApiLoginController::class, 'downloadFilesAttendence']);


    //LMS
    Route::get('/learning-read/{id}', [LmsController::class, 'ReadTest']);
    Route::get('/data-learning', [LmsController::class, 'DataLearning']);
    Route::get('/knowledge_test/{id}', [LmsController::class, 'KnowledgeTest']);
    Route::get('/lms-nilai', [LmsController::class, 'hasilNilai']);
    Route::post('/post_test', [LmsController::class, 'SubmitTest']);

    Route::get('/turn_over_bulan', [ApiEmployee::class, 'turnover_statistik']);


    /**
     * Task Management
     */
    Route::get('/task_list/{id}', [TaskController::class, 'task']);
    Route::get('/project', [TaskController::class, 'project']);


    /**
     * patroli
     */
    Route::get('/patroli_task/{id}', [PatroliController::class, 'checklist_task']);
    Route::post('/post-patroli', [PatroliController::class, 'patroli_save']);
    Route::post('/patroli-report-dash', [PatroliController::class, 'report_patrol']);
    Route::get('/patroli-detail/{id}', [PatroliController::class, 'detail']);
    Route::get('/report-patroli-project', [PatroliController::class, 'report_patroli']);
    Route::get('/patroli-list', [PatroliController::class, 'list']);
    Route::get('/export-report', [PatroliController::class, 'export_report']);

    Route::post('/download-report', [PatroliController::class, 'download_report']);
    Route::post('/save-chart-image', [PatroliController::class, 'saveChartImage']);

    Route::get('/download_file_patrol', [PatroliController::class, 'download_file_patrol']);



    Route::get('/patroli-report', [TaskController::class, 'report_patroli']);
    Route::get('/patroli-report-detail/{id}/{tanggal}', [TaskController::class, 'report_patroli_detail']);

    /**
     * Project
     */
    Route::get('/cek_schedule', [ProjectController::class, 'project_schedule']);
    Route::get('/absen_daily', [DailyContrtoller::class, 'daily_absen']);
    Route::get('/schedule-reminder/{key}/{periode}', [DailyContrtoller::class, 'reminder_schedule']);
    Route::get('/report-absens-qc', [DailyContrtoller::class, 'report_absen']);
    

    /**
     * version 
     */
    Route::get('/version', [VersionController::class, 'version']);

    // Analytics
    Route::get('unique-visitors', [AnalyticsController::class, 'getUniqueVisitorsCount']);
    Route::get('employee-count', [EmployeeAnalytics::class, 'getEmployeeCount']);
    Route::post('/map-domisili', [App\Http\Controllers\Map::class, 'update_domisili'])->name('map-domisili');

    //Schedule
    Route::get('project-schedules/{id}', [ScheduleController::class, 'data_shift']);
    Route::get('project-schedules-report/', [ScheduleController::class, 'index']);

    
    // Pengumuman
    Route::get('pengumuman', [AllDataController::class, 'ListPengumuman']);
        Route::get('pengumuman/{id}', [AllDataController::class, 'showPengumuman']);
    // Berita
    Route::get('berita', [AllDataController::class, 'ListBerita']);
        Route::get('berita/{id}', [AllDataController::class, 'showBerita']);

    // Birthday
    Route::get('birthdayEmployee', [AllDataController::class, 'BirtdayList']);

    Route::post('check-nik', [AllDataController::class, 'check_nik']);
    Route::post('submit-pengajuan-cicilan', [AllDataController::class, 'submit_pengajuan_cicilan']);
    Route::post('submit-voice', [VoiceOfController::class, 'submit_voice']);
    Route::get('voice', [VoiceOfController::class, 'index']);
    Route::get('voice-detail/{id}', [VoiceOfController::class, 'voice_detail']);
    Route::post('voice-detail-submit', [VoiceOfController::class, 'submit_voice_relations']);
    Route::get('data_pengajuan', [AllDataController::class, 'pengajuan_hp']);
    Route::post('/update_pengajuan', [AllDataController::class, 'update_pengajuan']);


    Route::post('dashboard-patroli', [PatroliController::class, 'dashboard_analytic']);
    Route::get('/skip-training', [TrainingController::class, 'index'])->name('skip-training');

    Route::get('/skip-training', [TrainingController::class, 'index'])->name('skip-training');
    
    //patrooli project
    Route::get('patroli-projects-get', [PatroliProojectController::class, 'index']); // Fetch all resources
    Route::post('patroli-projects-insert', [PatroliProojectController::class, 'store']); // Create a new resource
    Route::get('patroli-projects/{id}', [PatroliProojectController::class, 'show']); // Fetch a single resource
    Route::put('patroli-projects/{id}', [PatroliProojectController::class, 'update']); // Update a resource
    Route::delete('patroli-projects/{id}', [PatroliProojectController::class, 'destroy']); // Delete a resource
    Route::get('patroli-projects/{unixCode}/download', [PatroliProojectController::class, 'download'])->name('patroli-projects.download');
    Route::get('project-patroli/{unixCode}', [PatroliProojectController::class, 'project_patroli'])->name('project-patroli');
    Route::post('patroli-activity', [PatroliProojectController::class, 'storeActivity']);
    Route::get('patroli-activity-download', [PatroliProojectController::class, 'download_file_patrol']);

    // Fetch all Lapsit projects
    Route::get('lapsit-projects-get', [LapsitController::class, 'index']); // Fetch all resources

    // Create a new Lapsit project
    Route::post('lapsit-projects-insert', [LapsitController::class, 'store']); // Create a new resource

    // Fetch a single Lapsit project by ID
    Route::get('lapsit-projects/{id}', [LapsitController::class, 'show']); // Fetch a single resource

    // Update a Lapsit project by ID
    Route::put('lapsit-projects/{id}', [LapsitController::class, 'update']); // Update a resource

    // Delete a Lapsit project by ID
    Route::delete('lapsit-projects/{id}', [LapsitController::class, 'destroy']); // Delete a resource

    // Download QR code for a specific Lapsit project using its Unix code
    Route::get('lapsit-projects/{unixCode}/download', [LapsitController::class, 'download'])->name('lapsit-projects.download');

    // Get URL for a specific Lapsit activity
    Route::get('project-lapsit/{unixCode}', [LapsitController::class, 'project_lapsit'])->name('project-lapsit');

    // Store a Lapsit activity (with an image)
    Route::post('lapsit-activity', [LapsitController::class, 'storeActivity']); // Store activity
    Route::get('lapsit-activity-download', [LapsitController::class, 'download_file_patrol']);

    Route::post('export-payroll', [AllDataController::class, 'export_payroll']);
    Route::get('download-sertifikat/{unit_bisnis}', [AllDataController::class, 'download_sertifikat']);


});