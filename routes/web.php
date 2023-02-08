<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\SystemUserGroupController;
use App\Http\Controllers\CoreDapilController;
use App\Http\Controllers\CoreCandidateController;
use App\Http\Controllers\CoreTimsesController;
use App\Http\Controllers\CoreTimsesMemberController;
use App\Http\Controllers\CoreSupporterController;
use App\Http\Controllers\CorePeriodController;
use App\Http\Controllers\CorePollingStationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramTimsesController;
use App\Http\Controllers\QuickCountController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FundingIncomeController;
use App\Http\Controllers\FundingIncomeReportController;
use App\Http\Controllers\FundingIncomeReport2Controller;
use App\Http\Controllers\FundingExpenditureController;
use App\Http\Controllers\FundingExpenditureReport2Controller;
use App\Http\Controllers\FundingExpenditureReportController;
use App\Http\Controllers\FundingCombineReportController;
use App\Http\Controllers\RecapitulationReportController;
use App\Http\Controllers\FundingAcctReportController;
use App\Http\Controllers\FundingTimsesController;
use App\Http\Controllers\TimsesActivityReportController;
use App\Http\Controllers\TimsesActivityReport2Controller;

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

//System User
Route::get('/system-user', [SystemUserController::class, 'index'])->name('system-user');
Route::get('/system-user/add', [SystemUserController::class, 'addSystemUser'])->name('add-system-user');
Route::post('/system-user/process-add-system-user', [SystemUserController::class, 'processAddSystemUser'])->name('process-add-system-user');
Route::get('/system-user/edit/{user_id}', [SystemUserController::class, 'editSystemUser'])->name('edit-system-user');
Route::post('/system-user/process-edit-system-user', [SystemUserController::class, 'processEditSystemUser'])->name('process-edit-system-user');
Route::get('/system-user/delete-system-user/{user_id}', [SystemUserController::class, 'deleteSystemUser'])->name('delete-system-user');

//System User Gruop
Route::get('/system-user-group', [SystemUserGroupController::class, 'index'])->name('system-user-group');
Route::get('/system-user-group/add', [SystemUserGroupController::class, 'addSystemUserGroup'])->name('add-system-user-group');
Route::post('/system-user-group/process-add-system-user-group', [SystemUserGroupController::class, 'processAddSystemUserGroup'])->name('process-add-system-user-group');
Route::get('/system-user-group/edit/{user_id}', [SystemUserGroupController::class, 'editSystemUserGroup'])->name('edit-system-user-group');
Route::post('/system-user-group/process-edit-system-user-group', [SystemUserGroupController::class, 'processEditSystemUserGroup'])->name('process-edit-system-user-group');
Route::get('/system-user-group/delete-system-user-group/{user_id}', [SystemUserGroupController::class, 'deleteSystemUserGroup'])->name('delete-system-user-group');

//Data Dapil
Route::get('/dapil', [CoreDapilController::class, 'index']);
Route::get('/dapil/add', [CoreDapilController::class, 'addCoreDapil'])->name('add-dapil');
Route::post('/dapil/process-add', [CoreDapilController::class, 'processAddCoreDapil'])->name('process-add-dapil');
Route::post('/dapil/elements-add', [CoreDapilController::class, 'addElementsCoreDapil'])->name('add-dapil-elements');
Route::get('/dapil/reset-add', [CoreDapilController::class, 'addReset'])->name('add-dapil-reset');
Route::get('/dapil/edit/{dapil_id}', [CoreDapilController::class, 'editCoreDapil'])->name('edit-dapil');
Route::post('/dapil/process-edit/', [CoreDapilController::class, 'processEditCoreDapil'])->name('process-edit-dapil');
Route::get('/dapil/detail/{dapil_id}', [CoreDapilController::class, 'detailCoreDapil'])->name('detail-dapil');
Route::get('/dapil/delete-dapil/{dapil_id}', [CoreDapilController::class, 'deleteCoreDapil'])->name('delete-dapil');

