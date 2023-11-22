<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Employee\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    Route::post('/login', [ApiLoginController::class, 'login']);
    Route::post('/clockin', [ApiLoginController::class, 'clockin']);
    Route::post('/logout', [ApiLoginController::class, 'logout']);
    Route::get('/employee', [EmployeeController::class, 'ApiEmployee']);
    Route::get('/mySlip', [ApiLoginController::class, 'payslipuser']);
});