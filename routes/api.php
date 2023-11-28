<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Employee\EmployeeController;

Route::prefix('v1')->group(function () {

    Route::group(['middleware' => ['disableCsrf']], function () {
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

        // Log Absen
        Route::get('/log-absensi', [ApiLoginController::class, 'MyLogsAbsen']);

        // Schedule 
        Route::get('/mySchedule', [ApiLoginController::class, 'myschedule']);
        Route::get('/mySchedule-Backup', [ApiLoginController::class, 'BackupSchedule']);

        // Clock in/out (tanpa middleware auth:api)
        Route::post('/clockin', [ApiLoginController::class, 'clockin']);
        Route::post('/clockout', [ApiLoginController::class, 'clockout']);
    });

});