//Dapil Item
Route::get('/dapil/add-dapil-item/{dapil_id}', [CoreDapilController::class, 'addCoreDapilItem'])->name('add-dapil-item');
Route::post('/dapil/process-add-dapil-item', [CoreDapilController::class, 'processAddCoreDapilItem'])->name('process-add-dapil-item');
Route::get('/dapil/dapil-province', [CoreDapilController::class, 'addReset'])->name('dapil-provice');
Route::post('/dapil/city', [CoreDapilController::class, 'getCoreCity'])->name('dapil-city');
Route::post('/dapil/district', [CoreDapilController::class, 'getCoreDistrict'])->name('dapil-district');
Route::post('/dapil/village', [CoreDapilController::class, 'getCoreVillage'])->name('dapil-village');
Route::get('/dapil/delete-dapil-item/{dapil_item_id}', [CoreDapilController::class, 'deleteCoreDapilItem'])->name('delete-dapil-item');


//Data Candidate
Route::get('/candidate', [CoreCandidateController::class, 'index']);
// Route::post('/candidate/elements-add', [CoreCandidateController::class, 'addElementsCoreCandidate'])->name('add-candidate-elements');
// Route::get('/candidate/reset-add', [CoreCandidateController::class, 'addReset'])->name('add-candidate-reset');
Route::get('/candidate/edit/{candidate_id}', [CoreCandidateController::class, 'editCoreCandidate'])->name('edit-candidate');
Route::post('/candidate/process-edit', [CoreCandidateController::class, 'processEditCoreCandidate'])->name('process-edit-candidate');

Route::get('/candidate/edit-partai/{candidate_id}', [CoreCandidateController::class, 'editCoreCandidatePartai'])->name('edit-candidate-partai');
Route::post('/candidate/process-edit-paratai', [CoreCandidateController::class, 'processEditCoreCandidatePartai'])->name('process-edit-candidate-partai');

Route::get('/candidate/download/{candidate_id}', [CoreCandidateController::class, 'downloadCoreCandidatePhotos'])->name('candidate-photos-download');
Route::get('/candidate/delete-candidate/{candidate_id}', [CoreCandidateController::class, 'deleteCoreCandidate'])->name('delete-candidate');

//Core Timses
Route::get('/timses', [CoreTimsesController::class, 'index']);
Route::get('/timses/add', [CoreTimsesController::class, 'addCoreTimses'])->name('add-timses');
Route::post('/timses/process-add', [CoreTimsesController::class, 'processAddCoreTimses'])->name('process-add-timses');
Route::get('/timses/edit/{timses_id}', [CoreTimsesController::class, 'editCoreTimses'])->name('edit-timses');
Route::post('/timses/process-edit', [CoreTimsesController::class, 'processEdiCoreTimses'])->name('process-edit-timses');
Route::get('/timses/detail/{timses_id}', [CoreTimsesController::class, 'detailCoreTimses'])->name('detail-timses');
Route::get('/timses/add-account/{timses_id}', [CoreTimsesController::class, 'addAccountCoreTimses'])->name('add-timses-account');
Route::post('/timses/process-add-account', [CoreTimsesController::class, 'processAddAccountCoreTimses'])->name('process-add-timses-account');
Route::get('/timses/delete-timses/{timses_id}', [CoreTimsesController::class, 'deleteCoreTimses'])->name('delete-timses');

//Core Timses Member in menu Admin 
Route::get('/timses/add-member/{timses_id}', [CoreTimsesController::class, 'addCoreTimsesMember'])->name('add-timses-member');
Route::post('/timses/process-add-member', [CoreTimsesController::class, 'processAddCoreTimsesMember'])->name('process-add-timses-member');

Route::get('/timses/add-ktp-member/{timses_id}/{timses_member_id}', [CoreTimsesController::class, 'addCoreTimsesMemberKTP'])->name('add-ktp-member');
Route::post('/timses/process-add-ktp-member', [CoreTimsesController::class, 'processAddCoreTimsesMemberKTP'])->name('process-add-ktp-member');
Route::get('/timses/download-ktp-member/{timses_member_ktp_id}', [CoreTimsesController::class, 'downloadCoreTimsesMemberKTP'])->name('download-ktp-member');
Route::get('/timses/delete-ktp-member/{timses_member_ktp_id}', [CoreTimsesController::class, 'deleteCoreTimsesMemberKTP'])->name('delete-ktp-member');

