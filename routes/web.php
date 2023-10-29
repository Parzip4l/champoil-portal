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
    
    Route::get('/get-last-product-code', 'ProductController@getLastProductCode');
    Route::get('/get-purchase-data', 'DashboardController@getSalesData')->name('get-purchase-data');
    Route::resource('isi-survei', SurveyController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('invoice', App\Http\Controllers\Invoice\InvoiceController::class);
    Route::resource('absen', App\Http\Controllers\Absen\AbsenController::class);
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
    Route::resource('payslip-ns', App\Http\Controllers\Payrol\PayslipnsController::class);

    // Classroom
    Route::get('/read_test/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'read_test'])->name('read_test');
    Route::get('/pdf.preview/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'pdfPreview'])->name('pdf.preview');
    Route::get('/kas/user.test/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'user_test'])->name('kas/user.test');
    Route::post('/knowledge.save_test_user', [App\Http\Controllers\knowledge\KnowledgeController::class, 'submit_user'])->name('knowledge.save_test_user');
    Route::get('/list-class', [App\Http\Controllers\knowledge\KnowledgeController::class, 'list_classroom'])->name('list-class');
    Route::get('/start_class/{id}', [App\Http\Controllers\knowledge\KnowledgeController::class, 'start_class'])->name('start_class');
});

Route::middleware(['auth', 'permission:sales_access'])->group(function () {
    Route::resource('sales', SalesController::class);
    Route::resource('delivery-orders', DeliveryorderController::class);
    // Growth
    Route::resource('growth', GrowthController::class);
    // Contact
    Route::resource('contact', ContactController::class);
});

Route::middleware(['auth', 'permission:purchase_access'])->group(function () {
    Route::resource('purchase', PurchaseController::class);
    Route::get('purchase/receive/{id}', 'PurchaseController@receiveProductShow')->name('purchase.receive');
    Route::post('/send-purchase-to-slack/{purchase}', 'PurchaseController@sendToSlack')->name('purchase.sendToSlack');
    Route::post('/purchase/{id}/partial_receive', 'PurchaseController@partialReceive')->name('purchase.partial_receive');
});

Route::middleware(['auth', 'permission:accounting_access'])->group(function () {
    Route::resource('payment-regist', PaymentRegistController::class);
    Route::resource('journal', JournalController::class); 
    Route::get('/inventory-product/by-category/{category_id}', 'ProductController@getProductsByCategory')->name('product.byCategory');
    Route::get('/inventory-product/by-warehouse/{warehouse_id}', 'ProductController@getProductsByWarehouse')->name('product.byWarehouse');
    Route::resource('uom-categories', UomCategoryController::class);
    Route::resource('uom', UomController::class);
    Route::resource('warehouse-location', WarehouselokController::class);
    Route::resource('inventory-product', ProductController::class);
    Route::resource('analytics-plans', App\Http\Controllers\Analytics\AnalyticsPlansController::class);
    Route::resource('contact', ContactController::class);

    // Analytics Account
    Route::resource('analytics-account', App\Http\Controllers\Analytics\AnalyticsAccountController::class);

    // Invoice
    Route::resource('invoice', App\Http\Controllers\Invoice\InvoiceController::class);

    // Top
    Route::resource('terms-of-payment', App\Http\Controllers\Payment_terms\PaymentController::class);
    // Journal Item
    Route::resource('journal-item', App\Http\Controllers\Journal\JournalItemsController::class);
    Route::resource('journal-entry', App\Http\Controllers\Journal\JournalEntryController::class);
    Route::resource('profit-loss', App\Http\Controllers\AccountingReports\ProfitlossController::class);

    // Accounting 
    Route::resource('coa', CoaController::class);
    Route::resource('account-type', AccountTypeController::class);

    // Vendor Bills
    Route::resource('vendor-bills', VendorbillController::class);
    Route::resource('product-category', ProductCategoryController::class);

    Route::resource('tax', TaxController::class);
});

Route::middleware(['auth', 'permission:inventory_access,formulation_access'])->group(function () {
    Route::get('/inventory-product/by-category/{category_id}', 'ProductController@getProductsByCategory')->name('product.byCategory');
    Route::get('/inventory-product/by-warehouse/{warehouse_id}', 'ProductController@getProductsByWarehouse')->name('product.byWarehouse');
    Route::resource('product-category', ProductCategoryController::class);
    Route::resource('inventory-product', ProductController::class);
});

Route::middleware(['auth', 'permission:formulation_access'])->group(function () {
    Route::resource('rnd-check', App\Http\Controllers\Rnd\PenetrasiController::class);
    Route::resource('rnd-check-kuhl', App\Http\Controllers\Rnd\KuhlController::class);
});

Route::middleware(['auth', 'permission:ops_access'])->group(function () {
    Route::resource('uom-categories', UomCategoryController::class);
    Route::resource('uom', UomController::class);
    Route::resource('warehouse-location', WarehouselokController::class);
    Route::resource('inventory-product', ProductController::class);
    Route::resource('warehouse-stock', App\Http\Controllers\Warehousestock\FngController::class);
    Route::resource('warehouse-stock-pck', App\Http\Controllers\Warehousestock\PckController::class);
    Route::resource('warehouse-stock-rma', App\Http\Controllers\Warehousestock\RmaController::class);
    Route::resource('manual-delivery', ManualDeliveryController::class);
    Route::resource('product-category', ProductCategoryController::class);
    
    //task management
    Route::resource('task', App\Http\Controllers\Taskmanagement\TaskController::class);
    Route::resource('list-task', App\Http\Controllers\Taskmanagement\ListController::class);
    Route::get('/add_task/{id}', [App\Http\Controllers\Taskmanagement\ListController::class, 'list_task'])->name('add_task');
    Route::get('/qrcode', [App\Http\Controllers\Taskmanagement\TaskController::class, 'qr_code'])->name('qrcode');
});

Route::middleware(['auth', 'permission:hc_access'])->group(function () {
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');
    Route::resource('payslip-ns', App\Http\Controllers\Payrol\PayslipnsController::class);
});

Route::middleware(['auth', 'permission:creative_access'])->group(function () {
    
});

// Superadmin Access
Route::middleware(['auth', 'permission:superadmin_access'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('slack-account', App\Http\Controllers\Slack\SlackController::class);
    Route::resource('slack-artikel', App\Http\Controllers\Automatisasi\ArtikelController::class);
    Route::put('/users/{id}/update-password', 'UserController@changePassword')->name('pass.update');
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
    Route::get('/users/autocomplete', 'UserController@autocomplete')->name('users.autocomplete');
    Route::put('/manual-delivery/{id}/update-kiriman', 'ManualDeliveryController@UpdateSeluruhData')->name('manual-delivery.UpdateData');

    // Payrol Data
    Route::resource('payrol-component', App\Http\Controllers\Payrol\PayrolComponent::class);
    Route::resource('payroll', App\Http\Controllers\Payrol\PayrolController::class);
    
    // Component Ns
    Route::get('/component-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'createns'])->name('component.ns');
    Route::post('/store-ns', [App\Http\Controllers\Payrol\PayrolComponent::class, 'storens'])->name('componentns.store');
    Route::get('/payrol-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'indexns'])->name('payroll.ns');
    Route::get('/get-weeks', [App\Http\Controllers\Payrol\PayrolController::class, 'getWeeks'])->name('getWeek');
    Route::post('/payroll-ns', [App\Http\Controllers\Payrol\PayrolController::class, 'storens'])->name('payrollns.store');
    Route::resource('payslip-ns', App\Http\Controllers\Payrol\PayslipnsController::class);

    // Update Absen
    Route::post('/action/edit/{date}', [App\Http\Controllers\Employee\EmployeeController::class, 'UpdateAbsen'])->name('attendance.editData');
    Route::post('/action/create', [App\Http\Controllers\Employee\EmployeeController::class, 'CreateAbsen'])->name('attendance.createData');


    // Payroll
        // Component Master
        Route::resource('component-data', App\Http\Controllers\Payrol\ComponentController::class);
        
    // CG Component
    Route::group(['prefix' => 'kas'], function(){
        Route::resource('jabatan', App\Http\Controllers\CgControllers\JabatanControllers::class);
        Route::resource('project', App\Http\Controllers\CgControllers\ProjectControllers::class);
        Route::resource('project-details', App\Http\Controllers\CgControllers\ProjectDetailsController::class);
        Route::resource('shift', App\Http\Controllers\CgControllers\ShiftControllers::class);
        Route::resource('schedule', App\Http\Controllers\CgControllers\ScheduleControllers::class);
        Route::resource('backup-schedule', App\Http\Controllers\CgControllers\ScheduleBackupControllers::class);

        // Schedule Details
        Route::get('/schedule/details/{project}/{periode}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetails'])->name('schedule.details');
        Route::get('/schedule/details/{project}/{periode}/{employee}', [App\Http\Controllers\CgControllers\ScheduleControllers::class, 'showDetailsEmployee'])->name('schedule.employee');

        // Day Off
        Route::get('/getEmployeesWithDayOff', [App\Http\Controllers\CgControllers\ScheduleBackupControllers::class, 'getEmployeesWithDayOff'])->name('getEmployeesWithDayOff.backup');

        // Payroll
        Route::resource('payroll-kas', App\Http\Controllers\CgControllers\PayrolNS::class);

        // Get Employee
        Route::get('/get-employees', [App\Http\Controllers\CgControllers\PayrolNS::class, 'getEmployees'])->name('employee.unit');

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
    });
});

Route::get('/get-attendance-data', [App\Http\Controllers\Employee\EmployeeController::class, 'getAttendanceData'])->name('absen.getDataDetails');;

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
