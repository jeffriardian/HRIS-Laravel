<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/humanresource', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'human-resource', 'middleware' => 'auth:api'], function () {
    Route::resource('/countries', 'CountryController');
    Route::get('country-list', 'CountryController@loadAll');
    Route::get('/country-export-excel', 'CountryController@exportExcel');
    Route::get('/country-export-pdf', 'CountryController@exportPdf');

    Route::resource('/employees', 'EmployeeController');
    Route::get('employee-list', 'EmployeeController@loadAll');
    Route::get('/employee-export-excel', 'EmployeeController@exportExcel');
    Route::get('/employee-export-pdf', 'EmployeeController@exportPdf');
    Route::post('/migrationEmployee', 'EmployeeController@migrationData');

    Route::resource('/external-employees', 'ExternalEmployeeController');
    Route::get('external-employee-list', 'ExternalEmployeeController@loadAll');

    Route::resource('/departments', 'DepartmentController');
    Route::get('department-list', 'DepartmentController@loadAll');
    Route::get('department-tree/{id}', 'DepartmentController@loadTree');
    Route::get('/department-export-excel', 'DepartmentController@exportExcel');
    Route::get('/department-export-pdf', 'DepartmentController@exportPdf');

    Route::resource('/department-levels', 'DepartmentLevelController');
    Route::get('department-level-list', 'DepartmentLevelController@loadAll');
    Route::get('/department-level-export-excel', 'DepartmentLevelController@exportExcel');
    Route::get('/department-level-export-pdf', 'DepartmentLevelController@exportPdf');

    Route::resource('/religions', 'ReligionController');
    Route::get('religion-list', 'ReligionController@loadAll');
    Route::get('/religion-export-excel', 'ReligionController@exportExcel');
    Route::get('/religion-export-pdf', 'ReligionController@exportPdf');

    Route::resource('/blood-types', 'BloodTypeController');
    Route::get('blood-type-list', 'BloodTypeController@loadAll');
    Route::resource('/payroll-types', 'PayrollTypeController');
    Route::get('payroll-type-list', 'PayrollTypeController@loadAll');
    Route::resource('/marital-statuses', 'MaritalStatusController');
    Route::get('marital-status-list', 'MaritalStatusController@loadAll');
    Route::resource('/contracts', 'ContractController');
    Route::get('contract-list', 'ContractController@loadAll');
    Route::resource('/companies', 'CompanyController');
    Route::get('company-list', 'CompanyController@loadAll');
    Route::resource('/positions', 'PositionController');
    Route::get('position-list', 'PositionController@loadAll');
    Route::get('position-group-list', 'PositionController@loadPositionGroup');
    Route::resource('/employee-statuses', 'EmployeeStatusController');
    Route::get('employee-status-list', 'EmployeeStatusController@loadAll');
    Route::resource('/periods', 'PeriodController');
    Route::get('period-list', 'PeriodController@loadAll');
    Route::resource('/trainings', 'TrainingController');
    Route::get('training-list', 'TrainingController@loadAll');
    Route::resource('/training-types', 'TrainingTypeController');
    Route::get('training-type-list', 'TrainingTypeController@loadAll');
    Route::resource('/inventories', 'InventoryController');
    Route::get('inventory-list', 'InventoryController@loadAll');
    Route::resource('/leave-categories', 'LeaveCategoryController');
    Route::get('leave-category-list', 'LeaveCategoryController@loadAll');
    Route::resource('/leaves', 'LeaveController');
    Route::get('leave-list', 'LeaveController@loadAll');
    Route::resource('/incident-category', 'IncidentCategoryController');
    Route::get('incident-category-list', 'IncidentCategoryController@loadAll');
    Route::resource('/interrogation-reports', 'InterrogationReportController');
    Route::get('interrogation-report-list', 'InterrogationReportController@loadAll');
    Route::resource('/incident-penalties', 'IncidentPenaltyController');
    Route::get('incident-penalty-list', 'IncidentPenaltyController@loadAll');
    Route::get('penalty-interrogation/{id}', 'IncidentPenaltyController@showInterrogation');
    Route::resource('/transportations', 'TransportationController');
    Route::get('transportation-list', 'TransportationController@loadAll');
    Route::resource('/receipt-parameters', 'ReceiptParameterController');
    Route::get('receipt-parameter-list', 'ReceiptParameterController@loadAll');
    Route::resource('/business-trips', 'BusinessTripController');
    Route::get('business-trip-list', 'BusinessTripController@loadAll');
    Route::resource('/overtimes', 'OvertimeController');
    Route::get('overtime-list', 'OvertimeController@loadAll');
    Route::post('overtime-import-excel-to-temporary', 'OvertimeController@storeToTemporary');
    Route::get('overtime-migration-from-temporary', 'OvertimeController@migrationFromTemporary');
    Route::get('overtime-cancel-store-import', 'OvertimeController@cancelStoreImport');
    Route::post('overtime-approval', 'OvertimeController@storeApproval');
    Route::resource('/approve-overtimes', 'ApproveOvertimeController');
    Route::get('approve-overtime-list', 'ApproveOvertimeController@loadAll');
    Route::get('auto-overtimes', 'OvertimeController@getDataAutoOvertime');
    Route::resource('/approve-businesstrips', 'ApproveBusinessTripController');
    Route::get('approve-businesstrip-list', 'ApproveBusinessTripController@loadAll');
    Route::resource('/reimburse-categories', 'ReimburseCategoryController');
    Route::get('reimburse-category-list', 'ReimburseCategoryController@loadAll');
    Route::resource('/reimburses', 'ReimburseController');
    Route::get('reimburse-list', 'ReimburseController@loadAll');
    Route::resource('/inventory-categories', 'InventoryCategoryController');
    Route::get('inventory-category-list', 'InventoryCategoryController@loadAll');
    Route::resource('/inventory-employees', 'InventoryEmployeeController');
    Route::get('inventory-employee-list', 'InventoryEmployeeController@loadAll');
    Route::resource('/inventory-returns', 'InventoryReturnController');
    Route::get('inventory-return-list', 'InventoryReturnController@loadAll');
    Route::resource('/exit-clearances', 'ExitClearanceController');
    Route::get('exit-clearance-list', 'ExitClearanceController@loadAll');
    Route::resource('/exitclearance-parameters', 'ExitClearanceParameterController');
    Route::get('exitclearance-parameter-list', 'ExitClearanceParameterController@loadAll');
    Route::resource('/office-hours', 'OfficeHourController');
    Route::get('office-hour-list', 'OfficeHourController@loadAll');
    Route::resource('/office-hour-specials', 'OfficeHourSpecialController');
    Route::resource('/attlogs', 'AttLogController');
    Route::get('attlog-list', 'AttLogController@loadAll');
    Route::resource('/attendances', 'AttendanceController');
    Route::get('attendance-list', 'AttendanceController@loadAll');
    Route::resource('/cooperative-members', 'CooperativeController');
    Route::get('cooperative-member-list', 'CooperativeController@loadAll');
    Route::resource('/loans', 'LoanController');
    Route::get('loan-list', 'LoanController@loadAll');
    Route::resource('/savings', 'SavingController');
    Route::get('saving-list', 'SavingController@loadAll');
    Route::resource('/loan-transactions', 'LoanTransactionController');
    Route::get('loan-transaction-list', 'LoanTransactionController@loadAll');
    Route::resource('/salaries', 'SalaryController');
    Route::get('salary-list', 'SalaryController@loadAll');
    Route::post('/updateDataEmployeeSalaries', 'SalaryController@updateDataSalaries');
    Route::resource('/insurances', 'InsuranceController');
    Route::get('insurance-list', 'InsuranceController@loadAll');
    Route::resource('/payroll-parameters', 'PayrollParameterController');
    Route::get('payroll-parameter-list', 'PayrollParameterController@loadAll');
    Route::resource('/inventory-receipts', 'InventoryReceiptController');
    Route::get('inventory-receipt-list', 'InventoryReceiptController@loadAll');
    Route::resource('/inventory-damages', 'InventoryDamageController');
    Route::get('inventory-damage-list', 'InventoryDamageController@loadAll');
    Route::resource('/inventory-goods', 'InventoryGoodController');
    Route::get('inventory-good-list', 'InventoryGoodController@loadAll');
    // Route::resource('/exit-clearance-checks', 'ExitClearanceCheckController');
    Route::post('exit-clearance-checks', 'ExitClearanceController@exitClearanceCheck');
    // Route::get('exit-clearance-check-list', 'ExitClearanceCheckController@loadAll');

    Route::resource('/public-holidays', 'PublicHolidayController');
    Route::get('public-holiday-list', 'PublicHolidayController@loadAll');
    Route::post('/apiHoliday', 'PublicHolidayController@apiHoliday');

    Route::resource('/ptkps', 'PtkpController');
    Route::get('ptkp-list', 'PtkpController@loadAll');
    Route::resource('/payrolls', 'PayrollController');
    Route::get('payroll-list', 'PayrollController@loadAll');
    Route::resource('/approve-reimburses', 'ApproveReimburseController');
    Route::get('approve-reimburse-list', 'ApproveReimburseController@loadAll');
    Route::get('approvereimburse/{id}', 'ApproveReimburseController@approve');
    Route::resource('/disbursements', 'DisbursementController');
    Route::get('disbursement-list', 'DisbursementController@loadAll');
    Route::resource('/pphs', 'PphController');
    Route::get('pph-list', 'PphController@loadAll');
    Route::resource('/months', 'MonthController');
    Route::get('month-list', 'MonthController@loadAll');
    Route::resource('/years', 'YearController');
    Route::get('year-list', 'YearController@loadAll');
    Route::resource('/payroll-components', 'PayrollComponentController');
    Route::get('payroll-component-list', 'PayrollComponentController@loadAll');
    Route::resource('/payrollcomponent-types', 'PayrollComponentTypeController');
    Route::get('payrollcomponent-type-list', 'PayrollComponentTypeController@loadAll');
    Route::resource('/history-employee-statuses', 'HistoryEmployeeStatusController');
    Route::get('history-employee-status-list', 'HistoryEmployeeStatusController@loadAll');
    Route::resource('/history-employee-positions', 'HistoryEmployeePositionController');
    Route::get('history-employee-position-list', 'HistoryEmployeePositionController@loadAll');

    Route::resource('/cooperative-member-types', 'CooperativeMemberTypeController');
    Route::get('cooperative-member-type-list', 'CooperativeMemberTypeController@loadAll');

    Route::resource('/candidates', 'CandidateController');
    Route::get('candidate-list', 'CandidateController@loadAll');
    Route::resource('/memorandums', 'MemorandumController');
    Route::get('memorandum-list', 'MemorandumController@loadAll');
    Route::get('memorandum-interrogation/{id}', 'MemorandumController@showInterrogation');
    Route::resource('/memorandum-parameters', 'MemorandumParameterController');
    Route::get('memorandum-parameter-list', 'MemorandumParameterController@loadAll');
    Route::resource('/recruitments', 'RecruitmentController');
    Route::resource('/report-recruitments', 'ReportRecruitmentController');
    // Route::get('recruitment-list', 'RecruitmentController@loadAll');
    Route::get('/showFinal/{id}', 'RecruitmentController@showFinal');
    Route::patch('/update-candidate/{id}', 'RecruitmentController@updateCandidate');
    Route::put('/reject/{id}', 'RecruitmentController@reject');
    Route::put('/accept/{id}', 'RecruitmentController@accept');
    Route::put('/attend/{id}', 'RecruitmentController@attend');
    Route::resource('/cities', 'CityController');
    Route::get('/city-list', 'CityController@loadAll');
    Route::resource('/recruitment-steps', 'RecruitmentStepController');
    Route::get('recruitment-step-list', 'RecruitmentStepController@loadAll');
    Route::post('recruitment-step-parameter-scores', 'RecruitmentStepController@storeScore');
    Route::get('recruitment-step-parameter-scores-list/{id}', 'RecruitmentStepController@showDetail');
    Route::get('recruitment-next-step-list/{id}', 'RecruitmentStepController@loadNextStep');
    Route::resource('/recruitment-step-parameters', 'RecruitmentStepParameterController');
    Route::get('recruitment-step-parameter-list', 'RecruitmentStepParameterController@loadAll');
    Route::resource('/schedule-recruitments', 'ScheduleRecruitmentController');
    Route::get('schedule-recruitment-list', 'ScheduleRecruitmentController@loadAll');
    Route::resource('/scores', 'ScoreController');
    Route::get('score-list', 'ScoreController@loadAll');
    Route::resource('/status-recruitments', 'StatusRecruitmentController');
    Route::get('status-recruitment-list', 'StatusRecruitmentController@loadAll');
    Route::resource('/bpjs-ketenagakerjaan', 'BpjsKetenagakerjaanController');
    Route::get('/bpjs-ketenagakerjaan-export-excel', 'BpjsKetenagakerjaanController@exportExcel');
    Route::get('/bpjs-ketenagakerjaan-export-pdf', 'BpjsKetenagakerjaanController@exportPdf');
    Route::resource('/bpjs-kesehatan', 'BpjsKesehatanController');
    Route::get('/bpjs-kesehatan-export-excel', 'BpjsKesehatanController@exportExcel');
    Route::get('/bpjs-kesehatan-export-pdf', 'BpjsKesehatanController@exportPdf');
    Route::resource('/salary-types', 'SalaryTypeController');
    Route::get('salary-type-list', 'SalaryTypeController@loadAll');

    Route::resource('/activity-logs', 'ActivityLogController');

    Route::get('/kpi-employees', 'KpiController@kpiEmployee');
    Route::get('/kpi-formulas', 'KpiController@kpiFormula');
    Route::get('/kpi-formula-options', 'KpiController@kpiFormulaOption');
    Route::put('/kpi-formulas', 'KpiController@updateKpiFormula');

    Route::resource('/improvement-ideas', 'ImprovementIdeaController');
    Route::get('improvement-idea-list', 'CandidateController@loadAll');

    Route::post('/get-attendance', 'DummyAttendanceController@getAttendance');

    //Approve Interrogaiton
    Route::resource('/approve-intterogations', 'ApproveIntterogationController');
    Route::get('approve-intterogation-list', 'ApproveIntterogationController@loadAll');
    Route::get('memorandum-history', 'ApproveIntterogationController@showMemorandum');
    Route::get('penalty-history', 'ApproveIntterogationController@showPenalty');

    //Interrogation Report Type
    Route::resource('/interrogation-report-types', 'InterrogationReportTypeController');
    Route::get('interrogation-report-type-list', 'InterrogationReportTypeController@loadAll');

    //Report Interrogation
    Route::get('approve-intterogation-list', 'ApproveIntterogationController@loadAll');
    Route::get('memorandum-history', 'ApproveIntterogationController@showMemorandum');

    //Report
    Route::get('reports/interrogation', 'ReportController@interrogation');
    Route::get('reports/interrogation/{id}', 'ReportController@showInterrogation');
    Route::get('reports/interrogation/memorandum/{id}/{status}', 'ReportController@getMemo');

    //Report Incident Penalty
    Route::get('reports/incident-penalty', 'ReportController@incidentPenalty');

    //Report Memorandum
    Route::get('reports/memorandum', 'ReportController@memorandum');
});
