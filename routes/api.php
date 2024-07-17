<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
    Route::get('/patroli-list', [PatroliController::class, 'list']);

    Route::post('/download-report', [PatroliController::class, 'download_report']);



    Route::get('/patroli-report', [TaskController::class, 'report_patroli']);
    Route::get('/patroli-report-detail/{id}/{tanggal}', [TaskController::class, 'report_patroli_detail']);

    /**
     * Project
     */
    Route::get('/cek_schedule', [ProjectController::class, 'project_schedule']);
    

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