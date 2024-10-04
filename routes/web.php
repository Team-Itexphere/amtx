<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('homeDev');
    return view('home');
})->name('site-url');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// public pages
Route::view('about-us', 'about-us')->name('about-us');
Route::view('our-service', 'our-service')->name('our-service');
Route::view('contact-us', 'contact-us')->name('contact-us');


Route::get('/dashboard/switch_to/{id?}', [App\Http\Controllers\Dashboard\EmployeesController::class, 'switch_to'])->name('switch_to');

Route::middleware('switch.user')->group(function () {

// dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/user-logs', [App\Http\Controllers\Dashboard\User_logsController::class, 'index'])->name('user-logs');
Route::get('/dashboard/stores', [App\Http\Controllers\Dashboard\StoresController::class, 'index'])->name('stores');
Route::get('/dashboard/employees', [App\Http\Controllers\Dashboard\EmployeesController::class, 'index'])->name('employees');
Route::get('/dashboard/cus-notes', [App\Http\Controllers\Dashboard\EmployeesController::class, 'view_cus_notes'])->name('cus-notes');
Route::get('/dashboard/cus-licenses', [App\Http\Controllers\Dashboard\Cus_licensesController::class, 'index'])->name('cus-licenses');
Route::get('/dashboard/site-info', [App\Http\Controllers\Dashboard\Site_infosController::class, 'index'])->name('site-info');
Route::get('/dashboard/comp-docs', [App\Http\Controllers\Dashboard\Comp_docsController::class, 'index'])->name('comp-docs');
Route::get('/dashboard/maintain-logs', [App\Http\Controllers\Dashboard\Maintain_logsController::class, 'index'])->name('maintain-logs');
Route::get('/dashboard/accounting', [App\Http\Controllers\Dashboard\CommunicationController::class, 'index'])->name('accounting');
Route::get('/dashboard/fleet', [App\Http\Controllers\Dashboard\FleetsController::class, 'index'])->name('fleet');
Route::get('/dashboard/fleet-routing', [App\Http\Controllers\Dashboard\Fleet_routingsController::class, 'index'])->name('fleet-routing');
Route::get('/dashboard/inventory', [App\Http\Controllers\Dashboard\InventorysController::class, 'index'])->name('inventory');
Route::get('/dashboard/invoice', [App\Http\Controllers\Dashboard\InvoicesController::class, 'index'])->name('invoice');

Route::get('/dashboard/reports', [App\Http\Controllers\Dashboard\CommunicationController::class, 'index'])->name('reports');
Route::get('/dashboard/routes', [App\Http\Controllers\Dashboard\RoutesController::class, 'index'])->name('routes');
Route::get('/dashboard/tests', [App\Http\Controllers\Dashboard\TestsController::class, 'index'])->name('tests');
Route::get('/dashboard/testing', [App\Http\Controllers\Dashboard\TestingsController::class, 'index'])->name('testing');
Route::get('/dashboard/communication', [App\Http\Controllers\Dashboard\CommunicationController::class, 'index'])->name('communication');
Route::get('/dashboard/work-orders', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'index'])->name('work-orders');
Route::get('/dashboard/work-order/service-call/images', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'wo_images'])->name('service-call-images');
Route::get('/dashboard/settings', [App\Http\Controllers\Dashboard\SettingsController::class, 'index'])->name('settings');
Route::get('/dashboard/my-profile', [App\Http\Controllers\Dashboard\EmployeesController::class, 'index'])->name('my-profile');

Route::post('/dashboard/employees/add', [App\Http\Controllers\Dashboard\EmployeesController::class, 'add']);
Route::post('/dashboard/employees/edit', [App\Http\Controllers\Dashboard\EmployeesController::class, 'edit']);
Route::post('/dashboard/my-profile', [App\Http\Controllers\Dashboard\EmployeesController::class, 'my_profile']);
Route::get('/delete/user/{id}', [App\Http\Controllers\Dashboard\EmployeesController::class, 'deactivate']);
Route::get('/restore/user/{id}', [App\Http\Controllers\Dashboard\EmployeesController::class, 'reactivate']);
Route::get('/detach/store', [App\Http\Controllers\Dashboard\EmployeesController::class, 'detach_store']);


