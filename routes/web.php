<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\SystemUserGroupController;
use App\Http\Controllers\CoreLocationController;
use App\Http\Controllers\CoreCandidateController;
use App\Http\Controllers\CoreTimsesController;
use App\Http\Controllers\CoreSupporterController;
use App\Http\Controllers\CorePeriodController;
use App\Http\Controllers\CorePollingStationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuickCountController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FundingIncomeController;
use App\Http\Controllers\FundingIncomeReportController;
use App\Http\Controllers\FundingExpenditureController;
use App\Http\Controllers\FundingExpenditureReportController;
use App\Http\Controllers\FundingCombineReportController;
use App\Http\Controllers\RecapitulationReportController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('/system-user', [SystemUserController::class, 'index'])->name('system-user');
Route::get('/system-user/add', [SystemUserController::class, 'addSystemUser'])->name('add-system-user');
Route::post('/system-user/process-add-system-user', [SystemUserController::class, 'processAddSystemUser'])->name('process-add-system-user');
Route::get('/system-user/edit/{user_id}', [SystemUserController::class, 'editSystemUser'])->name('edit-system-user');
Route::post('/system-user/process-edit-system-user', [SystemUserController::class, 'processEditSystemUser'])->name('process-edit-system-user');
Route::get('/system-user/delete-system-user/{user_id}', [SystemUserController::class, 'deleteSystemUser'])->name('delete-system-user');


Route::get('/system-user-group', [SystemUserGroupController::class, 'index'])->name('system-user-group');
Route::get('/system-user-group/add', [SystemUserGroupController::class, 'addSystemUserGroup'])->name('add-system-user-group');
Route::post('/system-user-group/process-add-system-user-group', [SystemUserGroupController::class, 'processAddSystemUserGroup'])->name('process-add-system-user-group');
Route::get('/system-user-group/edit/{user_id}', [SystemUserGroupController::class, 'editSystemUserGroup'])->name('edit-system-user-group');
Route::post('/system-user-group/process-edit-system-user-group', [SystemUserGroupController::class, 'processEditSystemUserGroup'])->name('process-edit-system-user-group');
Route::get('/system-user-group/delete-system-user-group/{user_id}', [SystemUserGroupController::class, 'deleteSystemUserGroup'])->name('delete-system-user-group');

//Configuration Data Location
Route::get('/location', [CoreLocationController::class, 'index']);
Route::get('/location/add', [CoreLocationController::class, 'addCoreLocation'])->name('add-location');
Route::post('/location/process-add', [CoreLocationController::class, 'processAddCoreLocation'])->name('process-add-location');
Route::post('/location/elements-add', [CoreLocationController::class, 'addElementsCoreLocation'])->name('add-location-elements');
Route::get('/location/reset-add', [CoreLocationController::class, 'addReset'])->name('add-location-reset');
Route::get('/location/location-province', [CoreLocationController::class, 'addReset'])->name('location-provice');
Route::post('/location/city', [CoreLocationController::class, 'getCoreCity'])->name('location-city');
Route::post('/location/district', [CoreLocationController::class, 'getCoreDistrict'])->name('location-district');
Route::post('/location/village', [CoreLocationController::class, 'getCoreVillage'])->name('location-village');
Route::get('/location/edit/{location_id}', [CoreLocationController::class, 'editCoreLocation'])->name('edit-location');
Route::post('/location/process-edit/', [CoreLocationController::class, 'processEditCoreLocation'])->name('process-edit-location');
Route::get('/location/delete-location/{location_id}', [CoreLocationController::class, 'deleteCoreLocation'])->name('delete-location');

//Configuration Data Candidate
Route::get('/candidate', [CoreCandidateController::class, 'index']);
Route::get('/candidate/add', [CoreCandidateController::class, 'addCoreCandidate'])->name('add-candidate');
Route::post('/candidate/process-add', [CoreCandidateController::class, 'processAddCoreCandidate'])->name('process-add-candidate');
Route::post('/candidate/elements-add', [CoreCandidateController::class, 'addElementsCoreCandidate'])->name('add-candidate-elements');
Route::get('/candidate/reset-add', [CoreCandidateController::class, 'addReset'])->name('add-candidate-reset');
Route::get('/candidate/edit/{candidate_id}', [CoreCandidateController::class, 'editCoreCandidate'])->name('edit-candidate');
Route::post('/candidate/process-edit', [CoreCandidateController::class, 'processEditCoreCandidate'])->name('process-edit-candidate');
Route::get('/candidate/detail/{candidate_id}', [CoreCandidateController::class, 'detailCoreCandidate'])->name('detail-candidate');
Route::get('/candidate/download/{candidate_id}', [CoreCandidateController::class, 'downloadCoreCandidatePhotos'])->name('candidate-photos-download');
Route::get('/candidate/delete-candidate/{candidate_id}', [CoreCandidateController::class, 'deleteCoreCandidate'])->name('delete-candidate');

