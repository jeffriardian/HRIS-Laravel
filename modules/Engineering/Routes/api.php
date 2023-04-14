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

/*Route::middleware('auth:api')->get('/engineering', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'engineering','middleware' => 'auth:api'], function() {
    Route::resource('/service-types', 'ServiceTypeController');
    Route::get('/service-type-list', 'ServiceTypeController@loadAll');
    Route::get('/service-type-export-excel', 'ServiceTypeController@exportExcel');
    Route::get('/service-type-export-pdf', 'ServiceTypeController@exportPdf');

    Route::resource('/failure-types', 'FailureTypeController');
    Route::get('/failure-type-list', 'FailureTypeController@loadAll');
    Route::get('/failure-type-export-excel', 'FailureTypeController@exportExcel');
    Route::get('/failure-type-export-pdf', 'FailureTypeController@exportPdf');

    Route::resource('/work-order-actions', 'WorkOrderActionController');
    Route::get('/work-order-action-list', 'WorkOrderActionController@loadAll');
    Route::get('/work-order-action-export-excel', 'WorkOrderActionController@exportExcel');
    Route::get('/work-order-action-export-pdf', 'WorkOrderActionController@exportPdf');

    Route::resource('/annual-plannings', 'AnnualPlanningController');
    Route::get('/annual-planning-list', 'AnnualPlanningController@loadAll');
    Route::get('/annual-planning-annual/{id}', 'AnnualPlanningController@annual');

    Route::resource('/monthly-plannings', 'MonthlyPlanningController');
    Route::get('/monthly-planning-list', 'MonthlyPlanningController@loadAll');

    Route::resource('/weekly-plannings', 'WeeklyPlanningController');
    Route::get('/weekly-planning-list', 'WeeklyPlanningController@loadAll');
    Route::get('/weekly-planning-template', 'WeeklyPlanningController@exportTemplate');

    Route::resource('/daily-plannings', 'DailyPlanningController');
    Route::get('/daily-planning-list', 'DailyPlanningController@loadAll');

    Route::resource('/work-orders', 'WorkOrderController');
    Route::get('/work-order-list', 'WorkOrderController@loadAll');
    Route::get('/work-order-export-excel', 'WorkOrderController@exportExcel');
    Route::get('/work-order-export-pdf', 'WorkOrderController@exportPdf');

    Route::resource('/job-cards', 'JobCardController');
    Route::get('/job-card-list', 'JobCardController@loadAll');
    Route::get('/job-card-export-excel', 'JobCardController@exportExcel');
    Route::get('/job-card-export-pdf', 'JobCardController@exportPdf');

    Route::resource('/backlogs', 'BacklogController');
    Route::get('/backlog-list', 'BacklogController@loadAll');
    Route::get('/backlog-export-excel', 'BacklogController@exportExcel');
    Route::get('/backlog-export-pdf', 'BacklogController@exportPdf');

    Route::resource('/machines', 'MachineController');
    Route::get('/machine-list', 'MachineController@loadAll');
});