Route::get('/timses/edit-member/{timses_id}/{timses_member_id}', [CoreTimsesController::class, 'editCoreTimsesMember'])->name('edit-timses-member');
Route::post('/timses/process-edit-member', [CoreTimsesController::class, 'processEdiCoreTimsesMember'])->name('process-edit-timses-member');
// Route::get('/timses/add-account-member/{timses_id}/{timses_member_id}', [CoreTimsesController::class, 'addAccountMemberCoreTimses'])->name('add-timses-member-account');
// Route::post('/timses/process-add-account-member', [CoreTimsesController::class, 'processAddAccountMemberCoreTimses'])->name('process-add-timses-member-account');
Route::get('/timses/delete-timses-member/{timses_member_id}', [CoreTimsesController::class, 'deleteCoreTimsesMember'])->name('delete-timses-member');

//Core Timses Member in menu Timses 
Route::get('/timses-member', [CoreTimsesMemberController::class, 'addCoreTimsesMember'])->name('add-timses-member2');
Route::post('/timses-member/process-add-member', [CoreTimsesMemberController::class, 'processAddCoreTimsesMember'])->name('process-add-timses-member2');

Route::get('/timses-member/edit-member/{timses_member_id}', [CoreTimsesMemberController::class, 'editCoreTimsesMember'])->name('edit-timses-member2');
Route::post('/timses-member/process-edit-member', [CoreTimsesMemberController::class, 'processEdiCoreTimsesMember'])->name('process-edit-timses-member2');

Route::get('/timses-member/add-ktp-member/{timses_member_id}', [CoreTimsesMemberController::class, 'addCoreTimsesMemberKTP'])->name('add-ktp-member2');
Route::post('/timses-member/process-add-ktp-member', [CoreTimsesMemberController::class, 'processAddCoreTimsesMemberKTP'])->name('process-add-ktp-member2');
Route::get('/timses-member/download-ktp-member/{timses_member_id}', [CoreTimsesMemberController::class, 'downloadCoreTimsesMemberKTP'])->name('download-ktp-member2');
Route::get('/timses-member/delete-ktp-member/{timses_member_id}', [CoreTimsesMemberController::class, 'deleteCoreTimsesMemberKTP'])->name('delete-ktp-member2');

Route::get('/timses-member/delete-timses-member/{timses_member_id}', [CoreTimsesMemberController::class, 'deleteCoreTimsesMember'])->name('delete-timses-member2');

//Data Supporter
Route::get('/supporter', [CoreSupporterController::class, 'index']);
Route::get('/supporter/add', [CoreSupporterController::class, 'addCoreSupporter'])->name('add-supporter');
Route::post('/supporter/process-add', [CoreSupporterController::class, 'processAddCoreSupporter'])->name('process-add-supporter');
Route::post('/supporter/elements-add', [CoreSupporterController::class, 'addElementsCoreSupporter'])->name('add-supporter-elements');
Route::get('/supporter/reset-add', [CoreSupporterController::class, 'addReset'])->name('add-supporter-reset');
Route::get('/supporter/edit/{supporter_id}', [CoreSupporterController::class, 'editCoreSupporter'])->name('edit-supporter');
Route::post('/supporter/process-edit', [CoreSupporterController::class, 'processEditCoreSupporter'])->name('process-edit-supporter');
Route::get('/supporter/delete-supporter/{supporter_id}', [CoreSupporterController::class, 'deleteCoreSupporter'])->name('delete-supporter');

