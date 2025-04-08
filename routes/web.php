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

/** DMAIC */
Route::get('/dmaic-form', [App\Http\Controllers\DMAIC\DmaicController::class, 'create'])->name('dmic-form');
Route::post('/submit-dmaic', [App\Http\Controllers\DMAIC\DmaicController::class, 'store'])->name('submit-dmaic');
Route::get('/dmaic-success', [App\Http\Controllers\DMAIC\DmaicController::class, 'page_success'])->name('dmaic-success');
Route::view('/pengajuan-asset-form','pages.asset-management.pengajuan_hp_form')->name('pengajuan-asset-form');
Route::view('voice-frontline', 'pages.voice.form')->name('voice-frontline');
Route::view('voice-frontline-detail/{id}', 'pages.voice.detail')->name('voice-frontline-detail');

Route::view('forgot-password','pages.auth.forgot_password')->name('forgot-password');
Route::view('form-forgot-password/{id}','pages.auth.form_forgot_password')->name('form-forgot-password');

// Dashboard
Route::middleware(['auth', 'permission:dashboard_access'])->group(function () {

    // Employee Loan
    Route::resource('employee-loan', App\Http\Controllers\Loan\LoanController::class);

    // Anggota Koperasi
    Route::resource('koperasi-page', App\Http\Controllers\Koperasi\AnggotaController::class);

    // Keuangan Kas
    Route::resource('buku-kas', App\Http\Controllers\BukuKas\KasController::class);
    // Customer 
    Route::resource('customer', App\Http\Controllers\BukuKas\CustomerController::class);

    // Garda Pratama
    Route::resource('garda-pratama', App\Http\Controllers\GardaPratama\GpController::class);

    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::post('/checklist/toggle', [App\Http\Controllers\DashboardController::class, 'toggle'])->name('checklist.toggle');
    Route::resource('/', DashboardController::class);

    // Payslip
    Route::get('/myslip', [App\Http\Controllers\Payrol\PayslipController::class, 'payslipuser'])->name('mySlip');
    Route::resource('payslip', App\Http\Controllers\Payrol\PayslipController::class);
    Route::resource('payslip-ns', App\Http\Controllers\Payrol\PayslipnsController::class);
    Route::resource('absen', App\Http\Controllers\Absen\AbsenController::class);

    // absenduplikat
    Route::get('duplikat-absen', [App\Http\Controllers\Absen\AbsenController::class, 'duplicateAttendance'])->name('absens.index');
    Route::delete('/absens/delete/{nik}/{tanggal}', [App\Http\Controllers\Absen\AbsenController::class, 'deleteDuplicate'])->name('absens.deleteDuplicate');
    Route::delete('/absens/bulk-delete-duplicates', [App\Http\Controllers\Absen\AbsenController::class, 'bulkDeleteDuplicates'])->name('absens.bulkDeleteDuplicates2');

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
        Route::get('bulk-lembur', [App\Http\Controllers\Absen\RequestControllers::class, 'bulk_lembur'])->name('bulk-lembur');
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
    Route::get('/check-attendance', [App\Http\Controllers\Payrol\PayrolController::class, 'checkAttendance'])->name('uangmakan.check');

        // Ns Import
        Route::post('/payroll/import', [App\Http\Controllers\Payrol\PayrolController::class, 'importns'])->name('payroll.import.post');

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
    Route::view('task-report', 'pages.operational.task.report')->name('task-report');
    Route::get('task-download-qr/{id}', [App\Http\Controllers\Taskmanagement\TaskController::class,'download_qr'])->name('task-download-qr');
    Route::post('/import-excel-patroli', [App\Http\Controllers\Taskmanagement\TaskController::class, 'import'])->name('import-excel-patroli');
    
    
    //patroli
    Route::get('/patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'index'])->name('patroli');
    Route::get('/report-patrol/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'report'])->name('report-patrol');
    Route::get('/scan_qr', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'scan_qr'])->name('scan_qr');
    Route::get('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'checklist_task'])->name('checklist');
    Route::post('/checklist/{id}', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'post_code'])->name('checklist.post');
    Route::post('/save_patroli', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'store'])->name('save_patroli');
    Route::get('/analityc', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'analityc'])->name('analityc');

    Route::get('/preview_test', [App\Http\Controllers\Taskmanagement\PatroliController::class, 'preview_test'])->name('preview_test');
    
    // Feedback
    Route::post('/users/feedback', 'DashboardController@StoreFeedback')->name('feedback.store');
    Route::get('/unix-code', [App\Http\Controllers\Employee\EmployeeController::class,'unix_code'])->name('unix-code');

    //task management global
    Route::resource('taskg', App\Http\Controllers\Ops\TaskgController::class);
    Route::get('/task_edit/{id}', [App\Http\Controllers\ops\TaskgController::class, 'edit'])->name('task_edit');
    Route::post('/save-task-item', [App\Http\Controllers\ops\TaskgController::class, 'save_item'])->name('save-task-item');
    Route::post('/save_data', [App\Http\Controllers\ops\TaskgController::class, 'store'])->name('save_data');

    // Task V2
    Route::resource('task-management', App\Http\Controllers\Taskmanagement\TaskMasterController::class);
    Route::get('task-management/{id}/download', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'downloadAttachment'])->name('task.download');
    Route::get('subtask/{id}/download', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'downloadAttachmentSubtask'])->name('subtask.download');
    Route::put('/status/{id}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'masterStatus'])->name('status.update');
    Route::post('/subtasks', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'storeSubtask'])->name('subtasks.store');
        // Subtask Status
        Route::post('/subtask/update-status/{id}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'updateStatus'])->name('update-status.subtask');
        Route::patch('/tasks/{id}/status', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'updateStatusDrag']);
        Route::put('subtask-edit/{id}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'updateSubtask'])->name('subtask.update');
        Route::delete('subtask/{id}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'destroySubtask'])->name('subtask.destroy');
        // Tracking
        Route::post('/subtasks/{id}/start', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'startTracking'])->name('subtasks.start');
        Route::post('/subtasks/{id}/stop', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'stopTracking'])->name('subtasks.stop');
        // Komentar
        Route::post('/tasks/{id}/comments', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'storeKomen'])->name('tasks.comments.store');
        // Workspace
        Route::post('/workspace', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'storeWorkspace'])->name('workspace.store');
        Route::get('task-management/workspace/{folder}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'showWorkspace'])->name('workspace.show');
        Route::delete('workspace/{id}', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'destroyWorkspace'])->name('workspace.destroy');
        // Report
        Route::get('task-management/{id}/report', [App\Http\Controllers\Taskmanagement\TaskMasterController::class, 'mapReport'])->name('task.reportmap');
    // Emergency 
    Route::group(['prefix' => 'emergency'], function(){
        Route::resource('emergency-data', App\Http\Controllers\Emergency\EmergencyController::class);
        Route::resource('emergency-category', App\Http\Controllers\Emergency\EmergencyCategoryController::class);
        Route::post('/update-status/{id}', [App\Http\Controllers\Emergency\EmergencyController::class, 'updateStatus'])->name('update.status');
        Route::put('/update-status-cancel/{id}', [App\Http\Controllers\Emergency\EmergencyController::class, 'CancelRequest'])->name('cancel.status');
        Route::get('/page', [App\Http\Controllers\Emergency\EmergencyController::class, 'userpages'])->name('emergency.user');
        Route::post('/submit-emergency', [App\Http\Controllers\Emergency\EmergencyController::class, 'storeRequest'])->name('emergency.store');
    });

    // Data Performace Appraisal
    Route::group(['prefix' => 'performance-appraisal'], function(){
        // Setting PA
        Route::get('/setting', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'setting'])->name('setting.pa');
        Route::get('/performance-master', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'indexPA'])->name('index.pa');
        Route::delete('/performance-master/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'deletePA'])->name('pa.destroy');
        Route::get('/create', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'createPA'])->name('create.pa');
        Route::post('/store-performance', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'storePerformance'])->name('store.performance');
        Route::get('/edit-performance/{id}/edit', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'editPerformance'])->name('edit.pa');
        Route::put('/edit-performance/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'updatePerformance'])->name('update.pa');
        Route::get('/my-performance', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'MyPerformanceList'])->name('mypa.list');
        Route::get('/my-performance/{id}/details', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'DetailPerformance'])->name('details.Mypa');
        Route::get('/approve-myperformance/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'approvePa'])->name('approve.Mypa');
        Route::get('/get-faktor/{level}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'getFactorsByLevel'])->name('update-active-faktor');
        Route::get('/get-kategori/{level}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'getTotalKategori'])->name('update-active-kategori');
            Route::group(['prefix' => 'settings'], function(){
                // Kategori
                Route::get('/kategori', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'IndexsettingKategori'])->name('kategori-pa.setting');
                Route::post('/kategori', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'storeKategori'])->name('kategori-pa.store');
                Route::put('/kategori/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'updateKategori'])->name('kategori-pa.update');
                Route::delete('/kategori/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'deleteKategori'])->name('kategori-pa.destroy');
                // Predikat
                Route::get('/predikat', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'IndexsettingPredikat'])->name('predikat-pa.setting');
                Route::post('/predikat', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'storePredikat'])->name('predikat-pa.store');
                Route::put('/predikat/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'updatePredikat'])->name('predikat-pa.update');
                Route::delete('/predikat/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'deletePredikat'])->name('predikat-pa.destroy');
                // Faktor Data
                Route::get('/faktor', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'IndexsettingFaktor'])->name('faktor-pa.setting');
                Route::post('/faktor', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'storeFaktor'])->name('faktor-pa.store');
                Route::put('/faktor/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'updateFaktor'])->name('faktor-pa.update');
                Route::delete('/faktor/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'deleteFaktor'])->name('faktor-pa.destroy');
                Route::get('/duplikat-faktor/{id}', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'duplicateFaktor'])->name('faktor-pa.duplikat');
                Route::get('/performance/rata-rata', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'AllPerformanceList'])->name('pa.ratarata');
                Route::get('/performance/download', [App\Http\Controllers\PerformanceAppraisal\PerformanceController::class, 'downloadPDF'])->name('pa.download');
            });
    });

    
});

