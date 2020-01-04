<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('', 'AuthController@user');
        Route::get('list', 'AuthController@getUsers');
    });
});

Route::group([
    'prefix' => 'house'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('', 'HouseController@getHouses');
        Route::post('', 'HouseController@createUpdateHouse');
    });
});

Route::group([
    'prefix' => 'batch'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('active/{id}', 'BatchController@getActiveByHouseId');
        Route::put('{id}', 'BatchController@editBatch');
        Route::post('start', 'BatchController@startBatch');
        Route::put('end/{id}', 'BatchController@endBatch');
        Route::get('archive/{houseId}', 'BatchController@getBatchesByHouseId');
    });
});

Route::group([
    'prefix' => 'production'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('', 'ProductionController@getProductionReportsOfActiveBatches');
        Route::get('upto/{date}', 'ProductionController@getProductionReportsOfActiveBatchesUptoDate');
        Route::get('{houseId}', 'ProductionController@getProductionReportsByHouseId');
        Route::post('', 'ProductionController@createUpdateProductionReport');
    });
});

Route::group([
    'prefix' => 'graded-eggs'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('{date}', 'GradedEggsController@getGradedEggsByDate');
        Route::get('available/{date}', 'GradedEggsController@getAvailableByDate');
        Route::post('', 'GradedEggsController@createUpdateGradedEggs');
    });
});

Route::group([
    'prefix' => 'prices'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('', 'PricesController@getPrices');
        Route::post('', 'PricesController@updatePrices');
    });
});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('', 'CustomerController@getCustomers');
        Route::post('', 'CustomerController@createUpdateCustomer');
    });
});

Route::group([
    'prefix' => 'invoices'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('/{date}', 'InvoiceController@getInvoicesByDate');
        Route::get('{id}', 'InvoiceController@getInvoicesById');
        Route::get('customer/{id}', 'InvoiceController@getInvoicesByCustomerId');
        Route::post('', 'InvoiceController@createUpdateInvoice');
    });
});


    

    