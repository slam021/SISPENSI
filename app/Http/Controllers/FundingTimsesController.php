<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialCategory;
use App\Models\FinancialFlow;
use App\Models\CoreTimses;
use App\Models\CoreCandidate;
use App\Models\User;

class FundingTimsesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexTimses(){
        $fundingincome = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_timses.*')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'financial_flow.timses_id')
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_timses.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 1)
        ->where('core_timses.user_id', Auth::id())
        ->get();

        return view('content/FundingTimses_view/ListFundingIncomeTimses', compact('fundingincome'));
    }

    public function addFundingIncomeTimses(Request $request){
        $fundingincome = Session::get('data_fundingincome');

        $user_login = User::where('system_user.data_state', 0)
        ->join('core_timses', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('core_timses.user_id', Auth::id())->first()->timses_id;

        // dd($user_login );

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        $nullcoretimses  = Session::get('timses_id');

        return view('content/FundingTimses_view/FormAddFundingIncomeTimses', compact('fundingincome', 'financialcategory', 'nullfinancialcategory', 'coretimses', 'nullcoretimses', 'user_login'));
    }

    public function processAddFundingIncomeTimses(Request $request){

        $timses_id = User::where('system_user.data_state', 0)
        ->join('core_timses', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('core_timses.user_id', Auth::id())->first()->timses_id;
        
        $fields = $request->validate([
            'financial_category_id'             => 'required',
            // 'timses_id'                         => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $data = array(
            'financial_category_id'             => $fields['financial_category_id'], 
            'timses_id'                         => $timses_id, 
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

        $last_balance_timses = CoreTimses::findOrFail($timses_id);
        $last_balance_timses->last_balance += $fields['financial_flow_nominal'];

        if(FinancialFlow::create($data)){
            $last_balance_candidate->save();
            $last_balance_timses->save();
            // dd( $item);
            $msg = 'Tambah Pemasukan Keuangan Timses Berhasil';
            return redirect('/income-timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pemasukan Keuangan Timses Gagal';
            return redirect('/income-timses/add')->with('msg',$msg);
        }
    }

    public function editFundingIncomeTimses($financial_flow_id){
        $fundingincome = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 1)
        ->pluck('financial_category_name', 'financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        // $nullcoretimses  = Session::get('coretimses');

        return view('content/FundingTimses_view/FormEditFundingIncomeTimses', compact('fundingincome', 'financialcategory', 'coretimses'));
    }

    public function processEditFundingIncomeTimses(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            // 'timses_id'                  => 'required',
            'financial_category_id'             => 'required',
            'financial_flow_nominal'            => 'required',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $item  = FinancialFlow::findOrFail($fields['financial_flow_id']);
        $item->financial_category_id            = $fields['financial_category_id'];
        // $item->timses_id                        = $timses_id;
        $item->financial_flow_nominal           = $fields['financial_flow_nominal'];
        $item->financial_flow_date              = $fields['financial_flow_date'];
        $item->financial_flow_description       = $fields['financial_flow_description'];

        if($item->save()){
            $msg = 'Edit Pemasukan Keuangan Berhasil';
            return redirect('/income-timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Pemasukan Keuangan Gagal';
            return redirect('/income-timses')->with('msg',$msg);
        }
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

    public function indexExpenditureTimses(){
        $fundingexpenditure = FinancialFlow::select('financial_category.*', 'financial_flow.*', 'core_timses.*')
        ->join('financial_category', 'financial_category.financial_category_id', '=', 'financial_flow.financial_category_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'financial_flow.timses_id')
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('financial_category.data_state', '=', 0)
        ->where('financial_flow.data_state', '=', 0)
        ->where('core_timses.data_state', '=', 0)
        ->where('financial_flow.financial_category_type', '=', 2)
        ->where('core_timses.user_id', Auth::id())
        ->get();

        return view('content/FundingTimses_view/ListFundingExpenditureTimses', compact('fundingexpenditure'));
    }

    public function addFundingExpenditureTimses(Request $request){

        $user_login = User::where('system_user.data_state', 0)
        ->join('core_timses', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('core_timses.user_id', Auth::id())->first()->timses_id;

        $fundingexpenditure = Session::get('data_fundingexpenditure');

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');
        $nullfinancialcategory = Session::get('financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        $nullcoretimses  = Session::get('timses_id');

        return view('content/FundingTimses_view/FormAddFundingExpenditureTimses', compact('fundingexpenditure', 'financialcategory', 'nullfinancialcategory', 'coretimses', 'nullcoretimses', 'user_login'));
    }

    public function processAddFundingExpenditureTimses(Request $request){
        $timses_id = User::where('system_user.data_state', 0)
        ->join('core_timses', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('core_timses.user_id', Auth::id())->first()->timses_id;

        $fields = $request->validate([
            'financial_category_id'             => 'required',
            // 'timses_id'                  => 'required',
            'financial_category_type'           => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $data = array(
            'financial_category_id'             => $fields['financial_category_id'], 
            'timses_id'                         => $timses_id, 
            'financial_category_type'           => $fields['financial_category_type'],
            'financial_flow_nominal'            => $fields['financial_flow_nominal'],
            'financial_flow_date'               => $fields['financial_flow_date'],
            'financial_flow_description'        => $fields['financial_flow_description'],
            'created_id'                        => Auth::id(),
            'created_at'                        => date('Y-m-d'),
        );

        $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

        $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
        $last_balance_candidate->last_balance -= $fields['financial_flow_nominal'];

        $last_balance_timses = CoreTimses::findOrFail($timses_id);
        $last_balance_timses->last_balance -= $fields['financial_flow_nominal'];

        if(FinancialFlow::create($data)){
            $last_balance_candidate->save();
            $last_balance_timses->save();

            $msg = 'Tambah Pengeluaran Keuangan Timses Berhasil';
            return redirect('/expenditure-timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Pengeluaran Keuangan Timses Gagal';
            return redirect('/expenditure-timses/add')->with('msg',$msg);
        }
    }

    public function editFundingExpenditureTimses($financial_flow_id){
        $fundingexpenditure = FinancialFlow::where('data_state','=',0)->where('financial_flow_id', $financial_flow_id)->first();

        $financialcategory = FinancialCategory::where('data_state', '=', 0)
        ->where('financial_category_type', '=', 2)
        ->pluck('financial_category_name', 'financial_category_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');

        return view('content/FundingTimses_view/FormEditFundingExpenditureTimses', compact('fundingexpenditure', 'financialcategory', 'coretimses'));
    }

    public function processEditFundingExpenditureTimses(Request $request){
        $fields = $request->validate([
            'financial_flow_id'                 => 'required',
            'financial_category_id'             => 'required',
            // 'timses_id'                         => 'required',
            'financial_flow_nominal'            => 'required|numeric',
            'financial_flow_date'               => 'required',
            'financial_flow_description'        => 'required',
        ]);

        $item  = FinancialFlow::findOrFail($fields['financial_flow_id']);
        $item->financial_category_id            = $fields['financial_category_id'];
        // $item->timses_id                        = $fields['timses_id'];
        $item->financial_flow_nominal           = $fields['financial_flow_nominal'];
        $item->financial_flow_date              = $fields['financial_flow_date'];
        $item->financial_flow_description       = $fields['financial_flow_description'];

        if($item->save()){
            $msg = 'Edit Pengeluaran Keuangan Timses Berhasil';
            return redirect('/expenditure-timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Pengeluaran Keuangan Timses Gagal';
            return redirect('/expenditure-timses')->with('msg',$msg);
        }
    }

    public function deleteFundingExpenditureTimses($financial_flow_id){
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