Route::post('/dashboard/customers/licenses/add', [App\Http\Controllers\Dashboard\Cus_licensesController::class, 'add']);
Route::post('/dashboard/customers/licenses/edit', [App\Http\Controllers\Dashboard\Cus_licensesController::class, 'edit']);
Route::get('/delete/cus-license/{id}', [App\Http\Controllers\Dashboard\Cus_licensesController::class, 'delete_li']);

Route::post('/dashboard/customers/site-info/add', [App\Http\Controllers\Dashboard\Site_infosController::class, 'add']);
Route::post('/dashboard/customers/site-info/edit', [App\Http\Controllers\Dashboard\Site_infosController::class, 'edit']);

Route::post('/dashboard/customers/docs/add', [App\Http\Controllers\Dashboard\Comp_docsController::class, 'add']);
Route::post('/dashboard/customers/docs/edit', [App\Http\Controllers\Dashboard\Comp_docsController::class, 'edit']);
Route::get('/delete/comp-docs/{id}', [App\Http\Controllers\Dashboard\Comp_docsController::class, 'delete_cd']);

Route::post('/dashboard/fleet/add', [App\Http\Controllers\Dashboard\FleetsController::class, 'add']);
Route::post('/dashboard/fleet/edit', [App\Http\Controllers\Dashboard\FleetsController::class, 'edit']);

Route::post('/dashboard/inventory/add', [App\Http\Controllers\Dashboard\InventorysController::class, 'add']);
Route::post('/dashboard/inventory/edit', [App\Http\Controllers\Dashboard\InventorysController::class, 'edit']);

Route::post('/dashboard/invoice/add', [App\Http\Controllers\Dashboard\InvoicesController::class, 'add']);
Route::post('/dashboard/invoice/edit', [App\Http\Controllers\Dashboard\InvoicesController::class, 'edit']);

Route::post('/dashboard/route/add', [App\Http\Controllers\Dashboard\RoutesController::class, 'add']);
Route::post('/dashboard/route/edit', [App\Http\Controllers\Dashboard\RoutesController::class, 'edit']);
Route::post('/dashboard/route/add-rl', [App\Http\Controllers\Dashboard\RoutesController::class, 'add_rl']);
Route::post('/dashboard/route/edit-rl', [App\Http\Controllers\Dashboard\RoutesController::class, 'edit_rl']);
Route::get('/delete/route/{id}', [App\Http\Controllers\Dashboard\RoutesController::class, 'deactivate']);
Route::get('/dashboard/routes/unassign/{id}', [App\Http\Controllers\Dashboard\RoutesController::class, 'unassign']);
Route::get('/dashboard/route/notes', [App\Http\Controllers\Dashboard\EmployeesController::class, 'cus_notes']);
Route::post('/dashboard/route/add-note', [App\Http\Controllers\Dashboard\EmployeesController::class, 'add_cus_note']);
Route::post('/dashboard/route/edit-note', [App\Http\Controllers\Dashboard\EmployeesController::class, 'edit_cus_note']);

Route::post('/dashboard/tests/add/release-detection-annual-testing', [App\Http\Controllers\Dashboard\TestsController::class, 'add_rda_testing']);
Route::post('/dashboard/tests/add/atg-test', [App\Http\Controllers\Dashboard\TestsController::class, 'add_atg_test']);


