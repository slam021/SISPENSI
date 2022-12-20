<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CoreCandidate;
use App\Models\FinancialFlow;
use App\Models\CoreTimses;
use App\Models\FinancialCategory;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class FundingAcctReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        if(!$start_month = Session::get('start_month')){
            $start_month = date('m');
        }else{
            $start_month = Session::get('start_month');
        }
        if(!$end_month = Session::get('end_month')){
            $end_month = date('m');
        }else{
            $end_month = Session::get('end_month');
        }
        if(!$year = Session::get('year')){
            $year = date('Y');
        }else{
            $year = Session::get('year');
        }

        $monthlist = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );

        $year_now 	=	date('Y');
        for($i=($year_now-2); $i<($year_now+2); $i++){
            $yearlist[$i] = $i;
        } 

        if(!Session::get('start_date')){
            $start_date     = date('Y-m-d');
        }else{
            $start_date = Session::get('start_date');
        }
        if(!Session::get('end_date')){
            $end_date     = date('Y-m-d');
        }else{
            $end_date = Session::get('end_date');
        }

        $code=[
            '' => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];

        $listfinancialcategory = FinancialCategory::where('data_state', '=', 0)
        ->pluck('financial_category_name', 'financial_category_id');

        $listcoretimses = CoreTimses :: where('data_state', 0)
        ->get()
        ->pluck('timses_name', 'timses_id');

        $listcorecandidate = CoreCandidate :: where('data_state', 0)
        ->get()
        ->pluck('candidate_full_name', 'candidate_id');

        $category_income = FinancialCategory::where('financial_category.data_state', '=', 0)
        // ->join('financial_flow', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        // ->whereMonth('financial_flow.financial_flow_date', $start_month)
        // ->whereYear('financial_flow.financial_flow_date',$year)
        // ->orderBy('financial_flow.financial_flow_date', 'DESC')
        // ->orderBy('financial_flow.last_balance_candidate', 'DESC')
        ->where('financial_category.financial_category_type', '=', 1)
        ->get();
        
        $category_expenditure = FinancialCategory::where('financial_category.data_state', '=', 0)
        // ->join('financial_flow', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        // ->whereMonth('financial_flow.financial_flow_date', $start_month)
        // ->whereYear('financial_flow.financial_flow_date',$year)
        // ->orderBy('financial_flow.financial_flow_date', 'DESC')
        // ->orderBy('financial_flow.last_balance_candidate', 'DESC')
        ->where('financial_category.financial_category_type', '=', 2)
        ->get();
        
        $last_balance_timses_old = FinancialFlow::where('financial_flow.data_state', '=', 0)
        ->whereMonth('financial_flow.financial_flow_date', $start_month)
        ->whereYear('financial_flow.financial_flow_date',$year)
        ->orderBy('financial_flow.financial_flow_date', 'DESC')
        ->orderBy('financial_flow.last_balance_timses', 'DESC')
        ->get();
        
        $financialflow_income = FinancialFlow::where('financial_flow.data_state', '=', 0)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year);
        // ->where('financial_flow.financial_category_id', $category_income);
        
        $financial_category_id = Session::get('financial_category_id');
        $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');
        $financialflow_list = Session::get('financialflow_list');
        
        if($financial_category_id||$financial_category_id!=null||$financial_category_id!=''){
            $financialflow_income   = $financialflow_income->where('financial_category_id', $financial_category_id);
        }
        
        if($financialflow_list||$financialflow_list!=null||$financialflow_list!=''){
            if($financialflow_list == 1){           
                $financialflow_income   = $financialflow_income->where('candidate_id', '!=', null);
            }else{
                $financialflow_income   = $financialflow_income->where('timses_id', '!=', null);
            }
        }else{
            $financialfinancialflow_incomeflow   = $financialflow_income->where('candidate_id', '=', null);
            $financialflow_income   = $financialflow_income->where('timses_id', '=', null);
        }

        $financialflow_income   = $financialflow_income->get();
        
        // dd($financialflow_income);
        $financialflow = FinancialFlow::where('financial_flow.data_state', '=', 0)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year);
        // ->where('financial_flow.financial_category_id', $category_expenditure);
        
        $financial_category_id = Session::get('financial_category_id');
        $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');
        $financialflow_list = Session::get('financialflow_list');
        
        if($financial_category_id||$financial_category_id!=null||$financial_category_id!=''){
            $financialflow   = $financialflow->where('financial_category_id', $financial_category_id);
        }

        if($financialflow_list||$financialflow_list!=null||$financialflow_list!=''){
            if($financialflow_list == 1){           
                $financialflow   = $financialflow->where('candidate_id', '!=', null);
            }else{
                $financialflow   = $financialflow->where('timses_id', '!=', null);
            }
        }else{
            $financialflow   = $financialflow->where('candidate_id', '=', null);
            $financialflow   = $financialflow->where('timses_id', '=', null);
        }

        $financialflow   = $financialflow->get();

        return view('content.FundingAcctReport_view.ReportFundingAcct', compact('start_month','end_month', 'monthlist', 'year' , 'yearlist', 'year_now', 'listfinancialcategory', 'financial_category_id', 'timses_id', 'candidate_id', 'listcoretimses', 'listcorecandidate', 'financialflow', 'start_date', 'end_date', 'financialflow_list', 'category_income', 'category_expenditure', 'last_balance_timses_old', 'code', 'financialflow_income'));
    }

    public function filterFundingAcctReport(Request $request){
        $start_month  = $request->start_month;
        $end_month    = $request->end_month;
        $start_date  = $request->start_date;
        $end_date    = $request->end_date;
        $year         = $request->year;
        $timses_id    = $request->timses_id;
        $candidate_id = $request->candidate_id;
        $financial_category_id = $request->financial_category_id;
        $financialflow_list = $request->financialflow_list;

        Session::put('timses_id', $timses_id);
        Session::put('candidate_id', $candidate_id);
        Session::put('financial_category_id', $financial_category_id);
        Session::put('financialflow_list', $financialflow_list);
        Session::put('start_month',$start_month);
        Session::put('end_month',$end_month);
        Session::put('start_date',$start_date);
        Session::put('end_date',$end_date);
        Session::put('year',$year);
// dd($financialflow_list);
        return redirect('/report-funding');
    }

    public function filterResetFundingAcctReport(){
        Session::put('start_month');
        Session::put('end_month');
        Session::put('start_date');
        Session::put('end_date');
        Session::put('year');
        Session::put('year');
        Session::forget('timses_id');
        Session::forget('candidate_id');
        Session::forget('financial_category_id');
        Session::forget('financialflow_list');

        return redirect('/report-funding');
    }

    public function getCategoryName($financial_category_id){
        $data = FinancialCategory::where('financial_category_id',$financial_category_id)
        ->first();

        return $data['financial_category_name'];
    }

    public function getTimsesName($timses_id){
        $data = CoreTimses::where('timses_id', $timses_id)
        ->first();

        return $data['timses_name'];
    }

    public function getCandidateName($candidate_id){
        $data = CoreCandidate::where('candidate_id',$candidate_id)
        ->first();

        return $data['candidate_full_name'];
    }
}
