<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dashboard
Route::middleware(['auth', 'permission:dashboard_access'])->group(function () {

    // Employee Loan
    Route::resource('employee-loan', App\Http\Controllers\Loan\LoanController::class);
    
    // Garda Pratama
    Route::resource('garda-pratama', App\Http\Controllers\GardaPratama\GpController::class);

    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('/', DashboardController::class);

    // Payslip
    Route::get('/myslip', [App\Http\Controllers\Payrol\PayslipController::class, 'payslipuser'])->name('mySlip');
    Route::resource('payslip', App\Http\Controllers\Payrol\PayslipController::class);
    Route::resource('payslip-ns', App\Http\Controllers\Payrol\PayslipnsController::class);
    Route::resource('absen', App\Http\Controllers\Absen\AbsenController::class);

    // Delete Absen
    Route::get('/delete-attendance/{date}/{nik}', [App\Http\Controllers\Absen\AbsenController::class, 'deleteAttendance']);

    // Get By Organisasi
    Route::get('/attendance/filter', [ App\Http\Controllers\Absen\AbsenController::class, 'filterByOrganization'])->name('attendance.filter');

    Route::get('absen/show/{nik}', [App\Http\Controllers\Absen\AbsenController::class, 'detailsAbsen'])->name('absen.details');

    // Export Attendence
    Route::get('export-attendence', [App\Http\Controllers\Absen\AbsenController::class, 'exportAttendence'])->name('export.attendence');

    Route::get('/mylogs', [App\Http\Controllers\Absen\LogController::class, 'index'])->name('mylogs');

    // Backup
    Route::get('/backup-attendence', [App\Http\Controllers\Absen\AbsenController::class, 'absenBackup'])->name('attendence.backup');
    Route::post('/absensi/clockin', [\App\Http\Controllers\Absen\AbsenController::class, 'clockin'])
    ->middleware('auth')
    ->name('clockin');

    Route::post('/absensi/backup/clockout', [\App\Http\Controllers\Absen\AbsenController::class, 'clockoutbackup'])
    ->middleware('auth')
    ->name('clockout.backup');
    
    // Absen
    Route::post('/absensi/backup/clockin', [\App\Http\Controllers\Absen\AbsenController::class, 'clockinbackup'])
    ->middleware('auth')
    ->name('clockin.backup');

    Route::post('/absensi/clockout', [\App\Http\Controllers\Absen\AbsenController::class, 'clockout'])
    ->middleware('auth')
    ->name('clockout');

    // Request Absen
    Route::group(['prefix' => 'attendence'], function(){
        Route::resource('attendence-request', App\Http\Controllers\Absen\RequestControllers::class);
        Route::post('/attendence-request/{id}', [App\Http\Controllers\Absen\RequestControllers::class, 'updateStatusSetuju'])->name('approve.request');
        Route::post('/reject-request/{id}', [App\Http\Controllers\Absen\RequestControllers::class, 'updateStatusReject'])->name('reject.request');
        Route::get('attendence-request/{id}/download', [App\Http\Controllers\Absen\RequestControllers::class, 'download'])->name('dokumen.download');
    });

    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    
    // Update Component NS
    Route::get('/update-component-ns/{id}', [App\Http\Controllers\Payrol\PayrolComponent::class, 'editns'])->name('editcomponentns.edit');
    Route::put('/payroll-components-ns/{id}', [App\Http\Controllers\Payrol\PayrolComponent::class, 'updateNS'])->name('updatecomponentNS.update');

    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');

    // Classroom
    Route::get('/read_test/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'read_test'])->name('read_test');
    Route::get('/pdf.preview/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'pdfPreview'])->name('pdf.preview');
    Route::get('/kas/user.test/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'user_test'])->name('kas/user.test');
    Route::post('/knowledge.save_test_user', [App\Http\Controllers\knowledge\KnowledgeController::class, 'submit_user'])->name('knowledge.save_test_user');
    Route::get('/list-class', [App\Http\Controllers\knowledge\KnowledgeController::class, 'list_classroom'])->name('list-class');
    Route::get('/start_class/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'start_class'])->name('start_class');

    // User Profile
    Route::get('/MyProfile/{nik}', [App\Http\Controllers\Employee\EmployeeController::class, 'MyProfile'])->name('MyProfile');
    Route::put('/users/{id}/update-password', 'UserController@changePassword')->name('pass.update');
    Route::put('/users/{id}/reset-password', 'UserController@ResetPassword')->name('pass.reset');
    
    //task management
    Route::resource('task', App\Http\Controllers\Taskmanagement\TaskController::class);
    Route::resource('list-task', App\Http\Controllers\Taskmanagement\ListController::class);
    Route::get('/add_task/{id}', [App\Http\Controllers\Taskmanagement\ListController::class, 'list_task'])->name('add_task');
    Route::get('/qrcode/{id}', [App\Http\Controllers\Taskmanagement\TaskController::class, 'qr_code'])->name('qrcode');
    
    //patroli
    Route::get('/patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'index'])->name('patroli');
    Route::get('/scan_qr', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'scan_qr'])->name('scan_qr');
    Route::get('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'checklist_task'])->name('checklist');
    Route::post('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'post_code'])->name('checklist.post');
    Route::post('/save_patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'store'])->name('save_patroli');
    
    // Feedback
    Route::post('/users/feedback', 'DashboardController@StoreFeedback')->name('feedback.store');
    Route::get('/unix-code', [App\Http\Controllers\Employee\EmployeeController::class,'unix_code'])->name('unix-code');
});

Route::middleware(['auth', 'permission:hc_access'])->group(function () {
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');

    //task management global
    Route::resource('taskg', App\Http\Controllers\Ops\TaskgController::class);
});

// Superadmin Access
Route::middleware(['auth', 'permission:superadmin_access'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('slack-account', App\Http\Controllers\Slack\SlackController::class);
    Route::resource('slack-artikel', App\Http\Controllers\Automatisasi\ArtikelController::class);
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::resource('employee-resign', App\Http\Controllers\Employee\ResignController::class);
    Route::post('/employee.resign', [App\Http\Controllers\Employee\EmployeeController::class, 'resign'])->name('employee.resign');
    Route::get('/users/autocomplete', 'UserController@autocomplete')->name('users.autocomplete');

    Route::get('export-employee', [App\Http\Controllers\Employee\EmployeeController::class, 'exportEmployee'])->name('export.employee');

    // Payrol Data
    Route::resource('payrol-component', App\Http\Controllers\Payrol\PayrolComponent::class);
    Route::resource('payroll', App\Http\Controllers\Payrol\PayrolController::class);
    Route::get('/payrol-history/{month}/{year}', [App\Http\Controllers\Payrol\PayslipController::class, 'showByMonth'])->name('payslip.showByMonth');
    Route::get('/payrolns-history/{periode}', [App\Http\Controllers\Payrol\PayslipController::class, 'showByPeriode'])->name('payslip.showbyperiode');

    // Publish Payrol And Lock
    Route::get('/lock-payroll/{month}/{year}', [App\Http\Controllers\Payrol\PayslipController::class, 'lockPayroll'])->name('lockPayroll');
    Route::get('/publish-payslip/{month}/{year}', [App\Http\Controllers\Payrol\PayslipController::class, 'publishPayslip'])->name('PublishPayslipData');
    Route::get('/unlock-payroll/{month}/{year}', [App\Http\Controllers\Payrol\PayslipController::class, 'unlockPayroll'])->name('unlockPayroll');
    Route::get('/unpublish-payslip/{month}/{year}', [App\Http\Controllers\Payrol\PayslipController::class, 'unpublishPayslip'])->name('UnpublishPayslip');

    // Publish Payrol And Lock NS
    Route::get('/lock-payrollns/{periode}', [App\Http\Controllers\Payrol\PayslipController::class, 'lockPayrollNS'])->name('lockPayrollns');
    Route::get('/publish-payslipns/{periode}', [App\Http\Controllers\Payrol\PayslipController::class, 'publishPayslipNS'])->name('PublishPayslipDataNS');
    Route::get('/unlock-payrollns/{periode}', [App\Http\Controllers\Payrol\PayslipController::class, 'unlockPayrollns'])->name('unlockPayrollns');
    Route::get('/unpublish-payslipns/{periode}', [App\Http\Controllers\Payrol\PayslipController::class, 'unpublishPayslipns'])->name('UnpublishPayslipns');

    // Edit Payroll NS
    Route::get('/edit-payrollns/{id}', [App\Http\Controllers\Payrol\PayslipController::class, 'editNS'])->name('editns.payroldata');
    Route::put('/update-payrollns/{id}', [App\Http\Controllers\Payrol\PayslipController::class, 'updateNS'])->name('updateNS.payroldata');
    
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');

    // Update Absen
    Route::post('/action/edit/{date}/{nik}', [App\Http\Controllers\Employee\EmployeeController::class, 'UpdateAbsen'])->name('attendance.editData');
    Route::post('/action/create', [App\Http\Controllers\Employee\EmployeeController::class, 'CreateAbsen'])->name('attendance.createData');

    // Company Settings
    Route::resource('company', App\Http\Controllers\Company\CompanyController::class);

    // Payroll
    // Component Master
    Route::resource('component-data', App\Http\Controllers\Payrol\ComponentController::class);

    // Request Type
    Route::resource('request-type', App\Http\Controllers\Absen\RequestTypeController::class);

    // CG Component
    Route::group(['prefix' => 'kas'], function(){
        Route::resource('jabatan', App\Http\Controllers\CgControllers\JabatanControllers::class);
        // Project
        Route::resource('project', App\Http\Controllers\CgControllers\ProjectControllers::class);
        Route::resource('project-details', App\Http\Controllers\CgControllers\ProjectDetailsController::class);
        Route::post('/import-excel', [App\Http\Controllers\CgControllers\ProjectDetailsController::class, 'importExcel'])->name('import.excel');

        Route::resource('shift', App\Http\Controllers\CgControllers\ShiftControllers::class);
        Route::resource('schedule', App\Http\Controllers\CgControllers\ScheduleControllers::class);
        Route::resource('backup-schedule', App\Http\Controllers\CgControllers\ScheduleBackupControllers::class);
        // Get manpower Backup
        Route::get('/get-employees/{projectId}',[ App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getManPower']);
        Route::post('/import-schedule', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'importSchedule'])->name('import.schedule');
        Route::get('export-schedule', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'exportSchedule'])->name('export.schedule');

        // Schedule Details
        Route::get('/scheduleData/details/{project}/{periode}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetails'])->name('schedule.details');
        Route::get('/schedule/details/{project}/{periode}/{employee}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetailsEmployee'])->name('schedule.employee');

        // Day Off
        Route::get('/getEmployeesWithDayOff', [App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getEmployeesWithDayOff'])->name('getEmployeesWithDayOff.backup');
        Route::get('/getEmployeesReplaceSchedule', [App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getManPower'])->name('getManPower.backup');

        // Payroll
        Route::resource('payroll-kas', App\Http\Controllers\CgControllers\PayrolNS::class);

        // Get Employee
        Route::get('/get-employees', [App\Http\Controllers\CgControllers\PayrolNS::class, 'getEmployees'])->name('employee.unit');
        Route::post('/import-employees', [App\Http\Controllers\Employee\EmployeeController::class, 'importEmployee'])->name('import.employee');

        // Learning
        Route::resource('knowledge_base',App\Http\Controllers\knowledge\KnowledgeController::class);
        Route::post('/knowledge.store', [App\Http\Controllers\knowledge\KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::delete('/knowledge.destroy/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'destroy'])->name('knowledge.destroy');
        Route::get('/add_soal/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'add_soal'])->name('add_soal');
        Route::post('/knowledge.save_soal', [App\Http\Controllers\knowledge\KnowledgeController::class, 'save_soal'])->name('knowledge.save_soal');
        //knowledge -asign user
        Route::get('/asign_user/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'asign_user'])->name('asign_user');
        // Route::get('/read_test/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'read_test'])->name('read_test');
        Route::get('/pdf.preview/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'pdfPreview'])->name('pdf.preview');
        Route::post('/knowledge.save_asign_users', [App\Http\Controllers\knowledge\KnowledgeController::class, 'save_asign_users'])->name('knowledge.save_asign_users');

        // Pengajuan Schedule
        Route::resource('pengajuan-schedule', App\Http\Controllers\PengajuanSchedule\PengajuanController::class);
        Route::get('/schedule/details/{project}/{periode}', [App\Http\Controllers\PengajuanSchedule\PengajuanController::class, 'showDetails'])->name('pengajuanschedule.details');
            // Setujui Pengajuan Schedule
            Route::post('/schedule-request/{project}/{periode}', [App\Http\Controllers\PengajuanSchedule\PengajuanController::class, 'updateStatusSetuju'])->name('approve.requestschedule');
            Route::post('/reject-schedule-request/{project}/{periode}', [App\Http\Controllers\PengajuanSchedule\PengajuanController::class, 'RejectSchedule'])->name('reject.requestschedule');
            

    });
});

Route::get('/get-attendance-data', [App\Http\Controllers\Employee\EmployeeController::class, 'getAttendanceData'])->name('absen.getDataDetails');

// Tes Email
Route::get('/kirim-email', [App\Http\Controllers\DashboardController::class, 'kirimEmail']);
Route::get('/send-email/{id}', [App\Http\Controllers\DashboardController::class, 'sendEmail'])->name('send-email');

// Login
Route::controller(LoginController::class)->group(function(){
    Route::get('login','index')->name('login');
    Route::post('login/proses','proses');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('login');
})->name('logout');

Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