//Data Period
Route::get('/period', [CorePeriodController::class, 'index']);
Route::get('/period/add', [CorePeriodController::class, 'addCorePeriod'])->name('add-period');
Route::post('/period/process-add', [CorePeriodController::class, 'processAddCorePeriod'])->name('process-add-period');
Route::post('/period/elements-add', [CorePeriodController::class, 'addElementsCorePeriod'])->name('add-period-elements');
Route::get('/period/reset-add', [CorePeriodController::class, 'addReset'])->name('add-period-reset');
Route::get('/period/edit/{period_id}', [CorePeriodController::class, 'editCorePeriod'])->name('edit-period');
Route::post('/period/process-edit', [CorePeriodController::class, 'processEditCorePeriod'])->name('process-edit-period');
Route::get('/period/delete-period/{period_id}', [CorePeriodController::class, 'deleteCorePeriod'])->name('delete-period');

//Data Polling Station
Route::get('/polling-station', [CorePollingStationController::class, 'index']);
Route::get('/polling-station/add', [CorePollingStationController::class, 'addCorePollingStation'])->name('add-polling-station');
Route::post('/polling-station/process-add', [CorePollingStationController::class, 'processAddCorePollingStation'])->name('process-add-polling-station');
Route::post('/polling-station/elements-add', [CorePollingStationController::class, 'addElementsCorePollingStation'])->name('add-polling-station-elements');
Route::get('/polling-station/reset-add', [CorePollingStationController::class, 'addReset'])->name('add-polling-station-reset');
Route::get('/polling-station/edit/{polling_station_id}', [CorePollingStationController::class, 'editCorePollingStation'])->name('edit-polling-station');
Route::post('/polling-station/process-edit', [CorePollingStationController::class, 'processEditCorePollingStation'])->name('process-edit-polling-station');
Route::get('/polling-station/delete-polling-station/{polling_station_id}', [CorePollingStationController::class, 'deleteCorePollingStation'])->name('delete-polling-station');

//Program in menu admin
Route::get('/program', [ProgramController::class, 'index']);

Route::post('/program/filter',[ProgramController::class, 'filterProgram'])->name('filter-program');
Route::get('/program/filter-reset',[ProgramController::class, 'filterResetProgram'])->name('filter-reset-program');

Route::get('/program/add', [ProgramController::class, 'addProgram'])->name('add-program');
Route::post('/program/process-add', [ProgramController::class, 'processAddProgram'])->name('process-add-program');
Route::post('/program/elements-add', [ProgramController::class, 'addElementsProgram'])->name('add-program-elements');
Route::get('/program/reset-add', [ProgramController::class, 'addReset'])->name('add-program-reset');
Route::get('/program/edit/{program_id}', [ProgramController::class, 'editProgram'])->name('edit-program');
Route::post('/program/process-edit', [ProgramController::class, 'processEditProgram'])->name('process-edit-program');
Route::get('/program/detail/{program_id}', [ProgramController::class, 'detailProgram'])->name('detail-program');

Route::get('/program/documentation-program/{program_id}', [ProgramController::class, 'documentationProgram'])->name('documentation-program');
Route::post('/program/process-documentation-program', [ProgramController::class, 'processDocumentationProgram'])->name('process-documentation-program');
Route::get('/program/download-documentation/{program_documentation_id}', [ProgramController::class, 'downloadDocumentationProgram'])->name('download-documentation');
Route::get('/program/delete-documentation/{program_documentation_id}', [ProgramController::class, 'deleteDocumentationProgram'])->name('delete-documentation');
Route::get('/program/delete-program/{program_id}', [ProgramController::class, 'deleteProgram'])->name('delete-program');

//Program in menu timses
Route::get('/program-timses', [ProgramTimsesController::class, 'index'])->name('program-timses');

Route::post('/program-timses/filter',[ProgramTimsesController::class, 'filterProgram'])->name('filter-program2');
Route::get('/program-timses/filter-reset',[ProgramTimsesController::class, 'filterResetProgram'])->name('filter-reset-program2');

