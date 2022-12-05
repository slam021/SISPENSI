<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Program;
use App\Models\ProgramSupport;
use App\Models\ProgramDistributionFund;
use App\Models\CoreLocation;
use App\Models\CorePeriod;
use App\Models\CoreCandidate;
use App\Models\CoreSupporter;
use App\Models\CoreTimses;
use App\Models\CoreTimsesMember;
use App\Models\User;
use App\Models\DocumentationProgram;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    
        $program = Program::select('program.*', 'core_location.location_name', 'core_period.period_name', 'core_candidate.candidate_full_name', 'core_timses.*')
        ->where('program.data_state','=',0)
        ->join('core_location', 'core_location.location_id', '=', 'program.location_id')
        ->join('core_period', 'core_period.period_id', '=', 'program.period_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'program.candidate_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->orderBy('program_id', 'ASC')     
        ->get();

        // print_r($program); exit;

        $programgender =array(
            1 => 'Laki-laki',
            2 => 'Perempuan',
        );
        return view('content/Program_view/ListProgram', compact('program', 'programgender'));
    }

    public function addProgram(Request $request){
        $program = Session::get('data_program');

        $corelocation = CoreLocation::where('data_state', '=', 0)->pluck('location_name', 'location_id');
        $nullcorelocation = Session::get('location_id');

        $coreperiod = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');
        $nullcoreperiod = Session::get('period_id');

        
        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');
        $nullcorecandidate = Session::get('candidate_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        $nullcoretimses  = Session::get('timses_id');
        return view('content/Program_view/FormAddProgram', compact('program', 'corelocation', 'nullcorelocation', 'coreperiod', 'nullcoreperiod', 'corecandidate', 'nullcorecandidate', 'coretimses', 'nullcoretimses'));
    }

    public function addElementsProgram(Request $request){
        $data_program[$request->name] = $request->value;

        $Program = Session::get('data_program');
        
        return redirect('/program/add');
    }

    public function addReset(){
        Session::forget('data_program');

        return redirect('/program/add');
    }

    public function processAddProgram(Request $request){
        // print_r($request->all()); exit;
        $fields = $request->validate([
            'candidate_id'              => 'required',
            'location_id'               => 'required',
            'timses_id'                 => 'required',
            'program_name'              => 'required',
            'program_description'       => 'required',
            'program_address'           => 'required',
            'program_date'              => 'required',
            'program_fund'              => 'required',
            'period_id'                 => 'required',
            // 'candidate_photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $data = array(
            'candidate_id'                   => $fields['candidate_id'], 
            'location_id'                    => $fields['location_id'], 
            'period_id'                      => $fields['period_id'], 
            'timses_id'                      => $fields['timses_id'], 
            'program_name'                   => $fields['program_name'], 
            'program_description'            => $fields['program_description'], 
            'program_address'                => $fields['program_address'], 
            'program_date'                   => $fields['program_date'], 
            'program_fund'                   => $fields['program_fund'], 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );

        if(Program::create($data)){
            $msg = 'Tambah Acara Berhasil';
            return redirect('/program/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Acara Gagal';
            return redirect('/program/add')->with('msg',$msg);
        }
    }

    public function editProgram($program_id){
        $program = Program::where('data_state','=',0)->where('program_id', $program_id)->first();
        // print_r($program); exit;
        $corelocation = CoreLocation::where('data_state', '=', 0)->pluck('location_name', 'location_id');
        $nullcorelocation = Session::get('location_id');

        $coreperiod = CorePeriod::where('data_state', '=', 0)->pluck('period_name', 'period_id');
        $nullcoreperiod = Session::get('period_id');

        $corecandidate = CoreCandidate::where('data_state', '=', 0)->pluck('candidate_full_name', 'candidate_id');
        $nullcorecandidate = Session::get('candidate_id');

        $coretimses = CoreTimses::where('data_state', '=', 0)->pluck('timses_name', 'timses_id');
        $nullcoretimses  = Session::get('timses_id');
        return view('content/Program_view/FormEditProgram', compact('program', 'corelocation', 'nullcorelocation', 'coreperiod', 'nullcoreperiod', 'corecandidate', 'nullcorecandidate', 'coretimses'));
    }

    public function processEditProgram(Request $request){
        $fields = $request->validate([
            'program_id'                => 'required',
            'candidate_id'              => 'required',
            'location_id'               => 'required',
            'period_id'                 => 'required',
            'timses_id'                 => 'required',
            'program_name'              => 'required',
            'program_description'       => 'required',
            'program_address'           => 'required',
            'program_date'              => 'required',
            'program_fund'              => 'required',
            // 'candidate_photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $item  = Program::findOrFail($fields['program_id']);

        $item->candidate_id                     = $fields['candidate_id'];
        $item->location_id                      = $fields['location_id'];
        $item->period_id                        = $fields['period_id'];
        $item->timses_id                        = $fields['timses_id'];
        $item->program_name                     = $fields['program_name'];
        $item->program_description              = $fields['program_description'];
        $item->program_address                  = $fields['program_address'];
        $item->program_date                     = $fields['program_date'];
        $item->program_fund                     = $request['program_fund'];

        if($item->save()){
            $msg = 'Edit Acara Berhasil';
            return redirect('/program')->with('msg',$msg);
        }else{
            $msg = 'Edit Acara Gagal';
            return redirect('/program')->with('msg',$msg);
        }
    }

    public function detailProgram($program_id, $timses_id){
        $program = Program::select('program.*', 'core_location.location_name', 'core_period.period_name', 'core_candidate.candidate_full_name', 'core_timses.*')
        ->where('program.data_state','=',0)
        ->join('core_location', 'core_location.location_id', '=', 'program.location_id')
        ->join('core_period', 'core_period.period_id', '=', 'program.period_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'program.candidate_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->where('program_id', $program_id)->first();

        $programsupport = ProgramSupport::select('core_supporter.*', 'program.*')
        ->join('core_supporter', 'core_supporter.supporter_id', '=', 'program_support.supporter_id' )
        ->join('program', 'program.program_id', '=', 'program_support.program_id' )
        ->where('program_support.data_state', '=', 0)
        ->where('program_support.program_id', $program_id)
        ->get();

        $membertimses = CoreTimsesMember::where('data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        return view('content/Program_view/FormDetailProgram', compact('program', 'programsupport', 'membertimses'));
    }

    public function distributionFundProgram($program_id, $timses_id){
        $program = Program::select('program.*', 'core_location.location_name', 'core_period.period_name', 'core_candidate.candidate_full_name', 'core_timses.*')
        ->where('program.data_state','=',0)
        ->join('core_location', 'core_location.location_id', '=', 'program.location_id')
        ->join('core_period', 'core_period.period_id', '=', 'program.period_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'program.candidate_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->where('program_id', $program_id)->first();

        $membertimses = CoreTimsesMember::where('data_state','=',0)
        // ->where('core_timses_member.user_id', '!=', null)
        ->where('core_timses_member.timses_id', $timses_id)
        ->pluck('timses_member_name', 'timses_member_id');
        $nullmembertimses = Session::get('timses_member_id');

        $systemuser = User::where('data_state','=',0)
        ->where('system_user.user_group_id', '=', 27)
        ->pluck('name', 'user_id');
        $nullsystemuser = Session::get('user_id');

        $programdistributionfund = ProgramDistributionFund::select('program_distribution_fund.*', 'core_timses.*', 'core_timses_member.*')
        ->where('program_distribution_fund.data_state','=',0)
        // ->join('system_user', 'system_user.user_id', '=', 'program_distribution_fund.user_id')
        ->join('core_timses_member', 'core_timses_member.timses_member_id', '=', 'program_distribution_fund.timses_member_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program_distribution_fund.timses_id')
        ->where('program_distribution_fund.program_id', $program_id)
        ->get();

        return view('content/Program_view/FormDistributionFundProgram', compact('program', 'membertimses', 'nullmembertimses', 'programdistributionfund', 'systemuser', 'nullsystemuser'));
    }

    public function processDistributionFundProgram(Request $request){
        // print_r($request->all()); exit;
        $program_id = $request['program_id'];
        $request->validate([
            'program_id'                         => 'required',
            'user_id'                            => 'required',
            'timses_id'                          => 'required',
            'timses_member_id'                   => 'required',
            'distribution_fund_nominal'          => 'required',
        ]);

        $data = array(
            'program_id'                         => $request['program_id'], 
            'user_id'                            => $request['user_id'], 
            'timses_id'                          => $request['timses_id'], 
            'timses_member_id'                   => $request['timses_member_id'], 
            'distribution_fund_nominal'          => $request['distribution_fund_nominal'], 
            'created_id'                         => Auth::id(),
            'created_at'                         => date('Y-m-d'),
        );
                // print_r($data); exit;
        if(ProgramDistributionFund::create($data)){
            $msg = 'Tambah Penyaluran Dana Berhasil';
            return redirect()->back()->with('msg',$msg);
        } else {
            $msg = 'Tambah Penyaluran Dana Gagal';
            return redirect()->back()->with('msg',$msg);
        }
    }
    

    // public function downloadProgramOrganizerPhotos($program_id){
    //     $program = Program::findOrFail($program_id); 
    //     return response()->download(
    //         public_path('storage/program_organizer_photos_ktp/'.$program['program_organizer_photos_ktp']),
    //         $program['program_organizer_photos_ktp'],
    //     );
    // }

    public function addProgramSupport($program_id){
        $programsupport = ProgramSupport::select('core_supporter.*', 'program_support.*')
        ->join('core_supporter', 'core_supporter.supporter_id', '=', 'program_support.supporter_id' )
        ->where('program_support.data_state', '=', 0)
        ->where('program_support.program_id', $program_id)
        ->get();

        $coresupporter = CoreSupporter::select(DB::raw("CONCAT(supporter_nik,' - ',supporter_full_name) AS nik"), 'supporter_id') 
        ->where('core_supporter.data_state', '=', 0)      
        ->pluck('nik', 'supporter_id');
        // $nullcorecandidate = Session::get('candidate_id');
        $supportergender =array(
            1 => 'Laki-laki',
            2 => 'Perempuan',
        );
        return view('content/Program_view/FormAddProgramSupport', compact('coresupporter', 'supportergender', 'programsupport'));
    }

    public function processAddProgramSupport(Request $request){
        
        $fields = $request->validate([
            'program_id'                => 'required',
            'supporter_id'              => 'required',
        ]);

        $data = array(
        'program_id'                    => $fields['program_id'], 
        'supporter_id'                  => $fields['supporter_id'], 
        'created_id'                    => Auth::id(),
        'created_at'                    => date('Y-m-d'),
        );
        // print_r($data); exit;

        if(ProgramSupport::create($data)){
            $msg = 'Tambah Pendukung Acara Berhasil';
            return redirect('/program/add-program-support/'.$request['program_id'])->with('msg',$msg);
        } else {
            $msg = 'Tambah Pendukung Acara Gagal';
            return redirect('/program/add-program-support/'.$request['program_id'])->with('msg',$msg);
        }
    
    }

    public function addCoreSupporterNew($program_support_id){
        $coresupporter = Session::get('data_coresupporter');

        return view('content/Program_view/FormAddProgramSupport', compact('coresupporter'));
    }

    public function processAddCoreSupporterNew(Request $request){
        $program_id = $request['program_id'];
        $fields = $request->validate([
            'supporter_full_name'           => 'required',
            'supporter_nik'                 => 'required',
            'supporter_address'             => 'required',
            'supporter_gender'              => 'required',
            'supporter_birth_place'         => 'required',
            'supporter_birth_date'          => 'required',
        ]);

        $data = array(
            'supporter_full_name'           => $fields['supporter_full_name'], 
            'supporter_nik'                 => $fields['supporter_nik'], 
            'supporter_address'             => $fields['supporter_address'], 
            'supporter_gender'              => $fields['supporter_gender'], 
            'supporter_birth_place'         => $fields['supporter_birth_place'], 
            'supporter_birth_date'          => $fields['supporter_birth_date'], 
            'created_id'          => Auth::id(),
            'created_at'          => date('Y-m-d'),
        );

        if(CoreSupporter::create($data)){

                $msg = 'Tambah Data Pendukung Baru Berhasil, 
                        Lanjutkan Tambah Pendukung Acara pada Dropdown';
                return redirect('/program/add-program-support/'.$program_id)->with('msg',$msg);
            } else {
                $msg = 'Tambah Data Pendukung Baru Gagal';
                return redirect('/program_id/add-program-support/'.$program_id)->with('msg',$msg);
        }
    }

    public function deleteProgramSupport($program_support_id){
        $item               = ProgramSupport::findOrFail($program_support_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Acara Berhasil';
        }else{
            $msg = 'Hapus Acara Gagal';
        }

        return redirect('/program')->with('msg',$msg);
    }

    public function closingProgram($program_id){
        $item                   = Program::findOrFail($program_id);
        $item->program_status   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Penutupan Acara Berhasil';
        }else{
            $msg = 'Penutupan Acara Gagal';
        }

        return redirect('/program')->with('msg',$msg);
    }

    public function documentationProgram($program_id){
        $documentation = Session::get('data_ducumentation');

        $documentation_file = DocumentationProgram::where('data_state', '=', 0)->get();

        return view('content/Program_view/FormDocumentationProgram', compact('documentation', 'documentation_file'));
    }

    public function processDocumentationProgram(Request $request){
        $program_id = $request['program_id'];

        if ($request->hasFile('program_documentation_file')) {
            $resorce            = $request->file('program_documentation_file');
            $program_documentation_file    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/program_documentation_file', $program_documentation_file);

        }else{
            $msg_err = "Dokumentasi Masih Kosong";
            return redirect('/program/documentation-program/'.$program_id)->with('msgerror',$msg_err);

        }
        
        $data = array(
            'program_id'                     => $request['program_id'],
            'program_documentation_file'     => $program_documentation_file, 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );

        if(DocumentationProgram::create($data)){

                $msg = 'Tambah Dokumentasi Berhasil';
                return redirect('/program/documentation-program/'.$program_id)->with('msg',$msg);
            } else {
                $msg = 'Tambah Dokumentasi Gagal';
                return redirect('/program/documentation-program/'.$program_id)->with('msg',$msg);
        }
    }

    public function downloadDocumentationProgram($program_documentation_id){
        $ducumentationprogram = DocumentationProgram::findOrFail($program_documentation_id); 
        return response()->download(
            public_path('storage/program_documentation_file/'.$ducumentationprogram['program_documentation_file']),
            $ducumentationprogram['program_documentation_file'],
        );
    }

    public function deleteDocumentationProgram($program_documentation_id){
        
        $item               = DocumentationProgram::findOrFail($program_documentation_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Dokumentasi Program Berhasil';
        }else{
            $msg = 'Hapus Dokumentasi Program Gagal';
        }

        return redirect()->back()->with('msg',$msg);
    }

    public function deleteProgram($program_id){
        $item               = Program::findOrFail($program_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Acara Berhasil';
        }else{
            $msg = 'Hapus Acara Gagal';
        }

        return redirect('/program')->with('msg',$msg);
    }
}