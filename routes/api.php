<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/login', [App\Http\Controllers\Dashboard\EmployeesController::class, 'login']); 
//Route::middleware('auth:sanctum')->post('/logout', [App\Http\Controllers\Dashboard\EmployeesController::class, 'logout']);

Route::middleware('auth:api')->get('/fleets/{id?}', [App\Http\Controllers\Dashboard\FleetsController::class, 'list']);

Route::middleware('auth:api')->get('/fleet-routing/{fleet_id}', [App\Http\Controllers\Dashboard\Fleet_routingsController::class, 'list']);
Route::middleware('auth:api')->get('/fleet-routing/add/{fleet_id}', [App\Http\Controllers\Dashboard\Fleet_routingsController::class, 'add']);
Route::middleware('auth:api')->get('/fleet-routing/edit/{fleet_r_id}', [App\Http\Controllers\Dashboard\Fleet_routingsController::class, 'edit']);

Route::middleware('auth:api')->post('/invoice', [App\Http\Controllers\Dashboard\InvoicesController::class, 'editInvoice']);
Route::middleware('auth:api')->get('/invoice', [App\Http\Controllers\Dashboard\InvoicesController::class, 'inv_list']);

Route::middleware('auth:api')->get('/license/{cus_id}', [App\Http\Controllers\Dashboard\Cus_licensesController::class, 'li_list']);
Route::middleware('auth:api')->get('/site-info/{cus_id}', [App\Http\Controllers\Dashboard\Site_infosController::class, 'si_list']);
Route::middleware('auth:api')->get('/customers', [App\Http\Controllers\Dashboard\EmployeesController::class, 'get_customers']);
Route::middleware('auth:api')->post('customer/notes', [App\Http\Controllers\Dashboard\EmployeesController::class, 'add_cus_note']);
Route::middleware('auth:api')->post('customer/notes/update', [App\Http\Controllers\Dashboard\EmployeesController::class, 'update_cus_note']);

Route::middleware('auth:api')->get('/testing', [App\Http\Controllers\Dashboard\TestingsController::class, 'ques_list']);
Route::middleware('auth:api')->get('/store-testings', [App\Http\Controllers\Dashboard\TestingsController::class, 'store_testings']);
Route::middleware('auth:api')->get('/tests', [App\Http\Controllers\Dashboard\TestingsController::class, 'tests_list']);
Route::middleware('auth:api')->post('/testing/create', [App\Http\Controllers\Dashboard\TestingsController::class, 'create']);
Route::middleware('auth:api')->post('/testing/fill', [App\Http\Controllers\Dashboard\TestingsController::class, 'fill']);
Route::middleware('auth:api')->get('/testing/amount', [App\Http\Controllers\Dashboard\TestingsController::class, 'inv_amount']);
Route::middleware('auth:api')->post('/testing/invoice', [App\Http\Controllers\Dashboard\TestingsController::class, 'createInvoice']);

Route::middleware('auth:api')->get('/maintain_logs', [App\Http\Controllers\Dashboard\Maintain_logsController::class, 'list']);

Route::middleware('auth:api')->get('/route-list', [App\Http\Controllers\Dashboard\RoutesController::class, 'list']);
Route::middleware('auth:api')->get('/route-list/locations/{id}', [App\Http\Controllers\Dashboard\RoutesController::class, 'locations']);

Route::middleware('auth:api')->get('/ro-wo', [App\Http\Controllers\Dashboard\RoutesController::class, 'ro_wo']);
Route::middleware('auth:api')->get('/pend-wo', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'pend_wo']);
Route::middleware('auth:api')->get('/work-orders', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'work_orders']);
Route::middleware('auth:api')->post('/update-wo', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'update_wo']);
Route::middleware('auth:api')->get('/service-calls/history', [App\Http\Controllers\Dashboard\Work_ordersController::class, 'service_calls']);

Route::middleware('auth:api')->post('/img-upload', [App\Http\Controllers\Dashboard\PicturesController::class, 'upload']);
Route::middleware('auth:api')->get('/img-upload', [App\Http\Controllers\Dashboard\PicturesController::class, 'list']);
Route::middleware('auth:api')->get('/img-delete/{id}', [App\Http\Controllers\Dashboard\PicturesController::class, 'delete']);