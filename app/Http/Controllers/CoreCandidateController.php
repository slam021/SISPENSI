<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreCandidate;
use App\Models\CorePeriod;
use App\Models\CoreCandidatePartai;
use Illuminate\Support\Facades\Storage;

class CoreCandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    
        $corecandidate = CoreCandidate::where('core_candidate.data_state','=',0)->get();
        $corecandidatepartai = CoreCandidatePartai::where('core_candidate_partai.data_state','=',0)
        ->join('core_period', 'core_period.period_id', '=', 'core_candidate_partai.period_id')
        ->orderBy('partai_id', 'DESC')->take(1)->get();
        // dd($corecandidatepartai);

        // $oneyear = "14-07-2022";
        // $until = date('d-m-Y', strtotime('+2 year', strtotime($oneyear)));
        // dd($until);

        return view('content/CoreCandidate_view/ListCoreCandidate', compact('corecandidate', 'corecandidatepartai'));
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

    public function editCoreCandidate($candidate_id){
        $corecandidate = CoreCandidate::where('core_candidate.data_state','=',0)
        ->where('candidate_id', $candidate_id)->first();

        return view('content/CoreCandidate_view/FormEditCoreCandidate', compact('corecandidate'));
    }

    public function processEditCoreCandidate(Request $request){
        // $fields = $request->validate([
        //     'candidate_id'                  => 'required',
        //     'candidate_full_name'           => 'required',
        //     'candidate_nick_name'           => 'required',
        //     'candidate_address'             => 'required',
        //     'candidate_gender'              => 'required',
        //     'candidate_birth_place'         => 'required',
        //     'candidate_birth_date'          => 'required',
            // 'period_id'                     => 'required',
            // 'photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);

        $item  = CoreCandidate::findOrFail($request['candidate_id']);
        // print_r($item['candidate_photos']); exit;

        if ($request->hasFile('candidate_photos')) {
            $resorce               = $request->file('candidate_photos');
            $candidatephotosnew    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/candidate_photos', $candidatephotosnew);
            // print_r($candidatephotosnew); exit;

            Storage::delete('public/candidate_photos/'. $item->candidate_photos);
            
            $item->candidate_full_name         = $request['candidate_full_name'];
            $item->candidate_nick_name         = $request['candidate_nick_name'];
            $item->candidate_nik               = $request['candidate_nik'];
            $item->candidate_address           = $request['candidate_address'];
            $item->candidate_gender            = $request['candidate_gender'];
            $item->candidate_phone_number      = $request['candidate_phone_number'];
            $item->candidate_birth_place       = $request['candidate_birth_place'];
            $item->candidate_birth_date        = $request['candidate_birth_date'];
            // $item->period_id                   = $request['period_id'];
            $item->candidate_photos            = $candidatephotosnew;

            if($item->save()){
                $msg = 'Edit Data Kandidat Berhasil';
                return redirect('/candidate')->with('msg',$msg);
            }else{
                $msg = 'Edit Data Kandidat Gagal';
                return redirect('/candidate')->with('msg',$msg);
            }
        }else{ 
            $item->candidate_full_name         = $request['candidate_full_name'];
            $item->candidate_nick_name         = $request['candidate_nick_name'];
            $item->candidate_nik               = $request['candidate_nik'];
            $item->candidate_address           = $request['candidate_address'];
            $item->candidate_gender            = $request['candidate_gender'];
            $item->candidate_phone_number      = $request['candidate_phone_number'];
            $item->candidate_birth_place       = $request['candidate_birth_place'];
            $item->candidate_birth_date        = $request['candidate_birth_date'];
            // $item->period_id                   = $fields['period_id'];
    
            if($item->save()){
                $msg = 'Edit Data Kandidat Berhasil';
                return redirect('/candidate')->with('msg',$msg);
            }else{
                $msg = 'Edit Data Kandidat Gagal';
                return redirect('/candidate')->with('msg',$msg);
            }
        } 
    }

    public function editCoreCandidatePartai($candidate_id){
        $corecandidatepartai = CoreCandidatePartai::select('core_candidate_partai.*', 'core_period.*', 'core_candidate.*')
        ->join('core_period', 'core_period.period_id', '=', 'core_candidate_partai.period_id')
        ->join('core_candidate', 'core_candidate_partai.candidate_id', '=', 'core_candidate.candidate_id')
        ->where('core_candidate.data_state','=',0)
        ->where('core_candidate.candidate_id', $candidate_id)->first();
        // print_r($corecandidate); exit;
        $coreperiod = CorePeriod::where('data_state', '=', 0)->pluck('period_year', 'period_id');
        $nullcoreperiod = Session::get('period_id');

        return view('content/CoreCandidate_view/FormEditCoreCandidatePartai', compact('corecandidatepartai', 'coreperiod', 'nullcoreperiod'));
    }

    public function processEditCoreCandidatePartai(Request $request){
        $fields = $request->validate([
                    'partai_name'                   => 'required',
                    'partai_number'                 => 'required',
                    'candidate_number'              => 'required',
                    'period_id'                     => 'required',
                    'candidate_id'                  => 'required',
                ]);

            $data = array(
                        'partai_name'               => $fields['partai_name'], 
                        'partai_number'             => $fields['partai_number'], 
                        'candidate_number'          => $fields['candidate_number'], 
                        'period_id'                 => $fields['period_id'],
                        'candidate_id'              => $fields['candidate_id'],
                        'created_id'                => Auth::id(),
                        'created_at'                => date('Y-m-d'),
                    );

        if(CoreCandidatePartai::create($data)){
                    $msg = 'Edit Partai Berhasil';
                    return redirect('/candidate')->with('msg',$msg);
                } else {
                    $msg = 'Edit Partai Gagal';
                    return redirect('/candidate')->with('msg',$msg);
                }
    }

    public function downloadCoreCandidatePhotos($candidate_id){
        $corecandidate = CoreCandidate::findOrFail($candidate_id); 
        return response()->download(
            public_path('storage/candidate_photos/'.$corecandidate['candidate_photos']),
            $corecandidate['candidate_photos'],
        );
    }
}
