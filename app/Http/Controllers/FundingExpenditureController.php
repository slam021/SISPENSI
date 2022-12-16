<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialCategory;
use App\Models\FinancialFlow;
use App\Models\CoreCandidate;
use App\Models\CoreTimses;

class FundingExpenditureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexCandidate(){
        $fundingexpenditure = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_candidate.*')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'financial_flow.candidate_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_candidate.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 2)
        ->get();

        return view('content/FundingExpenditure_view/ListFundingExpenditureCandidate', compact('fundingexpenditure'));
    }

    public function indexTimses(){
        $fundingexpenditure = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_timses.timses_name')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'financial_flow.timses_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_timses.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 2)
        ->get();

        return view('content/FundingExpenditure_view/ListFundingExpenditureTimses', compact('fundingexpenditure'));
    }

    public function addFundingExpenditureCandidate(Request $request){
        $fundingexpenditure = Session::get('data_fundingexpenditure');

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');
        $nullcorecandidate  = Session::get('candidate_id');

        return view('content/FundingExpenditure_view/FormAddFundingExpenditureCandidate', compact('fundingexpenditure', 'financialcategory', 'nullfinancialcategory', 'corecandidate', 'nullcorecandidate'));
    }

    public function addFundingExpenditureTimses(Request $request){
        $fundingexpenditure = Session::get('data_fundingexpenditure');

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        $nullcoretimses  = Session::get('timses_id');

        return view('content/FundingExpenditure_view/FormAddFundingExpenditureTimses', compact('fundingexpenditure', 'financialcategory', 'nullfinancialcategory', 'coretimses', 'nullcoretimses'));
    }


    public function addElementsFundingExpenditure(Request $request){
        $data_fundingexpenditure[$request->name] = $request->value;

        $fundingexpenditure = Session::get('data_fundingexpenditure');
        
        return redirect('/funding-expenditure/add');
    }

    public function addReset(){
        Session::forget('data_fundingexpenditure');

        return redirect('/funding-expenditure/add');
    }

    public function processAddFundingExpenditureCandidate(Request $request){
        $fields = $request->validate([
            'financial_category_id'             => 'required',
            'candidate_id'                      => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $data = array(
            'financial_category_id'             => $fields['financial_category_id'], 
            'candidate_id'                      => $fields['candidate_id'], 
            'financial_category_type'           => $fields['financial_category_type'],
            'financial_flow_nominal'            => $fields['financial_flow_nominal'],
            'financial_flow_date'               => $fields['financial_flow_date'],
            'financial_flow_description'        => $fields['financial_flow_description'],
            'created_id'                        => Auth::id(),
            'created_at'                        => date('Y-m-d'),
        );

        if(FinancialFlow::create($data)){
            $msg = 'Tambah Pengeluaran Keuangan Kandidat Berhasil';
            return redirect('/funding-expenditure-candidate/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pengeluaran Keuangan Kandidat Gagal';
            return redirect('/funding-expenditure-candidate/add')->with('msg',$msg);
        }
    }

    public function processAddFundingExpenditureTimses(Request $request){
        $fields = $request->validate([
            'financial_category_id'             => 'required',
            'timses_id'                         => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $data = array(
            'financial_category_id'             => $fields['financial_category_id'], 
            'timses_id'                         => $fields['timses_id'], 
            'financial_category_type'           => $fields['financial_category_type'],
            'financial_flow_nominal'            => $fields['financial_flow_nominal'],
            'financial_flow_date'               => $fields['financial_flow_date'],
            'financial_flow_description'        => $fields['financial_flow_description'],
            'created_id'                        => Auth::id(),
            'created_at'                        => date('Y-m-d'),
        );

        if(FinancialFlow::create($data)){
            $msg = 'Tambah Pengeluaran Keuangan Timses Berhasil';
            return redirect('/funding-expenditure-timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pengeluaran Keuangan Timses Gagal';
            return redirect('/funding-expenditure-timses/add')->with('msg',$msg);
        }
    }

    public function editFundingExpenditureCandidate($financial_flow_id){
        $fundingexpenditure = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');

        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');

        return view('content/FundingExpenditure_view/FormEditFundingExpenditureCandidate', compact('fundingexpenditure', 'financialcategory', 'corecandidate'));
    }

    public function editFundingExpenditureTimses($financial_flow_id){
        $fundingexpenditure = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');

        return view('content/FundingExpenditure_view/FormEditFundingExpenditureTimses', compact('fundingexpenditure', 'financialcategory', 'coretimses'));
    }

    public function processEditFundingExpenditureCandidate(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            'financial_category_id'             => 'required',
            'candidate_id'           => 'required',
            'financial_flow_nominal'            => 'required',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $item  = FinancialFlow::findOrFail($fields['financial_flow_id']);
        $item->financial_category_id            = $fields['financial_category_id'];
        $item->candidate_id                     = $fields['candidate_id'];
        $item->financial_flow_nominal           = $fields['financial_flow_nominal'];
        $item->financial_flow_date              = $fields['financial_flow_date'];
        $item->financial_flow_description       = $fields['financial_flow_description'];

        if($item->save()){
            $msg = 'Edit Pengeluaran Keuangan Kandidat Berhasil';
            return redirect('/funding-expenditure-candidate')->with('msg',$msg);
        }else{
            $msg = 'Edit Pengeluaran Keuangan Kandidat Gagal';
            return redirect('/funding-expenditure-candidate')->with('msg',$msg);
        }
    }

    public function processEditFundingExpenditureTimses(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            'financial_category_id'             => 'required',
            'timses_id'                         => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $item  = FinancialFlow::findOrFail($fields['financial_flow_id']);
        $item->financial_category_id            = $fields['financial_category_id'];
        $item->timses_id                        = $fields['timses_id'];
        $item->financial_flow_nominal           = $fields['financial_flow_nominal'];
        $item->financial_flow_date              = $fields['financial_flow_date'];
        $item->financial_flow_description       = $fields['financial_flow_description'];

        if($item->save()){
            $msg = 'Edit Pengeluaran Keuangan Timses Berhasil';
            return redirect('/funding-expenditure-timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Pengeluaran Keuangan Timses Gagal';
            return redirect('/funding-expenditure-timses')->with('msg',$msg);
        }
    }

    public function deleteFundingExpenditure($financial_flow_id){
        $item               = FinancialFlow::findOrFail($financial_flow_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Pengeluaran Keuangan Berhasil';
        }else{
            $msg = 'Hapus Pengeluaran Keuangan Gagal';
        }

        return redirect('/funding-expenditure')->with('msg',$msg);
    }
}
