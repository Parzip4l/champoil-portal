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

    // Anggota Koperasi
    Route::resource('koperasi-page', App\Http\Controllers\Koperasi\AnggotaController::class);


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
    Route::get('/delete-attendance-backup/{date}/{nik}', [App\Http\Controllers\Absen\AbsenController::class, 'deleteAttendanceBackup']);

    // Get By Organisasi
    Route::get('/attendance/filter', [ App\Http\Controllers\Absen\AbsenController::class, 'filterByOrganization'])->name('attendance.filter');

    Route::get('absen/show/{nik}', [App\Http\Controllers\Absen\AbsenController::class, 'detailsAbsen'])->name('absen.details');
    // Backup Details
    Route::get('backup/show/{nik}', [App\Http\Controllers\Absen\AbsenController::class, 'detailsAbsenBackup'])->name('backup.details');

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
    Route::post('/task-update', [App\Http\Controllers\Taskmanagement\TaskController::class, 'update'])->name('task-update');
    
    //patroli
    Route::get('/patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'index'])->name('patroli');
    Route::get('/report-patrol/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'report'])->name('report-patrol');
    Route::get('/scan_qr', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'scan_qr'])->name('scan_qr');
    Route::get('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'checklist_task'])->name('checklist');
    Route::post('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'post_code'])->name('checklist.post');
    Route::post('/save_patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'store'])->name('save_patroli');
    
    // Feedback
    Route::post('/users/feedback', 'DashboardController@StoreFeedback')->name('feedback.store');
    Route::get('/unix-code', [App\Http\Controllers\Employee\EmployeeController::class,'unix_code'])->name('unix-code');

    //task management global
    Route::resource('taskg', App\Http\Controllers\Ops\TaskgController::class);
    Route::get('/task_edit/{id}', [App\Http\Controllers\ops\TaskgController::class, 'edit'])->name('task_edit');
    Route::post('/save-task-item', [App\Http\Controllers\ops\TaskgController::class, 'save_item'])->name('save-task-item');
    Route::post('/save_data', [App\Http\Controllers\ops\TaskgController::class, 'store'])->name('save_data');

    
});

Route::middleware(['auth', 'permission:hc_access'])->group(function () {
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');

    
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

    // Thr Routes
    Route::resource('thr-component', App\Http\Controllers\THR\ThrComponentController::class);
    Route::resource('component-data-thr', App\Http\Controllers\THR\ThrDataComponent::class);
    Route::resource('thr', App\Http\Controllers\THR\ThrController::class);

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

    // Update Backup
    Route::post('/actin-backupp/edit/{date}/{nik}', [App\Http\Controllers\Employee\EmployeeController::class, 'UpdateAbsenBackup'])->name('backupdata.editData');
    Route::post('/action-backup/create', [App\Http\Controllers\Employee\EmployeeController::class, 'CreateAbsenBackup'])->name('backupdata.createData');

    // Company Settings
    Route::resource('company', App\Http\Controllers\Company\CompanyController::class);

    // Menu Settings
    Route::resource('menu', App\Http\Controllers\MenuController::class);
    Route::post('/menu-create', [App\Http\Controllers\MenuController::class, 'store'])->name('menu-create');

    // Payroll
    // Component Master
    Route::resource('component-data', App\Http\Controllers\Payrol\ComponentController::class);
    
    // logbook
    Route::resource('logbook', App\Http\Controllers\Logbook\LogbookController::class);
    Route::get('logbook-tamu', [App\Http\Controllers\Logbook\LogbookController::class, 'tamu'])->name('logbook-tamu');
    Route::get('logbook-barang', [App\Http\Controllers\Logbook\LogbookController::class, 'barang'])->name('logbook-barang');

    // Request Type
    Route::resource('request-type', App\Http\Controllers\Absen\RequestTypeController::class);

    // Divisi Routes
    Route::resource('divisi', App\Http\Controllers\Divisi\DivisiController::class);

    // Organisasi Routes
    Route::resource('organisasi', App\Http\Controllers\Organisasi\OrganisasiController::class);

    // App Setting
    Route::resource('setting', App\Http\Controllers\Setting\SettingController::class);
        // Pajak
        Route::resource('pajak', App\Http\Controllers\Pajak\PajakController::class);
        Route::get('/pajak-data/{pajakid}', [App\Http\Controllers\Pajak\PajakController::class, 'pajakdetails'])->name('pajak.details');

        // Additional Component
        Route::resource('additional-component', App\Http\Controllers\Component\ComponentController::class);
            // Get Employee
            Route::get('/get-employees-component', [App\Http\Controllers\Component\ComponentController::class, 'getEmployeesComponent'])->name('employee.datacomponent');
            Route::get('/get-component', [App\Http\Controllers\Component\ComponentController::class, 'getComponent'])->name('component.additional');
            Route::get('/additional-component/show/{code_master}', [App\Http\Controllers\Component\ComponentController::class, 'showDetails'])->name('additional-component.showDetails');

        // Koperasi
        Route::resource('koperasi', App\Http\Controllers\Koperasi\KoperasiController::class);
            //member
            Route::get('/approve-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'ApproveAnggota'])->name('ApproveAnggota'); 
            Route::get('/reject-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'RejectAnggota'])->name('RejectAnggota'); 
            Route::get('/reapply-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'ReapplyAnggota'])->name('ReapplyAnggota'); 

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
        Route::post('/schedule-store', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'store'])->name('schedule-store');

        // Update Manual Schedule
        Route::post('/schedules/edit/{schedule_code}/{tanggal}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'editScheduleForm'])->name('schedules.edit');

        // Day Off
        Route::get('/getEmployeesWithDayOff', [App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getEmployeesWithDayOff'])->name('getEmployeesWithDayOff.backup');
        Route::get('/getEmployeesReplaceSchedule', [App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getManPower'])->name('getManPower.backup');

        // Payroll
        Route::resource('payroll-kas', App\Http\Controllers\CgControllers\PayrolNS::class);

        // Get Employee
        Route::get('/get-employees', [App\Http\Controllers\CgControllers\PayrolNS::class, 'getEmployees'])->name('employee.unit');
        
        Route::post('/import-employees', [App\Http\Controllers\Employee\EmployeeController::class, 'importEmployee'])->name('import.employee');

        // Backup Log
        Route::get('/backup-log', [App\Http\Controllers\Absen\AbsenController::class, 'indexbackup'])->name('backup.log');

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
        
        /** Report */
        Route::resource('report', App\Http\Controllers\Report\ReportController::class);
        Route::get('report-detail/{id}/{periode}',[App\Http\Controllers\Report\ReportController::class,'show'])->name('report-detail');




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