//Configuration Data Timses
Route::get('/timses', [CoreTimsesController::class, 'index']);
Route::get('/timses/add', [CoreTimsesController::class, 'addCoreTimses'])->name('add-timses');
Route::post('/timses/process-add', [CoreTimsesController::class, 'processAddCoreTimses'])->name('process-add-timses');
Route::post('/timses/elements-add', [CoreTimsesController::class, 'addElementsCoreTimses'])->name('add-timses-elements');
Route::get('/timses/reset-add', [CoreTimsesController::class, 'addReset'])->name('add-timses-reset');
Route::get('/timses/edit/{timses_id}', [CoreTimsesController::class, 'editCoreTimses'])->name('edit-timses');
Route::post('/timses/process-edit', [CoreTimsesController::class, 'processEditCoreTimses'])->name('process-edit-timses');
Route::get('/timses/detail/{timses_id}', [CoreTimsesController::class, 'detailCoreTimses'])->name('detail-timses');
Route::get('/timses/delete-timses/{timses_id}', [CoreTimsesController::class, 'deleteCoreTimses'])->name('delete-timses');

Route::get('/timses/add-member/{timses_id}', [CoreTimsesController::class, 'addMemberCoreTimses'])->name('add-timses-member');
Route::post('/timses/process-add-member', [CoreTimsesController::class, 'processAddMemberCoreTimses'])->name('process-add-timses-member');
Route::get('/timses/add-account-member/{timses_id}/{timses_member_id}', [CoreTimsesController::class, 'addAccountMemberCoreTimses'])->name('add-timses-member-account');
Route::post('/timses/process-add-account-member', [CoreTimsesController::class, 'processAddAccountMemberCoreTimses'])->name('process-add-timses-member-account');
Route::get('/timses/delete-timses-member/{timses_id}/{timses_member_id}', [CoreTimsesController::class, 'deleteMemberCoreTimses'])->name('delete-timses-member');

//Configuration Data Supporter
Route::get('/supporter', [CoreSupporterController::class, 'index']);
Route::get('/supporter/add', [CoreSupporterController::class, 'addCoreSupporter'])->name('add-supporter');
Route::post('/supporter/process-add', [CoreSupporterController::class, 'processAddCoreSupporter'])->name('process-add-supporter');
Route::post('/supporter/elements-add', [CoreSupporterController::class, 'addElementsCoreSupporter'])->name('add-supporter-elements');
Route::get('/supporter/reset-add', [CoreSupporterController::class, 'addReset'])->name('add-supporter-reset');
Route::get('/supporter/edit/{supporter_id}', [CoreSupporterController::class, 'editCoreSupporter'])->name('edit-supporter');
Route::post('/supporter/process-edit', [CoreSupporterController::class, 'processEditCoreSupporter'])->name('process-edit-supporter');
Route::get('/supporter/delete-supporter/{supporter_id}', [CoreSupporterController::class, 'deleteCoreSupporter'])->name('delete-supporter');

//Configuration Data Period
Route::get('/period', [CorePeriodController::class, 'index']);
Route::get('/period/add', [CorePeriodController::class, 'addCorePeriod'])->name('add-period');
Route::post('/period/process-add', [CorePeriodController::class, 'processAddCorePeriod'])->name('process-add-period');
Route::post('/period/elements-add', [CorePeriodController::class, 'addElementsCorePeriod'])->name('add-period-elements');
Route::get('/period/reset-add', [CorePeriodController::class, 'addReset'])->name('add-period-reset');
Route::get('/period/edit/{period_id}', [CorePeriodController::class, 'editCorePeriod'])->name('edit-period');
Route::post('/period/process-edit', [CorePeriodController::class, 'processEditCorePeriod'])->name('process-edit-period');
Route::get('/period/delete-period/{period_id}', [CorePeriodController::class, 'deleteCorePeriod'])->name('delete-period');

