<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CorePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CorePeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $coreperiod = CorePeriod::where('core_period.data_state','=',0)->get();
        return view('content/CorePeriod_view/ListCorePeriod', compact('coreperiod'));
    }
    
    public function addCorePeriod(Request $request){
        $coreperiod = Session::get('data_coreperiod');
        return view('content/CorePeriod_view/FormAddCorePeriod', compact('coreperiod'));
    }

    public function processAddCorePeriod(Request $request){
        $fields = $request->validate([
            // 'period_name'           => 'required',
            'period_year'           => 'required|numeric',
        ]);

        $data = array(
            // 'period_name'           => $fields['period_name'], 
            'period_year'           => $fields['period_year'],
            'created_id'            => Auth::id(),
            'created_at'            => date('Y-m-d'),
        );

        if(CorePeriod::create($data)){
            $msg = 'Tambah Data Periode Berhasil';
            return redirect('/period')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Periode Gagal';
            return redirect('/period')->with('msg',$msg);
        }
    }

    public function editCorePeriod($period_id){
        $coreperiod = CorePeriod::where('data_state','=',0)->where('period_id', $period_id)->first();
        // print_r($coreperiod); exit;

        return view('content/CorePeriod_view/FormEditCorePeriod', compact('coreperiod'));
    }

    public function processEditCorePeriod(Request $request){
        $fields = $request->validate([
            'period_id'             => 'required',
            // 'period_name'           => 'required',
            'period_year'           => 'required|numeric',
        ]);

        $item  = CorePeriod::findOrFail($fields['period_id']);
        // $item->period_name        = $fields['period_name'];
        $item->period_year        = $fields['period_year'];

            if($item->save()){
                $msg = 'Edit Data Periode Berhasil';
                return redirect('/period')->with('msg',$msg);
            }else{
                $msg = 'Edit Data Periode Gagal';
                return redirect('/period')->with('msg',$msg);
            }
    }

    public function deleteCorePeriod($period_id){
        $item               = CorePeriod::findOrFail($period_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Data Periode Berhasil';
        }else{
            $msg = 'Hapus Data Periode Gagal';
        }

        return redirect('/period')->with('msg',$msg);
    }
}
