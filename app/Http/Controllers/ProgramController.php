<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Program;
use App\Models\ProgramSupport;
use App\Models\ProgramDistributionFund;
use App\Models\ProgramTimsesActivity;
use App\Models\CoreLocation;
use App\Models\CorePeriod;
use App\Models\CoreCandidate;
use App\Models\CoreSupporter;
use App\Models\CoreTimses;
use App\Models\CoreTimsesMember;
use App\Models\User;
use App\Models\FinancialFlow;
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
    
        if(!Session::get('start_date')){
            $start_date     = date('Y-m-d');
        }else{
            $start_date = Session::get('start_date');
        }
        if(!Session::get('end_date')){
            $end_date     = date('Y-m-d');
        }else{
            $end_date = Session::get('end_date');
        } 

        $coretimsesmember = CoreTimsesMember::select('timses_member_id', 'timses_member_name')
        ->where('data_state', '=', 0)
        ->orderBy('timses_member_name', 'ASC')
        ->get()
        ->pluck('timses_member_name', 'timses_member_id');
        
        $program = Program::select('program.*')
        ->where('program.data_state','=',0)
        ->where('program_date','>=',$start_date)
        ->where('program_date','<=',$end_date);    
    
        $timses_member_id = Session::get('timses_member_id');

        if($timses_member_id||$timses_member_id!=null||$timses_member_id!=''){
            $program   = $program->where('timses_member_id', $timses_member_id);
        }
    
        $program   = $program->get();   
        // print_r($coretimses); exit;

        $programgender =array(
            1 => 'Laki-laki',
            2 => 'Perempuan',
        );
        return view('content/Program_view/ListProgram', compact('program', 'programgender', 'timses_member_id', 'coretimsesmember', 'start_date', 'end_date'));
    }

    public function filterProgram(Request $request)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $timses_member_id = $request->timses_member_id;

        // dd( $timses_member_id);

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('timses_member_id', $timses_member_id);

        return redirect('/program');
    }

    public function filterResetProgram()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('timses_member_id');

        return redirect('/program');
    }

    public function addProgram(Request $request){
        $program = Session::get('data_program');

        $corecandidate = CoreCandidate::where('candidate_id', '=', 1)
        ->where('data_state','=',0)->get();

        $coretimsesmember = CoreTimsesMember::where('data_state', '=', 0)
        ->orderBy('timses_member_name', 'ASC')
        ->pluck('timses_member_name', 'timses_member_id');
        
        $nullcoretimses  = Session::get('timses_member_id');
        return view('content/Program_view/FormAddProgram', compact('program', 'coretimsesmember', 'nullcoretimses', 'corecandidate'));
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
        if($request->program_organizer == 1){
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;
            // dd($candidate_id);
        }else{
            $candidate_id = null;
        }

        $fields = $request->validate([
            // 'candidate_id'              => 'required',
            'program_organizer'         => 'required',
            'program_name'              => 'required',
            'program_description'       => 'required',
            'program_address'           => 'required',
            'program_date'              => 'required',
            'program_fund'              => 'required',
            // 'candidate_photos'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $data = array(
            // 'location_id'                    => $fields['location_id'], 
            // 'period_id'                      => $fields['period_id'], 
            'candidate_id'                   => $candidate_id, 
            'timses_member_id'               => $request['timses_member_id'], 
            'program_organizer'              => $fields['program_organizer'], 
            'program_name'                   => $fields['program_name'], 
            'program_description'            => $fields['program_description'], 
            'program_address'                => $fields['program_address'], 
            'program_date'                   => $fields['program_date'], 
            'program_fund'                   => $fields['program_fund'], 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );
        // dd($data);                  

        if($request->program_organizer == 1){
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

            $timses_member_id = null;
        }else{
            $candidate_id = null;
            $timses_member_id = $request['timses_member_id'];
        }

        $program_id = Program::orderBy('program_id', 'DESC')->first()->program_id;

        $data_financial_flow = [
            'program_id'                     => $program_id + 1,
            'financial_category_id'          => 8,
            'financial_category_type'        => 2,
            'candidate_id'                   => $candidate_id, 
            'timses_member_id'               => $timses_member_id, 
            'financial_flow_nominal'         => $request['program_fund'],
            'financial_flow_description'     => $request['program_description'],
            'financial_flow_date'            => $request['program_date'],
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        ];

        // dd($data);

        // $core_candidate_id = CoreCandidate::select('candidate_id')
        // ->where('data_state','=',0)->first()->candidate_id;
        if($request->program_organizer == 1){
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

            $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
            $last_balance_candidate->last_balance -= $request['program_fund'];
            $last_balance_candidate->save();

        }else{
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

            $timses_member_id = $request['timses_member_id'];

            $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
            $last_balance_candidate->last_balance -= $request['program_fund'];
            $last_balance_candidate->save();

            $last_balance_timses = CoreTimsesMember::findOrFail($request['timses_member_id']);
            $last_balance_timses->last_balance -= $request['program_fund'];
            $last_balance_timses->save();
        }
       

        if(Program::create($data)){
            FinancialFlow::create($data_financial_flow);

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

        $corecandidate = CoreCandidate::select('candidate_id')
        ->where('data_state','=',0)->first()->candidate_id;
// dd($corecandidate);

        $coretimsesmember = CoreTimsesMember::where('data_state', '=', 0)
        ->orderBy('timses_member_name', 'ASC')
        ->pluck('timses_member_name', 'timses_member_id');
        
        $coretimsesmember2 = CoreTimsesMember::where('data_state', '=', 0)->get();
        $nullcoretimses  = Program::where('data_state', '=', 0)->where('program_id', $program_id)->first();
        return view('content/Program_view/FormEditProgram', compact('program', 'coretimsesmember', 'nullcoretimses', 'coretimsesmember2', 'corecandidate'));
    }

    public function processEditProgram(Request $request){
        // // print_r($request->all());exit;
        if($request->program_organizer == 1){
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;
            // dd($candidate_id);
        }else{
            $candidate_id = null;
        }

        $fields = $request->validate([
            'program_id'                => 'required',
            'program_organizer'         => 'required',
            'program_name'              => 'required',
            'program_description'       => 'required',
            'program_address'           => 'required',
            'program_date'              => 'required',
            'program_fund'              => 'required',

        ]);

        $item  = Program::findOrFail($fields['program_id']);

        $item->candidate_id                     = $candidate_id;
        $item->timses_member_id                 = $request['timses_member_id'];
        $item->program_organizer                = $fields['program_organizer'];
        $item->program_name                     = $fields['program_name'];
        $item->program_description              = $fields['program_description'];
        $item->program_address                  = $fields['program_address'];
        $item->program_date                     = $fields['program_date'];
        $item->program_fund                     = $request['program_fund'];
        // print_r($item);exit;

        if($item->save()){
            $msg = 'Edit Acara Berhasil';
            return redirect('/program')->with('msg',$msg);
        }else{
            $msg = 'Edit Acara Gagal';
            return redirect('/program')->with('msg',$msg);
        }
    }

    public function detailProgram($program_id){
        $program = Program::select('program.*')
        ->where('program.data_state','=',0)
        ->where('program_id', $program_id)->first();

        // $programsupport = ProgramSupport::select('core_supporter.*', 'program.*')
        // ->join('core_supporter', 'core_supporter.supporter_id', '=', 'program_support.supporter_id' )
        // ->join('program', 'program.program_id', '=', 'program_support.program_id' )
        // ->where('program_support.data_state', '=', 0)
        // ->where('program_support.program_id', $program_id)
        // ->get();

        // print_r($program); exit;

        // $membertimses = CoreTimsesMember::select('core_timses_member.*', 'core_timses.*')
        // ->where('core_timses_member.data_state','=',0)
        // ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        // ->where('core_timses.program_id', $program_id)
        // ->get();

        return view('content/Program_view/FormDetailProgram', compact('program'));
    }

    public function getTimsesName($program_id){
        $coretimsesmember = CoreTimsesMember::select('timses_member_name')
        ->where('timses_member_id', $program_id)
        ->where('data_state', '=', 0)->first();

        if(empty($coretimsesmember['timses_member_name'])){
            "-";
        }else{
            return  $coretimsesmember['timses_member_name'];
        }

    }

    // public function getCandidateID($candidate_id){
    //     $corecandidate = CoreCandidate::where('candidate_id', $candidate_id)
    //     ->where('data_state','=',0)->first();

    //     return  $corecandidate['candidate_id'];
    // }

    public function distributionFundProgram($program_id){
        $program = Program::select('program.*', 'core_location.location_name', 'core_period.period_name', 'core_candidate.candidate_full_name', 'core_timses.*')
        ->where('program.data_state','=',0)
        ->join('core_location', 'core_location.location_id', '=', 'program.location_id')
        ->join('core_period', 'core_period.period_id', '=', 'program.period_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'program.candidate_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->where('program_id', $program_id)->first();

        $membertimses = CoreTimsesMember::where('data_state','=',0)
        ->where('core_timses_member.user_id', '!=', null)
        ->where('core_timses_member.timses_id', $program['timses_id'])
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
            'timses_id'                          => 'required',
            'timses_member_id'                   => 'required',
            'distribution_fund_nominal'          => 'required',
        ]);

        $data = array(
            'program_id'                         => $request['program_id'],  
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

    public function getUserAkun($timses_member_id){
        $membertimses = CoreTimsesMember::join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_member_id', $timses_member_id)
        ->first();

        if (empty($membertimses)) {
            return '';
        } else {
            return $membertimses['name'];
        }
    }

    public function getAkunName($user_id){
        $data = User::where('user_id',$user_id)
        ->first();

        return $data['name'];
    }

    public function getNameTimses($timses_id){
        $data = CoreTimses::where('timses_id',$timses_id)
        ->first();

        return $data['timses_name'];
    }
    

    public function editDistributionFundProgram($program_id, $timses_id, $distribution_fund_id){
        
        $membertimses = CoreTimsesMember::where('data_state','=',0)
        ->where('core_timses_member.user_id', '!=', null)
        ->where('core_timses_member.timses_id', $timses_id)
        ->pluck('timses_member_name', 'timses_member_id');
        $nullmembertimses = Session::get('timses_member_id');

        $systemuser = User::where('data_state','=',0)
        ->where('system_user.user_group_id', '=', 27)
        ->pluck('name', 'user_id');
        $nullsystemuser = Session::get('user_id');

        $programdistributionfund = ProgramDistributionFund::select('core_timses_member.*', 'program_distribution_fund.*')
        ->where('program_distribution_fund.data_state','=',0)
        // ->join('system_user', 'system_user.user_id', '=', 'program_distribution_fund.user_id')
        ->join('core_timses_member', 'core_timses_member.timses_member_id', '=', 'program_distribution_fund.timses_member_id')
        // ->where('program_distribution_fund.program_id', $program_id)
        // ->where('program_distribution_fund.timses_id', $timses_id)
        ->where('program_distribution_fund.distribution_fund_id', $distribution_fund_id)
        ->first();
        // dd($programdistributionfund);


        return view('content/Program_view/FormEditDistributionFundProgram', compact('membertimses', 'nullmembertimses', 'programdistributionfund', 'systemuser', 'nullsystemuser'));
    }

    public function processEditDistributionFundProgram(Request $request){
        $request->validate([
            'distribution_fund_id'               => 'required',
            'program_id'                         => 'required',
            'timses_id'                          => 'required',
            'timses_member_id'                   => 'required',
            'distribution_fund_nominal'          => 'required',
        ]);

        $item  = ProgramDistributionFund::findOrFail($request['distribution_fund_id']);

        $item->program_id                       = $request['program_id'];
        $item->timses_id                        = $request['timses_id'];
        $item->timses_member_id                 = $request['timses_member_id'];
        $item->distribution_fund_nominal        = $request['distribution_fund_nominal'];
        // print_r($item);exit;

        if($item->save()){
            $msg = 'Edit Penyaluran Dana Berhasil';
            return redirect('program/distribution-fund/'.$request['program_id'])->with('msg',$msg);
        } else {
            $msg = 'Edit Penyaluran Dana Gagal';
            return redirect('program/distribution-fund/'.$request['program_id'])->with('msg',$msg);

        }
    }

    // public function downloadProgramOrganizerPhotos($program_id){
    //     $program = Program::findOrFail($program_id); 
    //     return response()->download(
    //         public_path('storage/program_organizer_photos_ktp/'.$program['program_organizer_photos_ktp']),
    //         $program['program_organizer_photos_ktp'],
    //     );
    // }

    public function detailDistributionFundProgram($program_id, $distribution_fund_id){
        
        $program = Program::select('program.*', 'core_location.location_name', 'core_period.period_name', 'core_candidate.candidate_full_name', 'core_timses.*')
        ->where('program.data_state','=',0)
        ->join('core_location', 'core_location.location_id', '=', 'program.location_id')
        ->join('core_period', 'core_period.period_id', '=', 'program.period_id')
        ->join('core_candidate', 'core_candidate.candidate_id', '=', 'program.candidate_id')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->where('program_id', $program_id)->first();

        $membertimses = CoreTimsesMember::where('data_state','=',0)
        ->where('core_timses_member.user_id', '!=', null)
        ->where('core_timses_member.timses_id', $program['timses_id'])
        ->first();

        
        $systemuser = User::where('data_state','=',0)
        ->where('system_user.user_group_id', '=', 27)
        ->first();

        $programdistributionfund = ProgramDistributionFund::select('core_timses_member.*', 'program_distribution_fund.*')
        ->where('program_distribution_fund.data_state','=',0)
        // ->join('system_user', 'system_user.user_id', '=', 'program_distribution_fund.user_id')
        ->join('core_timses_member', 'core_timses_member.timses_member_id', '=', 'program_distribution_fund.timses_member_id')
        // ->where('program_distribution_fund.program_id', $program_id)
        // ->where('program_distribution_fund.timses_id', $timses_id)
        ->where('program_distribution_fund.distribution_fund_id', $distribution_fund_id)
        ->first();

        $programtimsesactivity = ProgramTimsesActivity::where('data_state', '=', 0)
        ->where('program_timses_activity.distribution_fund_id', $distribution_fund_id)
        ->get();

        return view('content/Program_view/FormDetailDistributionFundProgram', compact('program', 'membertimses', 'programdistributionfund', 'systemuser', 'programtimsesactivity'));
    }

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

        return redirect()->back()->with('msg',$msg);
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

        $documentation_file = DocumentationProgram::where('data_state', '=', 0)
        ->where('program_documentation.program_id', $program_id)
        ->get();

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