Route::get('/program-timses/add', [ProgramTimsesController::class, 'addProgram'])->name('add-program2');
Route::post('/program-timses/process-add', [ProgramTimsesController::class, 'processAddProgram'])->name('process-add-program2');
Route::post('/program-timses/elements-add', [ProgramTimsesController::class, 'addElementsProgram'])->name('add-program-elements2');
Route::get('/program-timses/reset-add', [ProgramTimsesController::class, 'addReset'])->name('add-program-reset2');
Route::get('/program-timses/edit/{program_id}', [ProgramTimsesController::class, 'editProgram'])->name('edit-program2');
Route::post('/program-timses/process-edit', [ProgramTimsesController::class, 'processEditProgram'])->name('process-edit-program2');
Route::get('/program-timses/detail/{program_id}', [ProgramTimsesController::class, 'detailProgram'])->name('detail-program2');
Route::get('/program-timses/documentation-program/{program_id}', [ProgramTimsesController::class, 'documentationProgram'])->name('documentation-program2');
Route::post('/program-timses/process-documentation-program', [ProgramTimsesController::class, 'processDocumentationProgram'])->name('process-documentation-program2');
Route::get('/program-timses/download-documentation/{program_documentation_id}', [ProgramTimsesController::class, 'downloadDocumentationProgram'])->name('download-documentation2');
Route::get('/program-timses/delete-documentation/{program_documentation_id}', [ProgramTimsesController::class, 'deleteDocumentationProgram'])->name('delete-documentation2');
Route::get('/program-timses/delete-program/{program_id}', [ProgramTimsesController::class, 'deleteProgram'])->name('delete-program2');

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
Route::post('/financial-category/process-edit', [FinancialCategoryController::class, 'processEditFinancialCategory'])->name('process-edit-category');
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
Route::get('/report-recap',[RecapitulationReportController::class, 'index']);
Route::post('/report-recap/filter',[RecapitulationReportController::class, 'filterRecapitulationReport'])->name('filter-report-recap');
Route::get('/report-recap/reset-filter',[RecapitulationReportController::class, 'filterResetRecapitulationReport'])->name('filter-reset-report-recap');
Route::get('/report-recap/print',[RecapitulationReportController::class, 'printRecapitulationReport'])->name('print-report-recap');
Route::get('/report-recap/export',[RecapitulationReportController::class, 'exportRecapitulationReport'])->name('export-report-recap');

//Funding Acct Report
Route::get('/report-funding',[FundingAcctReportController::class, 'index']);
Route::post('/report-funding/filter',[FundingAcctReportController::class, 'filterFundingAcctReport'])->name('filter-report-funding');
Route::get('/report-funding/reset-filter',[FundingAcctReportController::class, 'filterResetFundingAcctReport'])->name('filter-reset-report-funding');
Route::get('/report-funding/print',[FundingAcctReportController::class, 'printFundingAcctReport'])->name('print-report-funding');
Route::get('/report-funding/export',[FundingAcctReportController::class, 'exportFundingAcctReport'])->name('export-report-funding');

//Timses Activity Report
Route::get('/report-timses-activity',[TimsesActivityReportController::class, 'index']);
Route::post('/report-timses-activity/filter',[TimsesActivityReportController::class, 'filterTimsesActivityReport'])->name('filter-report-timses-activity');
Route::get('/report-timses-activity/reset-filter',[TimsesActivityReportController::class, 'filterResetTimsesActivityReport'])->name('filter-reset-report-timses-activity');
Route::get('/report-timses-activity/print',[TimsesActivityReportController::class, 'printTimsesActivityReport'])->name('print-report-timses-activity');
Route::get('/report-timses-activity/export',[TimsesActivityReportController::class, 'exportTimsesActivityReport'])->name('export-report-timses-activity');

//funding in menu timses
Route::get('/income-timses', [FundingTimsesController::class, 'indexTimses']);
Route::get('/income-timses/add', [FundingTimsesController::class, 'addFundingIncomeTimses'])->name('add-income');
Route::post('/income-timses/process-add', [FundingTimsesController::class, 'processAddFundingIncomeTimses'])->name('process-add-income-timses');
Route::get('/income-timses/edit/{financial_flow_id}', [FundingTimsesController::class, 'editFundingIncomeTimses'])->name('edit-income-timses');
Route::post('/income-timses/process-edit', [FundingTimsesController::class, 'processEditFundingIncomeTimses'])->name('process-edit-income-timses');
Route::get('/income-timses/delete-income/{financial_flow_id}', [FundingTimsesController::class, 'deleteFundingIncomeTimses'])->name('delete-income-timses');

