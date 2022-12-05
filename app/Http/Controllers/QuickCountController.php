<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\QuickCount;
use App\Models\CorePeriod;
use App\Models\CoreLocation;
use App\Models\CorePollingStation;
use App\Models\CoreCandidate;

class QuickCountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $quickcount = QuickCount::select('quick_count.*', 'core_period.*', 'core_location.*', 'core_polling_station.*')
        ->join('core_period', 'core_period.period_id', '=', 'quick_count.period_id')
        ->join('core_location', 'core_location.location_id', '=', 'quick_count.location_id')
        ->join('core_polling_station', 'core_polling_station.polling_station_id', '=', 'quick_count.polling_station_id')
        ->where('quick_count.data_state','=',0)
        ->get();

        return view('content/QuickCount_view/ListQuickCount', compact('quickcount'));
    }
    
    public function addQuickCount(Request $request){
        $quickcount       = Session::get('data_quickcount');

        $coreperiod       = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');
        $nullcoreperiod   = Session::get('period_id');

        $corelocation     = CoreLocation::where('data_state', '=', 0)->pluck('location_name', 'location_id');
        $nullcorelocation = Session::get('location_id');

        $corepollingstation     = CorePollingStation::where('data_state', '=', 0)->pluck('polling_station_name', 'polling_station_id');
        $nullcorepollingstation = Session::get('polling_station_id');

        return view('content/QuickCount_view/FormAddQuickCount', compact('quickcount', 'coreperiod', 'nullcoreperiod', 'corelocation', 'nullcorelocation', 'corepollingstation', 'nullcorepollingstation'));
    }

    public function addElementsQuickCount(Request $request){
        $data_quickcount[$request->name] = $request->value;

        $quickcount = Session::get('data_quickcount');
        
        return redirect('/quick-count/add');
    }

    public function addReset(){
        Session::forget('data_quickcount');

        return redirect('/quick-count/add');
    }

    public function processAddQuickCount(Request $request){
        $fields = $request->validate([
            'period_id'           => 'required',
            'location_id'         => 'required',
            'polling_station_id'  => 'required',
        ]);

        $data = array(
            'period_id'           => $fields['period_id'], 
            'location_id'         => $fields['location_id'],
            'polling_station_id'  => $fields['polling_station_id'],
            'created_id'          => Auth::id(),
            'created_at'          => date('Y-m-d'),
        );

        if(QuickCount::create($data)){
            $msg = 'Tambah Data Quick Count Berhasil';
            return redirect('/quick-count/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Quick Count Gagal';
            return redirect('/quick-count/add')->with('msg',$msg);
        }
    }

    public function editQuickCount($quick_count_id){
        $quickcount = QuickCount::select('quick_count.*', 'core_period.*', 'core_location.*', 'core_polling_station.*')
            ->join('core_period', 'core_period.period_id', '=', 'quick_count.period_id')
            ->join('core_location', 'core_location.location_id', '=', 'quick_count.location_id')
            ->join('core_polling_station', 'core_polling_station.polling_station_id', '=', 'quick_count.polling_station_id')
            ->where('quick_count.data_state','=',0)
            ->where('quick_count_id', $quick_count_id)->first();
        // $quickcount = QuickCount::where('data_state','=',0)->where('quick_count_id', $quick_count_id)->first();
            // print_r($quickcount); exit;
            $coreperiod       = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');

            $corelocation     = CoreLocation::where('data_state', '=', 0)->pluck('location_name', 'location_id');
    
            $corepollingstation     = CorePollingStation::where('data_state', '=', 0)->pluck('polling_station_name', 'polling_station_id');
    

            return view('content/QuickCount_view/FormEditQuickCount', compact('quickcount','coreperiod', 'corelocation', 'corepollingstation'));
    }

    public function processEditQuickCount(Request $request){
        $fields = $request->validate([
            'quick_count_id'      => 'required',
            'period_id'           => 'required',
            'location_id'         => 'required',
            'polling_station_id'  => 'required',
        ]);

        $item  = QuickCount::findOrFail($fields['quick_count_id']);
        $item->period_id          = $fields['period_id'];
        $item->location_id        = $fields['location_id'];
        $item->polling_station_id        = $fields['polling_station_id'];
            // $item->photos            = $request['photos'];

        if($item->save()){
            $msg = 'Edit Data Quick Count Berhasil';
            return redirect('/quick-count')->with('msg',$msg);
        } else {
            $msg = 'Edit Data Quick Count Gagal';
            return redirect('/quick-count')->with('msg',$msg);
        }
    }

    public function closingQuickCount($quick_count_id){
        $item                   = QuickCount::findOrFail($quick_count_id);
        $item->quick_count_status   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Penutupan Quick Count Berhasil';
        }else{
            $msg = 'Penutupan Quick Count Gagal';
        }

        return redirect('/quick-count')->with('msg',$msg);
    }

    public function startingQuickCount($quick_count_id, $period_id){   
        $quickcount = QuickCount::select('quick_count.*', 'core_period.*', 'core_location.*', 'core_polling_station.*')
            ->join('core_period', 'core_period.period_id', '=', 'quick_count.period_id')
            ->join('core_location', 'core_location.location_id', '=', 'quick_count.location_id')
            ->join('core_polling_station', 'core_polling_station.polling_station_id', '=', 'quick_count.polling_station_id')
            ->where('quick_count.data_state','=',0)
            ->where('core_period.data_state','=',0)
            ->where('core_location.data_state','=',0)
            ->where('core_polling_station.data_state','=',0)
            ->where('quick_count_id', $quick_count_id)->first();

        $corecandidate = CoreCandidate::select('quick_count.*', 'core_candidate.*')
            ->join('quick_count', 'quick_count.period_id', '=', 'core_candidate.period_id')
            ->where('quick_count.data_state','=',0)
            ->where('core_candidate.data_state','=',0)
            // ->where('core_candidate.candidate_id', $candidate_id)->get()
            ->where('core_candidate.period_id', $period_id)->get();

            // print_r($corecandidate); exit;
        return view('content/QuickCount_view/FormStartingQuickCount', compact('quickcount', 'corecandidate'));

    }

    public function subtractionStartingQuickCount($candidate_id){
        $item                    = CoreCandidate::findOrFail($candidate_id);
        $item->candidate_point   -= 1;
    
        if($item->candidate_point == -1){
        }else{
        $item->save();
        }
        $candidatepointnew=CoreCandidate::where('candidate_id', $candidate_id)->first();
        return  $candidatepointnew['candidate_point'];
    }

    public function sumStartingQuickCount($candidate_id){
        $item                    = CoreCandidate::findOrFail($candidate_id);
        $item->candidate_point   += 1;
        $item->save();

        // return redirect()->back();
        $candidatepointnew=CoreCandidate::where('candidate_id', $candidate_id)->first();
        return  $candidatepointnew['candidate_point'];

    }

    public function deleteQuickCount($quick_count_id){
        $item               = QuickCount::findOrFail($quick_count_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Data Quick Count Berhasil';
        }else{
            $msg = 'Hapus Data Quick Count Gagal';
        }

        return redirect('/quick-count')->with('msg',$msg);
    }
}