//Configuration Data Polling Station
Route::get('/polling-station', [CorePollingStationController::class, 'index']);
Route::get('/polling-station/add', [CorePollingStationController::class, 'addCorePollingStation'])->name('add-polling-station');
Route::post('/polling-station/process-add', [CorePollingStationController::class, 'processAddCorePollingStation'])->name('process-add-polling-station');
Route::post('/polling-station/elements-add', [CorePollingStationController::class, 'addElementsCorePollingStation'])->name('add-polling-station-elements');
Route::get('/polling-station/reset-add', [CorePollingStationController::class, 'addReset'])->name('add-polling-station-reset');
Route::get('/polling-station/edit/{polling_station_id}', [CorePollingStationController::class, 'editCorePollingStation'])->name('edit-polling-station');
Route::post('/polling-station/process-edit', [CorePollingStationController::class, 'processEditCorePollingStation'])->name('process-edit-polling-station');
Route::get('/polling-station/delete-polling-station/{polling_station_id}', [CorePollingStationController::class, 'deleteCorePollingStation'])->name('delete-polling-station');

//Program
Route::get('/program', [ProgramController::class, 'index']);
Route::get('/program/add', [ProgramController::class, 'addProgram'])->name('add-program');
Route::post('/program/process-add', [ProgramController::class, 'processAddProgram'])->name('process-add-program');
Route::post('/program/elements-add', [ProgramController::class, 'addElementsProgram'])->name('add-program-elements');
Route::get('/program/reset-add', [ProgramController::class, 'addReset'])->name('add-program-reset');
Route::get('/program/edit/{program_id}', [ProgramController::class, 'editProgram'])->name('edit-program');
Route::post('/program/process-edit', [ProgramController::class, 'processEditProgram'])->name('process-edit-program');
Route::get('/program/detail/{program_id}', [ProgramController::class, 'detailProgram'])->name('detail-program');

Route::get('/program/distribution-fund/{program_id}/{timses_id}', [ProgramController::class, 'distributionFundProgram'])->name('distribution-fund');
Route::post('/program/process-distribution-fund', [ProgramController::class, 'processDistributionFundProgram'])->name('process-distribution-fund');
Route::get('/program/edit-distribution-fund/{program_id}/{timses_id}/{distribution_fund_id}', [ProgramController::class, 'editDistributionFundProgram'])->name('edit-distribution-fund');
Route::post('/program/process-edit-distribution-fund', [ProgramController::class, 'processEditDistributionFundProgram'])->name('process-edit-distribution-fund');
Route::get('/program/get-user-akun/{timses_member_id}', [ProgramController::class, 'getUserAkun'])->name('get-user-akun');

Route::get('/program/add-program-support/{program_id}', [ProgramController::class, 'addProgramSupport'])->name('add-program-support');
Route::post('/program/process-add-program-support', [ProgramController::class, 'processAddProgramSupport'])->name('process-add-program-support');
Route::get('/program/add-supporter-new/{program_support_id}', [ProgramController::class, 'addCoreSupporterNew'])->name('add-supporter-new');
Route::post('/program/process-add-supporter-new', [ProgramController::class, 'processAddCoreSupporterNew'])->name('process-add-supporter-new');
Route::get('/program/delete-program-support/{program_id}/{program_support_id}', [ProgramController::class, 'deleteProgramSupport'])->name('delete-program-support');

Route::get('/program/documentation-program/{program_id}', [ProgramController::class, 'documentationProgram'])->name('documentation-program');
Route::post('/program/process-documentation-program', [ProgramController::class, 'processDocumentationProgram'])->name('process-documentation-program');
Route::get('/program/download-documentation/{program_documentation_id}', [ProgramController::class, 'downloadDocumentationProgram'])->name('download-documentation');
Route::get('/program/delete-documentation/{program_documentation_id}', [ProgramController::class, 'deleteDocumentationProgram'])->name('delete-documentation');
Route::get('/program/closing-program/{program_id}', [ProgramController::class, 'closingProgram'])->name('closing-program');
Route::get('/program/delete-program/{program_id}', [ProgramController::class, 'deleteProgram'])->name('delete-program');

//Quick Count
Route::get('/quick-count', [QuickCountController::class, 'index']);
Route::get('/quick-count/add', [QuickCountController::class, 'addQuickCount'])->name('add-quick-count');
Route::post('/quick-count/process-add', [QuickCountController::class, 'processAddQuickCount'])->name('process-add-quick-count');
Route::post('/quick-count/elements-add', [QuickCountController::class, 'addElementsQuickCount'])->name('add-quick-count-elements');
Route::get('/quick-count/reset-add', [QuickCountController::class, 'addReset'])->name('add-quick-count-reset');
Route::get('/quick-count/edit/{quick_count_id}', [QuickCountController::class, 'editQuickCount'])->name('edit-quick-count');
Route::post('/quick-count/process-edit', [QuickCountController::class, 'processEditQuickCount'])->name('process-edit-quick-count');
Route::get('/quick-count/starting-quick-count/{quick_count_id}/{period_id}', [QuickCountController::class, 'startingQuickCount'])->name('starting-quick-count');
Route::get('/quick-count/subtraction-starting-quick-count/{candidate_id}', [QuickCountController::class, 'subtractionStartingQuickCount'])->name('subtraction-starting-quick-count');
Route::get('/quick-count/sum-starting-quick-count/{candidate_id}', [QuickCountController::class, 'sumStartingQuickCount'])->name('sum-starting-quick-count');
Route::get('/quick-count/process-starting-quick-count/', [QuickCountController::class, 'processStartingQuickCount'])->name('process-starting-quick-count');
Route::get('/quick-count/closing-quick-count/{quick_count_id}', [QuickCountController::class, 'closingQuickCount'])->name('closing-quick-count');
Route::get('/quick-count/delete-quick-count/{quick_count_id}', [QuickCountController::class, 'deleteQuickCount'])->name('delete-quick-count');