Route::get('/expenditure-timses', [FundingTimsesController::class, 'indexExpenditureTimses']);
Route::get('/expenditure-timses/add', [FundingTimsesController::class, 'addFundingExpenditureTimses'])->name('add-expenditure-timses');
Route::post('/expenditure-timses/process-add', [FundingTimsesController::class, 'processAddFundingExpenditureTimses'])->name('process-add-expenditure-timses');
Route::get('/expenditure-timses/edit/{financial_flow_id}', [FundingTimsesController::class, 'editFundingExpenditureTimses'])->name('edit-expenditure-timses');
Route::post('/expenditure-timses/process-edit', [FundingTimsesController::class, 'processEditFundingExpenditureTimses'])->name('process-edit-expenditure-timses');
Route::get('/expenditure-timses/delete-expenditure/{financial_flow_id}', [FundingTimsesController::class, 'deleteFundingExpenditureTimses'])->name('delete-expenditure-timses');

//timses activity report in menu timses
Route::get('/report-timses-activity2',[TimsesActivityReport2Controller::class, 'index']);
Route::post('/report-timses-activity2/filter',[TimsesActivityReport2Controller::class, 'filterTimsesActivityReport2'])->name('filter-report-timses-activity2');
Route::get('/report-timses-activity2/reset-filter',[TimsesActivityReport2Controller::class, 'filterResetTimsesActivityReport2'])->name('filter-reset-report-timses-activity2');
Route::get('/report-timses-activity2/print',[TimsesActivityReport2Controller::class, 'printTimsesActivityReport2'])->name('print-report-timses-activity2');
Route::get('/report-timses-activity2/export',[TimsesActivityReport2Controller::class, 'exportTimsesActivityReport2'])->name('export-report-timses-activity2');

//Report Funding Income in menub timses
Route::get('/report-income2', [FundingIncomeReport2Controller::class, 'index']);
Route::post('/report-income2/filter',[FundingIncomeReport2Controller::class, 'filterFundingIncomeReport2'])->name('filter-report-income2');
Route::get('/report-income2/filter-reset',[FundingIncomeReport2Controller::class, 'filterResetFundingIncomeReport2'])->name('filter-reset-report-income2');
Route::get('/report-income2/print',[FundingIncomeReport2Controller::class, 'printFundingIncomeReport2'])->name('print-funding-income-report2');
Route::get('/report-income2/export',[FundingIncomeReport2Controller::class, 'exportFundingIncomeReport2'])->name('export-funding-income-report2');

//Report Funding Expenditure
Route::get('/report-expenditure2', [FundingExpenditureReport2Controller::class, 'index']);
Route::post('/report-expenditure2/filter',[FundingExpenditureReport2Controller::class, 'filterFundingExpenditureReport2'])->name('filter-report-expenditure2');
Route::get('/report-expenditure2/filter-reset',[FundingExpenditureReport2Controller::class, 'filterResetFundingExpenditureReport2'])->name('filter-reset-report-expenditure2');
Route::get('/report-expenditure2/print',[FundingExpenditureReport2Controller::class, 'printFundingExpenditureReport2'])->name('print-funding-expenditure-report2');
Route::get('/report-expenditure2/export',[FundingExpenditureReport2Controller::class, 'exportFundingExpenditureReport2'])->name('export-funding-expenditure-report2');

//Report Funding Combine
Route::get('/report-combine', [FundingCombineReportController::class, 'index']);
Route::post('/report-combine/filter',[FundingCombineReportController::class, 'filterFundingCombineReport'])->name('filter-report-combine');
Route::get('/report-combine/filter-reset',[FundingCombineReportController::class, 'filterResetFundingCombineReport'])->name('filter-reset-report-combine');
Route::get('/report-combine/print',[FundingCombineReportController::class, 'printFundingCombineReport'])->name('print-funding-combine-report');
Route::get('/report-combine/export',[FundingCombineReportController::class, 'exportFundingCombineReport'])->name('export-funding-combine-report');