//Route::post('/dashboard/communication/send', [App\Http\Controllers\Dashboard\CommunicationController::class, 'send']);
//Route::post('/dashboard/stores/add', [App\Http\Controllers\Dashboard\StoresController::class, 'add']);
//Route::post('/dashboard/stores/edit', [App\Http\Controllers\Dashboard\StoresController::class, 'edit']);
//Route::post('/dashboard/stores/equipments/add', [App\Http\Controllers\Dashboard\EquipmentsController::class, 'add']);
//Route::post('/dashboard/stores/equipments/edit', [App\Http\Controllers\Dashboard\EquipmentsController::class, 'edit']);
//Route::post('/dashboard/stores/licenses/add', [App\Http\Controllers\Dashboard\Store_licensesController::class, 'add']);
//Route::post('/dashboard/stores/licenses/edit', [App\Http\Controllers\Dashboard\Store_licensesController::class, 'edit']);
//Route::post('/dashboard/stores/fuel_tanks/add', [App\Http\Controllers\Dashboard\Fuel_tanksController::class, 'add']);
//Route::post('/dashboard/stores/fuel_tanks/edit', [App\Http\Controllers\Dashboard\Fuel_tanksController::class, 'edit']);
//Route::post('/dashboard/stores/pumps/add', [App\Http\Controllers\Dashboard\PumpsController::class, 'add']);
//Route::post('/dashboard/stores/pumps/edit', [App\Http\Controllers\Dashboard\PumpsController::class, 'edit']);
//Route::post('/dashboard/stores/tenants/add', [App\Http\Controllers\Dashboard\TenantsController::class, 'add']);
//Route::post('/dashboard/stores/tenants/edit', [App\Http\Controllers\Dashboard\TenantsController::class, 'edit']);
//Route::post('/dashboard/stores/utilities/add', [App\Http\Controllers\Dashboard\UtilitiesController::class, 'add']);
//Route::post('/dashboard/stores/utilities/edit', [App\Http\Controllers\Dashboard\UtilitiesController::class, 'edit']);
//Route::post('/dashboard/stores/vendors/add', [App\Http\Controllers\Dashboard\VendorsController::class, 'add']);
//Route::post('/dashboard/stores/vendors/edit', [App\Http\Controllers\Dashboard\VendorsController::class, 'edit']);

Route::post('/dashboard/work_orders/add', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'add']);
Route::post('/dashboard/work_orders/edit', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'edit']);
Route::get('/delete/work_orders/{id}', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'delete']);
Route::get('/work-orders/wo-pdf', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'pdfGen'])->name('wo-pdf');

Route::post('/dashboard/settings/edit', [App\Http\Controllers\Dashboard\SettingsController::class, 'edit']);


// temp routes
Route::get('/atg-test', [App\Http\Controllers\Dashboard\TestsController::class, 'atg_test']);
Route::get('/line-leak-test', [App\Http\Controllers\Dashboard\TestsController::class, 'line_leak_test']);
Route::get('/liquid-sensor-test', [App\Http\Controllers\Dashboard\TestsController::class, 'liquid_sensor_test']);
Route::get('/containment-sump-test', [App\Http\Controllers\Dashboard\TestsController::class, 'containment_sump_test']);
Route::get('/galvanic-cp-test', [App\Http\Controllers\Dashboard\TestsController::class, 'galvanic_cp_test']);
Route::get('/impressed-current-cp-test', [App\Http\Controllers\Dashboard\TestsController::class, 'impressed_current_cp_test']);
Route::get('/overfill-test', [App\Http\Controllers\Dashboard\TestsController::class, 'overfill_test']);
Route::get('/spill-bucket-test', [App\Http\Controllers\Dashboard\TestsController::class, 'spill_bucket_test']);
Route::get('/stage-1-test', [App\Http\Controllers\Dashboard\TestsController::class, 'stage_1_test']);
Route::get('/release-detection-annual-testing', [App\Http\Controllers\Dashboard\TestsController::class, 'release_detection_annual_testing']);
});


// file permissions
Route::pattern('any', '.*');
Route::group(['prefix' => 'employee-docs/{any}'], function () {
    Route::any('/', function () {
        if (!auth()->check()) {
            abort(404);
        }
    });
});