//Financial Category
Route::get('/financial-category', [FinancialCategoryController::class, 'index']);
Route::get('/financial-category/add', [FinancialCategoryController::class, 'addFinancialCategory'])->name('add-financial-category');
Route::post('/financial-category/process-add', [FinancialCategoryController::class, 'processAddFinancialCategory'])->name('process-add-financial-category');
Route::post('/financial-category/elements-add', [FinancialCategoryController::class, 'addElementsFinancialCategory'])->name('add-financial-category-elements');
Route::get('/financial-category/reset-add', [FinancialCategoryController::class, 'addReset'])->name('add-financial-category-reset');
Route::get('/financial-category/edit/{financial_category_id}', [FinancialCategoryController::class, 'editFinancialCategory'])->name('edit-financial-category');
Route::post('/financial-category/process-edit', [FinancialCategoryController::class, 'processEditFinancialCategory'])->name('process-edit-period');
Route::get('/financial-category/delete-financial-category/{financial_category_id}', [FinancialCategoryController::class, 'deleteFinancialCategory'])->name('delete-financial-category');

//Funding Income -> Financial Flow
Route::post('/funding-income/elements-add', [FundingIncomeController::class, 'addElementsFundingIncome'])->name('add-funding-income-elements');
Route::get('/funding-income/reset-add', [FundingIncomeController::class, 'addReset'])->name('add-funding-income-reset');

Route::get('/funding-income-timses', [FundingIncomeController::class, 'indexTimses']);
Route::get('/funding-income-timses/add', [FundingIncomeController::class, 'addFundingIncomeTimses'])->name('add-funding-income');
Route::post('/funding-income-timses/process-add', [FundingIncomeController::class, 'processAddFundingIncomeTimses'])->name('process-add-funding-income-timses');
Route::get('/funding-income-timses/edit/{financial_flow_id}', [FundingIncomeController::class, 'editFundingIncomeTimses'])->name('edit-funding-income-timses');
Route::post('/funding-income-timses/process-edit', [FundingIncomeController::class, 'processEditFundingIncomeTimses'])->name('process-edit-funding-income-timses');
Route::get('/funding-income-timses/delete-funding-income/{financial_flow_id}', [FundingIncomeController::class, 'deleteFundingIncomeTimses'])->name('delete-funding-income-timses');

Route::get('/funding-income-candidate', [FundingIncomeController::class, 'indexCandidate']);
Route::get('/funding-income-candidate/add', [FundingIncomeController::class, 'addFundingIncomeCandidate'])->name('add-funding-income-candaidate');
Route::post('/funding-income-candidate/process-add', [FundingIncomeController::class, 'processAddFundingIncomeCandidate'])->name('process-add-funding-income-candidate');
Route::get('/funding-income-candidate/edit/{financial_flow_id}', [FundingIncomeController::class, 'editFundingIncomeCandidate'])->name('edit-funding-income');
Route::post('/funding-income-candidate/process-edit', [FundingIncomeController::class, 'processEditFundingIncomeCandidate'])->name('process-edit-funding-income-candidate');
Route::get('/funding-income-candidate/delete-funding-income/{financial_flow_id}', [FundingIncomeController::class, 'deleteFundingIncomeCandidate'])->name('delete-funding-income-candidate');


//Funding Expenditure -> Financial Flow
Route::post('/funding-expenditure/elements-add', [FundingExpenditureController::class, 'addElementsFundingExpenditure'])->name('add-funding-expenditure-elements');
Route::get('/funding-expenditure/reset-add', [FundingExpenditureController::class, 'addReset'])->name('add-funding-expenditure-reset');

