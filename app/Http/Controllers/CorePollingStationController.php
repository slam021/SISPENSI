<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CorePollingStation;
use App\Models\Coredapil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CorePollingStationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $corepollingstation = CorePollingStation::select('core_polling_station.*','core_dapil.dapil_name')->where('core_polling_station.data_state','=',0)
        ->join('core_dapil', 'core_dapil.dapil_id', '=', 'core_polling_station.dapil_id')->get();
        return view('content/CorePollingStation_view/ListCorePollingStation', compact('corepollingstation'));
    }

    public function addCorePollingStation(Request $request){
        $corepollingstation = Session::get('data_corepollingstation');
        
        $coredapil = CoreDapil::where('data_state', '=', 0)->pluck('dapil_name', 'dapil_id');
        $nullcoredapil = Session::get('dapil_id');

        return view('content/CorePollingStation_view/FormAddCorePollingStation', compact('corepollingstation', 'coredapil', 'nullcoredapil'));
    }

    public function addElementsCorePollingStation(Request $request){
        $data_corepollingstation[$request->name] = $request->value;

        $corepollingstation = Session::get('data_corepollingstation');
        
        return redirect('/polling-station/add');
    }

    public function addReset(){
        Session::forget('data_corepollingstation');

        return redirect('/polling-station/add');
    }

    public function processAddCorePollingStation(Request $request){
        $fields = $request->validate([
            'dapil_id'                  => 'required',
            'polling_station_name'      => 'required',
            'polling_station_address'   => 'required',
        ]);

        $data = array(
            'dapil_id'                  => $fields['dapil_id'],    
            'polling_station_name'      => $fields['polling_station_name'], 
            'polling_station_address'   => $fields['polling_station_address'],
            'created_id'                => Auth::id(),
            'created_at'                => date('Y-m-d'),
        );

        if(CorePollingStation::create($data)){
            $msg = 'Tambah Data TPS Berhasil';
            return redirect('/polling-station/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data TPS Gagal';
            return redirect('/polling-station/add')->with('msg',$msg);
        }
    }

    public function editCorePollingStation($polling_station_id){
        $corepollingstation = CorePollingStation::where('data_state','=',0)->where('polling_station_id', $polling_station_id)->first();
        // print_r($coreperiod); exit;
        $coredapil = CoreDapil::where('data_state', '=', 0)->pluck('dapil_name', 'dapil_id');

        return view('content/CorePollingStation_view/FormEditCorePollingStation', compact('corepollingstation', 'coredapil'));
    }

    public function processEditCorePollingStation(Request $request){
        $fields = $request->validate([
            'polling_station_id'        => 'required',
            'dapil_id'               => 'required',
            'polling_station_name'      => 'required',
            'polling_station_address'   => 'required',
        ]);

        $item  = CorePollingStation::findOrFail($fields['polling_station_id']);
        $item->dapil_id              = $fields['dapil_id'];
        $item->polling_station_name     = $fields['polling_station_name'];
        $item->polling_station_address  = $fields['polling_station_address'];
            // $item->photos            = $request['photos'];

        if($item->save()){
            $msg = 'Edit Data TPS Berhasil';
            return redirect('/polling-station')->with('msg',$msg);
        }else{
            $msg = 'Edit Data TPS Gagal';
            return redirect('/polling-station')->with('msg',$msg);
        }
    }

    public function deleteCorePollingStation($polling_station_id){
        $item               = CorePollingStation::findOrFail($polling_station_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Data TPS Berhasil';
        }else{
            $msg = 'Hapus Data TPS Gagal';
        }

        return redirect('/polling-station')->with('msg',$msg);
    }

}
