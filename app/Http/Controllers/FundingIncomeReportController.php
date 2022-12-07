<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialFlow;
use Carbon\Carbon;

class FundingIncomeReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        
        $start_date='';
        $end_date='';
       

        // dd($start_date);
  
        $fundingincome = FinancialFlow::where('data_state', '=', 0)
                        ->where('financial_flow_date', '>=', $start_date)
                        ->where('financial_flow_date', '<=', $end_date)
                        ->get();

        // return $fundingincome;
        // if (request()->start_date || request()->end_date) {
        //     $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
        //     $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
        //     $fundingincome = FinancialFlow::whereBetween('created_at',[$start_date,$end_date])->get();
        // } else {
        //     $fundingincome = FinancialFlow::latest()->get();
        // }

        // dd($users);
        // $fundingincome = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_timses.timses_name')
        // ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        // ->join('core_timses', 'core_timses.timses_id', '=', 'financial_flow.timses_id')
        // ->where('financial_category.data_state', '=', 0)
        // ->where('financial_flow.data_state', '=', 0)
        // ->where('core_timses.data_state', '=', 0)
        // ->whereBetween('financial_flow_date', [$start_date, $end_date])
        // // ->where('financial_flow_date','>=',$start_date)
        // // ->where('financial_flow_date','<=',$end_date)
        // ->where('financial_flow.financial_category_type', '=', 1)
        // ->get();
        return view('content/FundingIncomeReport_view/ReportFundingIncome', compact('fundingincome'));
    }

    public function filterFundingIncomeReport(Request $request)
    {
        $Date1=$request->start_date;
        $Date2=$request->end_date;
        $start_date = Carbon::createFromFormat('Y-m-d', $Date1);
        $end_date = Carbon::createFromFormat('Y-m-d', $Date2);
        $fundingincome = FinancialFlow::where('data_state', '=', 0)
        // ->where('financial_flow_date', '>=', $start_date)
        // ->where('financial_flow_date', '<=', $end_date)
        ->get();
        // dd($fundingincome );
        return view('content/FundingIncomeReport_view/ReportFundingIncome', compact('fundingincome'));
    }

    public function filterResetFundingIncomeReport()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('warehouse_id');
        return redirect('/report-income');
    }
}
