<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\LmsController;
use App\Http\Controllers\Employee\EmployeeController;

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
    Route::post('/absence-request', [ApiLoginController::class, 'submitAbsenceRequest']);
    Route::get('/history-absence-request', [ApiLoginController::class, 'HistoryDataRequest']);
    Route::get('/employee-resign', [App\Http\Controllers\Api\EmployeeController::class, 'resign'])->name('employee-resign');

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
    Route::post('/clockout-backup', [ApiLoginController::class, 'clockout']);
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
    Route::post('/post_test', [LmsController::class, 'SubmitTest']);
    
});