Route::get('/funding-expenditure-candidate', [FundingExpenditureController::class, 'indexCandidate']);
Route::get('/funding-expenditure-candidate/add', [FundingExpenditureController::class, 'addFundingExpenditureCandidate'])->name('add-funding-expenditure-candidate');
Route::post('/funding-expenditure-candidate/process-add', [FundingExpenditureController::class, 'processAddFundingExpenditureCandidate'])->name('process-add-funding-expenditure-candidate');
Route::get('/funding-expenditure-candidate/edit/{financial_flow_id}', [FundingExpenditureController::class, 'editFundingExpenditureCandidate'])->name('edit-funding-expenditure-candidate');
Route::post('/funding-expenditure-candidate/process-edit', [FundingExpenditureController::class, 'processEditFundingExpenditureCandidate'])->name('process-edit-funding-expenditure-candidate');
Route::get('/funding-expenditure-candidate/delete-funding-expenditure/{financial_flow_id}', [FundingExpenditureController::class, 'deleteFundingExpenditureCandidate'])->name('delete-funding-expenditure-candidate');

Route::get('/funding-expenditure-timses', [FundingExpenditureController::class, 'indexTimses']);
Route::get('/funding-expenditure-timses/add', [FundingExpenditureController::class, 'addFundingExpenditureTimses'])->name('add-funding-expenditure-timses');
Route::post('/funding-expenditure-timses/process-add', [FundingExpenditureController::class, 'processAddFundingExpenditureTimses'])->name('process-add-funding-expenditure-timses');
Route::get('/funding-expenditure-timses/edit/{financial_flow_id}', [FundingExpenditureController::class, 'editFundingExpenditureTimses'])->name('edit-funding-expenditure-timses');
Route::post('/funding-expenditure-timses/process-edit', [FundingExpenditureController::class, 'processEditFundingExpenditureTimses'])->name('process-edit-funding-expenditure-timses');

Route::get('/funding-expenditure-timses/delete-funding-expenditure/{financial_flow_id}', [FundingExpenditureController::class, 'deleteFundingExpenditureTimses'])->name('delete-funding-expenditure-timses');

//Report Funding Income
Route::get('/report-income', [FundingIncomeReportController::class, 'index']);
Route::post('/report-income/filter',[FundingIncomeReportController::class, 'filterFundingIncomeReport'])->name('filter-report-income');
Route::get('/report-income/filter-reset',[FundingIncomeReportController::class, 'filterResetFundingIncomeReport'])->name('filter-reset-report-income');
Route::get('/report-income/print',[FundingIncomeReportController::class, 'printFundingIncomeReport'])->name('print-funding-income-report');
Route::get('/report-income/export',[FundingIncomeReportController::class, 'exportFundingIncomeReport'])->name('export-funding-income-report');

//Report Funding Expenditure
Route::get('/report-expenditure', [FundingExpenditureReportController::class, 'index']);
Route::post('/report-expenditure/filter',[FundingExpenditureReportController::class, 'filterFundingExpenditureReport'])->name('filter-report-expenditure');
Route::get('/report-expenditure/filter-reset',[FundingExpenditureReportController::class, 'filterResetFundingExpenditureReport'])->name('filter-reset-report-expenditure');
Route::get('/report-expenditure/print',[FundingExpenditureReportController::class, 'printFundingExpenditureReport'])->name('print-funding-expenditure-report');
Route::get('/report-expenditure/export',[FundingExpenditureReportController::class, 'exportFundingExpenditureReport'])->name('export-funding-expenditure-report');

//Report Funding Combine
Route::get('/report-combine', [FundingCombineReportController::class, 'index']);
Route::post('/report-combine/filter',[FundingCombineReportController::class, 'filterFundingCombineReport'])->name('filter-report-combine');
Route::get('/report-combine/filter-reset',[FundingCombineReportController::class, 'filterResetFundingCombineReport'])->name('filter-reset-report-combine');
Route::get('/report-combine/print',[FundingCombineReportController::class, 'printFundingCombineReport'])->name('print-funding-combine-report');
Route::get('/report-combine/export',[FundingCombineReportController::class, 'exportFundingCombineReport'])->name('export-funding-combine-report');

//Recapitulation Report
Route::get('/report-recap',[RecapitulationReportController::class, 'index'])->name('ledger-report');
Route::post('/report-recap/filter',[RecapitulationReportController::class, 'filterLedgerReport'])->name('filter-report-recap');
Route::get('/report-recap/reset-filter',[RecapitulationReportController::class, 'resetFilterLedgerReport'])->name('reset-filter-report-recap');
Route::get('/report-recap/print',[RecapitulationReportController::class, 'printLedgerReport'])->name('print-report-recap');
Route::get('/report-recap/export',[RecapitulationReportController::class, 'exportLedgerReport'])->name('export-report-recap');