<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreCandidate;
use App\Models\CorePeriod;
use Illuminate\Support\Facades\Storage;

class CoreCandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    
        $corecandidate = CoreCandidate::select('core_candidate.*','core_period.*')
        ->join('core_period', 'core_period.period_id', '=', 'core_candidate.period_id')
        ->where('core_period.data_state','=',0)->get();
        
        
        return view('content/CoreCandidate_view/ListCoreCandidate', compact('corecandidate'));
    }

    public function addCoreCandidate(Request $request){
        $corecandidate  = Session::get('data_corecandidate');

        $coreperiod     = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');
        $nullcoreperiod = Session::get('period_id');

        return view('content/CoreCandidate_view/FormAddCoreCandidate', compact('corecandidate', 'coreperiod', 'nullcoreperiod'));
    }

    public function addElementsCoreCandidate(Request $request){
        $data_corecandidate[$request->name] = $request->value;

        $corecandidate = Session::get('data_corecandidate');
        
        return redirect('/candidate/add');
    }

    public function addReset(){
        Session::forget('data_corecandidate');

        return redirect('/candidate/add');
    }

    public function processAddCoreCandidate(Request $request){
        $fields = $request->validate([
            'candidate_full_name'           => 'required',
            'candidate_nick_name'           => 'required',
            'candidate_nik'                 => 'required',
            'candidate_address'             => 'required',
            'candidate_gender'              => 'required',
            'candidate_phone_number'        => 'required',
            'candidate_birth_place'         => 'required',
            'candidate_birth_date'          => 'required',
            'period_id'                     => 'required',
            // 'candidate_photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('candidate_photos')) {
            $resorce            = $request->file('candidate_photos');
            $candidatephotos    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/candidate_photos', $candidatephotos);

        }else{
            $msg_err = "Foto Kandidat Masih Kosong";
            return redirect('/candidate/add')->with('msgerror',$msg_err);
        }
        
        $data = array(
            'candidate_full_name'           => $fields['candidate_full_name'], 
            'candidate_nick_name'           => $fields['candidate_nick_name'], 
            'candidate_nik'                 => $fields['candidate_nik'], 
            'candidate_address'             => $fields['candidate_address'], 
            'candidate_gender'              => $fields['candidate_gender'], 
            'candidate_phone_number'        => $fields['candidate_phone_number'], 
            'candidate_birth_place'         => $fields['candidate_birth_place'], 
            'candidate_birth_date'          => $fields['candidate_birth_date'], 
            'period_id'                     => $fields['period_id'], 
            'candidate_photos'              => $candidatephotos, 
            'created_id'                    => Auth::id(),
            'created_at'                    => date('Y-m-d'),
        );

        if(CoreCandidate::create($data)){
            $msg = 'Tambah Data Kandidat Berhasil';
            return redirect('/candidate/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Kandidat Gagal';
            return redirect('/candidate/add')->with('msg',$msg);
        }
    }

    public function editCoreCandidate($candidate_id){
        $corecandidate = CoreCandidate::select('core_candidate.*', 'core_period.*')
        ->join('core_period', 'core_period.period_id', '=', 'core_candidate.period_id')
        ->where('core_candidate.data_state','=',0)
        ->where('candidate_id', $candidate_id)->first();
        // print_r($corecandidate); exit;
        $coreperiod = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');

        return view('content/CoreCandidate_view/FormEditCoreCandidate', compact('corecandidate', 'coreperiod'));
    }

    public function processEditCoreCandidate(Request $request){
        $fields = $request->validate([
            'candidate_id'                  => 'required',
            'candidate_full_name'           => 'required',
            'candidate_nick_name'           => 'required',
            'candidate_nik'                 => 'required',
            'candidate_address'             => 'required',
            'candidate_gender'              => 'required',
            'candidate_phone_number'        => 'required',
            'candidate_birth_place'         => 'required',
            'candidate_birth_date'          => 'required',
            'period_id'                     => 'required',
            // 'photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $item  = CoreCandidate::findOrFail($fields['candidate_id']);
        // print_r($item['candidate_photos']); exit;

        if ($request->hasFile('candidate_photos')) {
            $resorce               = $request->file('candidate_photos');
            $candidatephotosnew    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/candidate_photos', $candidatephotosnew);
            // print_r($candidatephotosnew); exit;

            Storage::delete('public/candidate_photos/'. $item->candidate_photos);
            
            $item->candidate_full_name         = $fields['candidate_full_name'];
            $item->candidate_nick_name         = $fields['candidate_nick_name'];
            $item->candidate_nik               = $fields['candidate_nik'];
            $item->candidate_address           = $fields['candidate_address'];
            $item->candidate_gender            = $fields['candidate_gender'];
            $item->candidate_phone_number      = $fields['candidate_phone_number'];
            $item->candidate_birth_place       = $fields['candidate_birth_place'];
            $item->candidate_birth_date        = $fields['candidate_birth_date'];
            $item->period_id                   = $fields['period_id'];
            $item->candidate_photos            = $candidatephotosnew;

            if($item->save()){
                $msg = 'Edit Data Kandidat Berhasil';
                return redirect('/candidate')->with('msg',$msg);
            }else{
                $msg = 'Edit Data Kandidat Gagal';
                return redirect('/candidate')->with('msg',$msg);
            }
        }else{ 
            $item->candidate_full_name         = $fields['candidate_full_name'];
            $item->candidate_nick_name         = $fields['candidate_nick_name'];
            $item->candidate_nik               = $fields['candidate_nik'];
            $item->candidate_address           = $fields['candidate_address'];
            $item->candidate_gender            = $fields['candidate_gender'];
            $item->candidate_phone_number      = $fields['candidate_phone_number'];
            $item->candidate_birth_place       = $fields['candidate_birth_place'];
            $item->candidate_birth_date        = $fields['candidate_birth_date'];
            $item->period_id                   = $fields['period_id'];
    
            if($item->save()){
                $msg = 'Edit Data Kandidat Berhasil';
                return redirect('/candidate')->with('msg',$msg);
            }else{
                $msg = 'Edit Data Kandidat Gagal';
                return redirect('/candidate')->with('msg',$msg);
            }
        } 
    }

    public function detailCoreCandidate($candidate_id){
        $corecandidate = CoreCandidate::select('core_candidate.*', 'core_period.*')
        ->join('core_period', 'core_period.period_id', '=', 'core_candidate.period_id')
        ->where('core_candidate.data_state','=',0)
        ->where('candidate_id', $candidate_id)->first();
        // print_r($corecandidate); exit;

        return view('content/CoreCandidate_view/FormDetailCoreCandidate', compact('corecandidate'));
    }

    public function downloadCoreCandidatePhotos($candidate_id){
        $corecandidate = CoreCandidate::findOrFail($candidate_id); 
        return response()->download(
            public_path('storage/candidate_photos/'.$corecandidate['candidate_photos']),
            $corecandidate['candidate_photos'],
        );
    }
    
    public function deleteCoreCandidate($candidate_id){
        $item               = CoreCandidate::findOrFail($candidate_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Data Kandidat Berhasil';
        }else{
            $msg = 'Hapus Data Kandidat Gagal';
        }

        return redirect('/candidate')->with('msg',$msg);
    }
}
