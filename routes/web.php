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
    Route::resource('dashboard', DashboardController::class);
    Route::resource('/', DashboardController::class);
    Route::get('/get-last-product-code', 'ProductController@getLastProductCode');
    Route::get('/get-purchase-data', 'DashboardController@getSalesData')->name('get-purchase-data');
    Route::resource('isi-survei', SurveyController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('invoice', App\Http\Controllers\Invoice\InvoiceController::class);
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

Route::middleware(['auth', 'permission:inventory_access'])->group(function () {
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
    Route::post('/update-status', 'ManualDeliveryController@updateStatus')->name('delivery.updateStatus');
    Route::resource('product-category', ProductCategoryController::class);
});

Route::middleware(['auth', 'permission:hc_access'])->group(function () {
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
});

Route::middleware(['auth', 'permission:creative_access'])->group(function () {
    
});

Route::middleware(['auth', 'permission:superadmin_access'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('slack-account', App\Http\Controllers\Slack\SlackController::class);
    Route::resource('slack-artikel', App\Http\Controllers\Automatisasi\ArtikelController::class);
    Route::put('/users/{id}/update-password', 'UserController@changePassword')->name('pass.update');
    Route::resource('employee', App\Http\Controllers\Employee\EmployeeController::class);
});



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
