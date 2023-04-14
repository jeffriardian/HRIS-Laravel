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

/*Route::middleware('auth:api')->get('/production', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'production','middleware' => 'auth:api'], function() {
    Route::resource('/machines', 'MachineController');
    Route::get('/machine-list', 'MachineController@loadAll');
    Route::get('/machine-export-excel', 'MachineController@exportExcel');
    Route::get('/machine-export-pdf', 'MachineController@exportPdf');

    Route::resource('/machine-groups', 'MachineGroupController');
    Route::get('/machine-group-list', 'MachineGroupController@loadAll');
    Route::get('/machine-group-export-excel', 'MachineGroupController@exportExcel');
    Route::get('/machine-group-export-pdf', 'MachineGroupController@exportPdf');

    Route::resource('/machine-parts', 'MachinePartController');
    Route::get('/machine-part-list', 'MachinePartController@loadAll');
    Route::get('/machine-part-export-excel', 'MachinePartController@exportExcel');
    Route::get('/machine-part-export-pdf', 'MachinePartController@exportPdf');

    Route::resource('/machine-types', 'MachineTypeController');
    Route::get('/machine-type-list', 'MachineTypeController@loadAll');
    Route::get('/machine-type-export-excel', 'MachineTypeController@exportExcel');
    Route::get('/machine-type-export-pdf', 'MachineTypeController@exportPdf');

    Route::resource('/machine-brands', 'MachineBrandController');
    Route::get('/machine-brand-list', 'MachineBrandController@loadAll');
    Route::get('/machine-brand-export-excel', 'MachineBrandController@exportExcel');
    Route::get('/machine-brand-export-pdf', 'MachineBrandController@exportPdf');
});