Route::middleware(['auth', 'permission:hc_access'])->group(function () {
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::view('/employee-referal','pages.hc.karyawan.referal')->name('employee-referal');
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');    
});

// Superadmin Access
Route::middleware(['auth', 'permission:superadmin_access'])->group(function () {
    // Setting App User
    Route::resource('users', UserController::class);
    Route::resource('roles', App\Http\Controllers\RolesController::class);

    // Features Menu
    Route::resource('features-management', App\Http\Controllers\Setting\Features\FeaturesController::class);
        Route::post('features-management/update-status/{id}', [App\Http\Controllers\Setting\Features\FeaturesController::class, 'updateStatus'])->name('features.status');

        
    Route::resource('slack-account', App\Http\Controllers\Slack\SlackController::class);
    Route::resource('slack-artikel', App\Http\Controllers\Automatisasi\ArtikelController::class);
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::resource('employee-resign', App\Http\Controllers\Employee\ResignController::class);
    Route::post('/employee.resign', [App\Http\Controllers\Employee\EmployeeController::class, 'resign'])->name('employee.resign');
    Route::get('/users/autocomplete', 'UserController@autocomplete')->name('users.autocomplete');

    Route::get('export-employee', [App\Http\Controllers\Employee\EmployeeController::class, 'exportEmployee'])->name('export.employee');

    Route::resource('urbanica-payroll', App\Http\Controllers\Urbanica\PayrolUrban::class);

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
    Route::get('/company/feature-company/{id}', [App\Http\Controllers\Company\CompanyController::class, 'editmenu'])->name('companymenu.set');
    Route::post('/company/features/toggle', [App\Http\Controllers\Company\CompanyController::class, 'toggleFeature'])
    ->name('company.features.toggle');
    Route::post('/company/features/bulk-toggle', [App\Http\Controllers\Company\CompanyController::class, 'bulkToggle'])->name('company.features.bulkToggle');

    Route::get('/company-settings/{company}/edit', [App\Http\Controllers\Company\CompanySettingController::class, 'edit'])->name('company-settings.edit');
    Route::put('/company-settings/{company}', [App\Http\Controllers\Company\CompanySettingController::class, 'update'])->name('company-settings.update');

    // Multi Location
    Route::prefix('company/{company}/work-locations')->middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Company\WorkLocationController::class, 'index'])->name('company.work-locations.index');
        Route::get('create', [\App\Http\Controllers\Company\WorkLocationController::class, 'create'])->name('company.work-locations.create');
        Route::post('/', [\App\Http\Controllers\Company\WorkLocationController::class, 'store'])->name('company.work-locations.store');
        
        // 🔧 Tambahkan yang ini
        Route::get('{id}/edit', [\App\Http\Controllers\Company\WorkLocationController::class, 'edit'])->name('company.work-locations.edit');
        Route::put('{id}', [\App\Http\Controllers\Company\WorkLocationController::class, 'update'])->name('company.work-locations.update');
        
        Route::delete('{id}', [\App\Http\Controllers\Company\WorkLocationController::class, 'destroy'])->name('company.work-locations.destroy');
    });
    
    


    // Menu Settings
    Route::resource('menu', App\Http\Controllers\MenuController::class);
    Route::post('/menu-create', [App\Http\Controllers\MenuController::class, 'store'])->name('menu-create');

    // Payroll

    // Component Master
    Route::resource('component-data', App\Http\Controllers\Payrol\ComponentController::class);
        // Update Active 
        Route::post('/update-status/{id}', [App\Http\Controllers\Payrol\ComponentController::class, 'updateStatus'])->name('update-status');
    
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

    // Data Invoice
    Route::resource('invoice', App\Http\Controllers\Invoice\InvoiceController::class);
    Route::get('/invoices/{id}/print', [App\Http\Controllers\Invoice\InvoiceController::class, 'print'])->name('invoice.print');


    // Pengumuman Routes
    Route::resource('pengumuman', App\Http\Controllers\Pengumuman\PengumumanController::class);
        Route::get('pengumuman/{id}/download', [App\Http\Controllers\Pengumuman\PengumumanController::class, 'downloadAttachment'])->name('pengumuman.download');

    // News
    Route::resource('news', App\Http\Controllers\News\NewsController::class);

    // Document
    Route::get('/folders', [App\Http\Controllers\Document\DocumentController::class, 'indexFolder'])->name('folders.index');
    Route::get('/folders/{folder}', [App\Http\Controllers\Document\DocumentController::class, 'showFolder'])->name('folders.show');
    Route::post('/folders', [App\Http\Controllers\Document\DocumentController::class, 'storeFolder'])->name('folders.store');
    Route::post('/files/{folderId}', [App\Http\Controllers\Document\DocumentController::class, 'storeFiles'])->name('files.store');
    Route::put('folders/{id}', [App\Http\Controllers\Document\DocumentController::class, 'updateFolder'])->name('folders.update');
    Route::delete('folders/{id}', [App\Http\Controllers\Document\DocumentController::class, 'deleteFolder'])->name('folders.delete');
    Route::delete('files-delete/{id}', [App\Http\Controllers\Document\DocumentController::class, 'deleteFile'])->name('files.delete');
    Route::get('file/download/{id}', [App\Http\Controllers\Document\DocumentController::class, 'download'])->name('file.download');
    // App Setting
    Route::resource('setting', App\Http\Controllers\Setting\SettingController::class);
    Route::get('version', [App\Http\Controllers\Setting\SettingController::class, 'apps_version'])->name('version');
    Route::get('birthdays-messages', [App\Http\Controllers\Setting\SettingController::class, 'birthdays_messages'])->name('birthdays-messages');
    Route::post('version-save', [App\Http\Controllers\Setting\SettingController::class, 'save_version'])->name('version-save');
    Route::post('save-messages', [App\Http\Controllers\Setting\SettingController::class, 'save_messages'])->name('save-messages');
        // Pajak
        Route::resource('pajak', App\Http\Controllers\Pajak\PajakController::class);
        Route::get('/pajak-data/{pajakid}', [App\Http\Controllers\Pajak\PajakController::class, 'pajakdetails'])->name('pajak.details');

        // Additional Component
        Route::resource('additional-component', App\Http\Controllers\Component\ComponentController::class);
            // Get Employee
            Route::get('/get-employees-component', [App\Http\Controllers\Component\ComponentController::class, 'getEmployeesComponent'])->name('employee.datacomponent');
            Route::get('/get-component', [App\Http\Controllers\Component\ComponentController::class, 'getComponent'])->name('component.additional');
            Route::get('/additional-component/show/{code_master}', [App\Http\Controllers\Component\ComponentController::class, 'showDetails'])->name('additional-component.showDetails');

        //Log Activities
        Route::resource('activities', App\Http\Controllers\UserActivitiesController::class); 

        // Koperasi
        Route::resource('koperasi', App\Http\Controllers\Koperasi\KoperasiController::class);

            //member
            Route::get('/approve-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'ApproveAnggota'])->name('ApproveAnggota'); 
            Route::get('/reject-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'RejectAnggota'])->name('RejectAnggota'); 
            Route::get('/reapply-anggota/{employee_code}', [App\Http\Controllers\Koperasi\AnggotaController::class, 'ReapplyAnggota'])->name('ReapplyAnggota');

            Route::get('/daftar-anggota', [App\Http\Controllers\Koperasi\KoperasiController::class, 'anggotapage'])->name('anggota.page');
            Route::get('/daftar-pengajuan-anggota', [App\Http\Controllers\Koperasi\KoperasiController::class, 'pendinganggota'])->name('pendinganggota.page'); 
            Route::get('/daftar-pengajuan-pinjaman', [App\Http\Controllers\Koperasi\KoperasiController::class, 'pinjamananggota'])->name('pinjamananggota.page'); 

            // Dashboard Koperasi
            Route::get('/koperasi-dashboard', [App\Http\Controllers\Koperasi\KoperasiController::class, 'dashboard'])->name('dashboard.koperasi');

            // Saving History
            Route::resource('saving-history', App\Http\Controllers\Koperasi\SavingsController::class);

            // Loan Settings
            Route::resource('loan-settings', App\Http\Controllers\Koperasi\LoanSettingController::class);

            // Excel Export
            Route::get('/download-excel', [App\Http\Controllers\Koperasi\KoperasiController::class, 'downloadExcel']);

            // Pengajuan Pinjaman
            Route::resource('pengajuan-pinjaman', App\Http\Controllers\Koperasi\PengajuanPinjamanController::class);
                // Set Status 
                Route::get('/approve-pinjaman/{employee_code}', [App\Http\Controllers\Koperasi\PengajuanPinjamanController::class, 'ApprovePinjaman'])->name('ApprovePinjaman'); 
                Route::get('/reject-pinjaman/{employee_code}', [App\Http\Controllers\Koperasi\PengajuanPinjamanController::class, 'RejectPinjaman'])->name('RejectPinjaman'); 

                // history Pembayaran
                Route::get('/history-pembayaran', [App\Http\Controllers\Koperasi\KoperasiController::class, 'historypayment'])->name('historypayment'); 

                // Kontral
                Route::get('/download-kontrak/{employee_code}', function ($employee_code) {
                    $path = storage_path('app/public/kontrak/kontrak_' . $employee_code . '.pdf');
                
                    return response()->file($path, [
                        'Content-Type' => 'application/pdf',
                    ]);
                });
                Route::get('/export-anggota', [App\Http\Controllers\Koperasi\KoperasiController::class, 'exportAnggota'])->name('export.anggota');

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
        Route::post('/read-excel', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'readExcel'])->name('read.excel');
        Route::post('/post-data-schedule', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'post_data_schedule'])->name('post-data-schedule');
        // Schedule Details
        Route::get('/scheduleData/details/{project}/{periode}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetails'])->name('schedule.details');
        Route::get('/schedule/details/{project}/{periode}/{employee}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetailsEmployee'])->name('schedule.employee');
        Route::get('/schedule/stop_report/{employee}/{periode}/{project}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'stop_report'])->name('schedule.stop_report');
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
        Route::get('rekap-report', [App\Http\Controllers\Report\ReportController::class,'rekap_report'])->name('rekap-report');
        Route::resource('double', App\Http\Controllers\Report\CheckdoubleController::class);

        /** Recruitments */
        Route::get('dashboard-recruitment',[App\Http\Controllers\Recruitments\DashboardController::class, 'index'])->name('dashboard-recruitment');
        Route::resource('job-aplicant', App\Http\Controllers\Recruitments\JobAplicantController::class);
        Route::get('/job-aplicant', [App\Http\Controllers\Recruitments\JobAplicantController::class, 'index'])->name('job-aplicant');
        Route::post('/save-permintaan-client', [App\Http\Controllers\Recruitments\JobAplicantController::class, 'store'])->name('save-permintaan-client');
        Route::get('/medis', [App\Http\Controllers\Recruitments\MedisController::class, 'index'])->name('medis');
        Route::get('/test', [App\Http\Controllers\Recruitments\TestController::class, 'index'])->name('test');
        Route::get('/penempatan', [App\Http\Controllers\Recruitments\PenempatanController::class, 'index'])->name('penempatan');
        Route::get('/create-truest/{id}', [App\Http\Controllers\Recruitments\PenempatanController::class, 'create'])->name('create-truest');
        Route::view('training-skip','pages.recruitments.training')->name('training-skip');
        
        
        Route::get('/map', [App\Http\Controllers\Map::class, 'index'])->name('map');
        Route::post('/map-domisili', [App\Http\Controllers\Map::class, 'update_domisili'])->name('map-domisili');
        Route::get('/map-frontline', [App\Http\Controllers\Map::class, 'map_frontline'])->name('map-frontline');

        /** DMAIC */
        Route::get('/dmaic-report', [App\Http\Controllers\DMAIC\ReportController::class, 'index'])->name('dmaic-report');

        // voice of frontline 
        Route::resource('voice', App\Http\Controllers\CgControllers\VoiceControllers::class);

        // patroli project 
        Route::view('patroli-project','pages.operational.patroli_project.index')->name('patroli-project');
        Route::view('scan-project','pages.operational.patroli_project.scan_project')->name('scan-project');
        Route::view('activity-patroli/{id}','pages.operational.patroli_project.form_activity')->name('activity-patroli');


        // Define a static view route for 'lapsit-project'
        Route::view('lapsit-project', 'pages.operational.lapsit.index')->name('lapsit-project');

        // Define a static view route for 'scan-lapsit'
        Route::view('scan-lapsit', 'pages.operational.lapsit.scan_project')->name('scan-lapsit');

        // Use Route::get for the route with a dynamic parameter
        Route::get('activity-lapsit/{id}', function ($id) {
            return view('pages.operational.lapsit.form_activity', ['id' => $id]);
        })->name('activity-lapsit');

        
        

    });

    

    // Assets Management
    Route::group(['prefix' => 'assets-management'], function(){
        // Asset Master
        Route::get('/asset', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'IndexAsset'])->name('asset.index');
        Route::post('/simpan-asset', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'StoreAsset'])->name('asset.store');
        Route::put('/update-asset/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'UpdateAsset'])->name('asset.update');
        Route::delete('/delete-asset/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'DestroyAsset'])->name('asset.destroy');

        // Asset Stock
        Route::get('/asset-stock', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'StockIndex'])->name('asset-stock.index');
        Route::post('/simpan-asset-stock', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'StockStore'])->name('asset-stock.store');
        Route::put('/update-asset-stock/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'UpdateStock'])->name('asset-stock.update');
        Route::delete('/delete-asset-stock/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'DestoryStock'])->name('asset-stock.destroy');

        // Asset Category
        Route::get('/asset-category', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'IndexCategory'])->name('asset-category.index');
        Route::post('/simpan-asset-category', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'StoreCategory'])->name('asset-category.store');
        Route::put('/update-asset-category/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'UpdateCategory'])->name('asset-category.update');
        Route::delete('/delete-asset-category/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'DestroyCategory'])->name('asset-category.destroy');

        // Vendor Asset
        Route::get('/asset-vendor', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'IndexVendor'])->name('asset-vendor.index');
        Route::post('/simpan-asset-vendor', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'VendorStore'])->name('asset-vendor.store');
        Route::put('/update-asset-vendor/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'UpdateVendor'])->name('asset-vendor.update');
        Route::delete('/delete-asset-vendor/{id}', [App\Http\Controllers\AssetManagement\AllAssetsController::class, 'DestroyVendor'])->name('asset-vendor.destroy');

        //pengajuan HP
        Route::view('/pengajuan-asset','pages.asset-management.pengajuan_hp')->name('pengajuan-asset');
    });

    // Company Setting
    Route::group(['prefix' => 'company-setting'], function(){
        Route::get('/golongan', [App\Http\Controllers\Setting\SettingController::class, 'IndexGolongan'])->name('golongan.index');
        Route::post('/save-golongan', [App\Http\Controllers\Setting\SettingController::class, 'StoreGolongan'])->name('golongan.store');
        Route::put('/update-golongan/{id}', [App\Http\Controllers\Setting\SettingController::class, 'UpdateGolongan'])->name('golongan.update');
        Route::delete('/delete-golongan/{id}', [App\Http\Controllers\Setting\SettingController::class, 'DeleteGolongan'])->name('golongan.delete');

        Route::get('/payroll-cutoff-settings', [App\Http\Controllers\Setting\Cutoff\PayrollCutoffController::class, 'edit'])->name('payroll.cutoff.edit');
        Route::put('/payroll-cutoff-settings', [App\Http\Controllers\Setting\Cutoff\PayrollCutoffController::class, 'update'])->name('payroll.cutoff.update');
    });

    
    Route::view('/sample','pages.report.patrol_pdf')->name('sample');
    
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
