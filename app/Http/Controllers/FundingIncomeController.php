<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialCategory;
use App\Models\FinancialFlow;
use App\Models\CoreTimsesMember;
use App\Models\CoreCandidate;

class FundingIncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexTimses(){
        $fundingincome = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_timses_member.*')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_timses_member', 'core_timses_member.timses_member_id', '=', 'financial_flow.timses_member_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_timses_member.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 1)
        ->get();

        return view('content/FundingIncome_view/ListFundingIncomeTimses', compact('fundingincome'));
    }

    public function indexCandidate(){
        $fundingincome = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_candidate.*')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'financial_flow.candidate_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_candidate.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 1)
        ->get();

        return view('content/FundingIncome_view/ListFundingIncomeCandidate', compact('fundingincome'));
    }

    public function addFundingIncomeCandidate(Request $request){
        $fundingincome = Session::get('data_fundingincome');

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');
        $nullcorecandidate  = Session::get('candidate_id');

        return view('content/FundingIncome_view/FormAddFundingIncomeCandidate', compact('fundingincome', 'financialcategory', 'nullfinancialcategory', 'corecandidate', 'nullcorecandidate'));
    }

    public function addFundingIncomeTimses(Request $request){
        $fundingincome = Session::get('data_fundingincome');

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $coretimsesmember = CoreTimsesMember::where('data_state', '=', 0)->pluck('timses_member_name', 'timses_member_id');
        $nullcoretimses  = Session::get('timses_member_id');

        return view('content/FundingIncome_view/FormAddFundingIncomeTimses', compact('fundingincome', 'financialcategory', 'nullfinancialcategory', 'coretimsesmember', 'nullcoretimses'));
    }

    public function addElementsFundingIncome(Request $request){
        $data_fundingincome[$request->name] = $request->value;

        $fundingincome = Session::get('data_fundingincome');
        
        return redirect('/funding-income/add');
    }

    public function addReset(){
        Session::forget('data_fundingincome');

        return redirect('/funding-income/add');
    }

    public function processAddFundingIncomeCandidate(Request $request){
        $fields = $request->validate([
            'financial_category_id'             => 'required',
            'candidate_id'                      => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required|numeric',
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

        $last_balance_candidate = CoreCandidate::findOrFail($fields['candidate_id']);
        $last_balance_candidate->last_balance += $fields['financial_flow_nominal'];

        if(FinancialFlow::create($data)){
            $last_balance_candidate->save();

            $msg = 'Tambah Pemasukan Keuangan Kandidat Berhasil';
            return redirect('/funding-income-candidate/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pemasukan Keuangan Kandidat Gagal';
            return redirect('/funding-income-candidate/add')->with('msg',$msg);
        }
    }

    public function processAddFundingIncomeTimses(Request $request){
        $fields = $request->validate([
            'financial_category_id'             => 'required',
            'timses_member_id'                  => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $data = array(
            'financial_category_id'             => $fields['financial_category_id'], 
            'timses_member_id'                  => $fields['timses_member_id'], 
            'financial_category_type'           => $fields['financial_category_type'],
            'financial_flow_nominal'            => $fields['financial_flow_nominal'],
            'financial_flow_date'               => $fields['financial_flow_date'],
            'financial_flow_description'        => $fields['financial_flow_description'],
            'created_id'                        => Auth::id(),
            'created_at'                        => date('Y-m-d'),
        );

        // dd($data);

        $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

        $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
        $last_balance_candidate->last_balance += $fields['financial_flow_nominal'];

        $last_balance_timses = CoreTimsesMember::findOrFail($fields['timses_member_id']);
        $last_balance_timses->last_balance += $fields['financial_flow_nominal'];

        if(FinancialFlow::create($data)){
            $last_balance_candidate->save();
            $last_balance_timses->save();
            // dd( $item);
            $msg = 'Tambah Pemasukan Keuangan Timses Berhasil';
            return redirect('/funding-income-timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pemasukan Keuangan Timses Gagal';
            return redirect('/funding-income-timses/add')->with('msg',$msg);
        }
    }

    public function editFundingIncomeCandidate($financial_flow_id){
        $fundingincome = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');

        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');

        return view('content/FundingIncome_view/FormEditFundingIncomeCandidate', compact('fundingincome', 'financialcategory', 'corecandidate'));
    }

    public function editFundingIncomeTimses($financial_flow_id){
        $fundingincome = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');

        $coretimsesmember = CoreTimsesMember::where('data_state', '=', 0)->pluck('timses_member_name', 'timses_member_id');
        // $nullcoretimses  = Session::get('coretimsesmember');

        return view('content/FundingIncome_view/FormEditFundingIncomeTimses', compact('fundingincome', 'financialcategory', 'coretimsesmember'));
    }

    public function processEditFundingIncomeCandidate(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            'candidate_id'                      => 'required',
            'financial_category_id'             => 'required',
            'financial_flow_nominal'            => 'required|numeric',
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
            $msg = 'Edit Pemasukan Keuangan Candidate Berhasil';
            return redirect('/funding-income-candidate')->with('msg',$msg);
        }else{
            $msg = 'Edit Pemasukan Keuangan Candidate Gagal';
            return redirect('/funding-income-candidate')->with('msg',$msg);
        }
    }

    public function processEditFundingIncomeTimses(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            'timses_member_id'                  => 'required',
            'financial_category_id'             => 'required',
            'financial_flow_nominal'            => 'required',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $item  = FinancialFlow::findOrFail($fields['financial_flow_id']);
        $item->financial_category_id            = $fields['financial_category_id'];
        $item->timses_member_id                 = $fields['timses_member_id'];
        $item->financial_flow_nominal           = $fields['financial_flow_nominal'];
        $item->financial_flow_date              = $fields['financial_flow_date'];
        $item->financial_flow_description       = $fields['financial_flow_description'];

        if($item->save()){
            $msg = 'Edit Pemasukan Keuangan Berhasil';
            return redirect('/funding-income-timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Pemasukan Keuangan Gagal';
            return redirect('/funding-income-timses')->with('msg',$msg);
        }
    }

    public function deleteFundingIncomeCandidate($financial_flow_id){
        $item               = FinancialFlow::findOrFail($financial_flow_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Pemasukan Keuangan Kandidat Berhasil';
        }else{
            $msg = 'Hapus Pemasukan Keuangan Kandidat Gagal';
        }

        return redirect('/funding-income-candidate')->with('msg',$msg);
    }

    public function deleteFundingIncomeTimses($financial_flow_id){
        $item               = FinancialFlow::findOrFail($financial_flow_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Pemasukan Keuangan Timses Berhasil';
        }else{
            $msg = 'Hapus Pemasukan Keuangan Timses Gagal';
        }

        return back()->with('msg',$msg);
    }

    
}
