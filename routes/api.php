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
    Route::get('/referal-search/{kode_referal}', [ReferalController::class, 'search_referal']);

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



    Route::get('/patroli-report', [TaskController::class, 'report_patroli']);
    Route::get('/patroli-report-detail/{id}/{tanggal}', [TaskController::class, 'report_patroli_detail']);

    /**
     * Project
     */
    Route::get('/cek_schedule', [ProjectController::class, 'project_schedule']);
    Route::get('/absen_daily', [DailyContrtoller::class, 'daily_absen']);
    

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

    
    // Pengumuman
    Route::get('pengumuman', [AllDataController::class, 'ListPengumuman']);
        Route::get('pengumuman/{id}', [AllDataController::class, 'showPengumuman']);
    // Berita
    Route::get('berita', [AllDataController::class, 'ListBerita']);
        Route::get('berita/{id}', [AllDataController::class, 'showBerita']);

    // Birthday
    Route::get('birthdayEmployee', [AllDataController::class, 'BirtdayList']);
});