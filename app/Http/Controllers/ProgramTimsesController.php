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



class ProgramTimsesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        
        $user_group_login = User::where('data_state', 0)
        ->where('system_user.user_id', Auth::id())->first()->user_group_id;
    
        // if(!Session::get('start_date')){
        //     $start_date     = date('Y-m-d');
        // }else{
        //     $start_date = Session::get('start_date');
        // }
        // if(!Session::get('end_date')){
        //     $end_date     = date('Y-m-d');
        // }else{
        //     $end_date = Session::get('end_date');
        // } 

        $coretimses = CoreTimses::where('core_timses.data_state', '=', 0)
        ->orderBy('core_timses.timses_name', 'ASC')
        ->get()
        ->pluck('core_timses.timses_name', 'timses_id');
        
        $program = Program::select('program.*')
        ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        ->where('program.data_state','=',0)
        ->where('core_timses.user_id', Auth::id());
        // ->where('program_date','>=',$start_date)
        // ->where('program_date','<=',$end_date);
    
        // $timses_id = Session::get('timses_id');

        // if($timses_id||$timses_id!=null||$timses_id!=''){
        //     $program   = $program->where('timses_id', $timses_id);
        // }
    
        $program   = $program->get();   
        // print_r($coretimses); exit;

        $programgender =array(
            1 => 'Laki-laki',
            2 => 'Perempuan',
        );
        return view('content/ProgramTimses_view/ListProgram', compact('user_group_login', 'program', 'programgender', 'coretimses'));
    }

    public function filterProgram(Request $request)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $timses_id = $request->timses_id;

        // dd( $timses_id);

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('timses_id', $timses_id);

        return redirect('/program-timses');
    }

    public function filterResetProgram()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('timses_id');

        return redirect('/program-timses');
    }

    public function addProgram(Request $request){
        $program = Session::get('data_program');

        $corecandidate = CoreCandidate::where('candidate_id', '=', 1)
        ->where('data_state','=',0)->get();

        // $coretimses = CoreTimses::where('data_state', '=', 0)
        // ->orderBy('timses_name', 'ASC')
        // ->pluck('timses_name', 'timses_id');

        $coretimses = CoreTimses::where('core_timses.data_state', '=', 0)
        ->where('core_timses.user_id', Auth::id())
        ->first()->timses_id;

        
        $nullcoretimses  = Session::get('timses_id');
        return view('content/ProgramTimses_view/FormAddProgram', compact('program', 'coretimses', 'nullcoretimses', 'corecandidate'));
    }


    public function addElementsProgram(Request $request){
        $data_program[$request->name] = $request->value;

        $Program = Session::get('data_program');
        
        return redirect('/program-timses/add');
    }

    public function addReset(){
        Session::forget('data_program');

        return redirect('/program-timses/add');
    }

    public function processAddProgram(Request $request){
        // print_r($request->all()); exit;

        $fields = $request->validate([
            // 'candidate_id'              => 'required',
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
            // 'candidate_id'                   => $candidate_id, 
            'timses_id'                      => (int)$request['timses_id'], 
            'program_organizer'              => (int)$request['program_organizer'], 
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

            $timses_id = null;
        }else{
            $candidate_id = null;
            $timses_id = $request['timses_id'];
        }

        $program_id = Program::orderBy('program_id', 'DESC')->first()->program_id;

        $data_financial_flow = [
            'program_id'                     => $program_id + 1,
            'financial_category_id'          => 8,
            'financial_category_type'        => 2,
            'candidate_id'                   => $candidate_id, 
            'timses_id'                      => (int)$request['timses_id'], 
            'financial_flow_nominal'         => $request['program_fund'],
            'financial_flow_description'     => $request['program_description'],
            'financial_flow_date'            => $request['program_date'],
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        ];

        // dd($data_financial_flow);

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

            $timses_id = $request['timses_id'];

            $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
            $last_balance_candidate->last_balance -= $request['program_fund'];
            $last_balance_candidate->save();

            $last_balance_timses = CoreTimses::findOrFail($request['timses_id']);
            $last_balance_timses->last_balance -= $request['program_fund'];
            $last_balance_timses->save();
        }

        if(Program::create($data)){
            FinancialFlow::create($data_financial_flow);

            $msg = 'Tambah Acara Berhasil';
            return redirect('/program-timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Acara Gagal';
            return redirect('/program-timses/add')->with('msg',$msg);
        }
    }

    public function editProgram($program_id){
        $program = Program::where('data_state','=',0)->where('program_id', $program_id)->first();
        // print_r($program); exit;

        $corecandidate = CoreCandidate::select('candidate_id')
        ->where('data_state','=',0)->first()->candidate_id;
// dd($corecandidate);

        $coretimses = CoreTimses::where('data_state', '=', 0)
        ->orderBy('timses_name', 'ASC')
        ->pluck('timses_name', 'timses_id');
        
        $coretimses2 = CoreTimses::where('data_state', '=', 0)->get();
        $nullcoretimses  = Program::where('data_state', '=', 0)->where('program_id', $program_id)->first();
        return view('content/ProgramTimses_view/FormEditProgram', compact('program', 'coretimses', 'nullcoretimses', 'coretimses2', 'corecandidate'));
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
        $item->timses_id                        = $request['timses_id'];
        $item->program_organizer                = $fields['program_organizer'];
        $item->program_name                     = $fields['program_name'];
        $item->program_description              = $fields['program_description'];
        $item->program_address                  = $fields['program_address'];
        $item->program_date                     = $fields['program_date'];
        $item->program_fund                     = $request['program_fund'];
        // print_r($item);exit;

        if($item->save()){
            $msg = 'Edit Acara Berhasil';
            return redirect('/program-timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Acara Gagal';
            return redirect('/program-timses')->with('msg',$msg);
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

        // $membertimses = CoreTimsesMember::select('core_timses.*', 'core_timses.*')
        // ->where('core_timses.data_state','=',0)
        // ->join('core_timses', 'core_timses.timses_id', '=', 'program.timses_id')
        // ->where('core_timses.program_id', $program_id)
        // ->get();

        return view('content/ProgramTimses_view/FormDetailProgram', compact('program'));
    }

    public function getTimsesName($program_id){
        $coretimses = CoreTimses::select('timses_name')
        ->where('timses_id', $program_id)
        ->where('data_state', '=', 0)->first();

        if(empty($coretimses['timses_name'])){
            "-";
        }else{
            return  $coretimses['timses_name'];
        }

    }

    // public function getNameTimses($timses_id){
    //     $data = CoreTimses::where('timses_id',$timses_id)
    //     ->first();

    //     return $data['timses_name'];
    // }

    // public function downloadProgramOrganizerPhotos($program_id){
    //     $program = Program::findOrFail($program_id); 
    //     return response()->download(
    //         public_path('storage/program_organizer_photos_ktp/'.$program['program_organizer_photos_ktp']),
    //         $program['program_organizer_photos_ktp'],
    //     );
    // }

    public function documentationProgram($program_id){
        $documentation = Session::get('data_ducumentation');

        $documentation_file = DocumentationProgram::where('data_state', '=', 0)
        ->where('program_documentation.program_id', $program_id)
        ->get();

        return view('content/ProgramTimses_view/FormDocumentationProgram', compact('documentation', 'documentation_file'));
    }

    public function processDocumentationProgram(Request $request){
        $program_id = $request['program_id'];

        if ($request->hasFile('program_documentation_file')) {
            $resorce            = $request->file('program_documentation_file');
            $program_documentation_file    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/program_documentation_file', $program_documentation_file);

        }else{
            $msg_err = "Dokumentasi Masih Kosong";
            return redirect('/program-timses/documentation-program/'.$program_id)->with('msgerror',$msg_err);

        }
        
        $data = array(
            'program_id'                     => $request['program_id'],
            'program_documentation_file'     => $program_documentation_file, 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );

        if(DocumentationProgram::create($data)){

                $msg = 'Tambah Dokumentasi Berhasil';
                return redirect('/program-timses/documentation-program/'.$program_id)->with('msg',$msg);
            } else {
                $msg = 'Tambah Dokumentasi Gagal';
                return redirect('/program-timses/documentation-program/'.$program_id)->with('msg',$msg);
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

        return redirect('/program-timses')->with('msg',$msg);
    }
}