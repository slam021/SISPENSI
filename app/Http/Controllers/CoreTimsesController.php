<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreTimses;
use App\Models\CoreTimsesMember;
use App\Models\CoreTimsesMemberKTP;
use App\Models\SystemUserGroup;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class CoreTimsesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $coretimses = CoreTimses::where('core_timses.data_state','=',0)
        ->orderBy('core_timses.timses_name', 'ASC')
        ->get();
// dd($coretimsesmember);

        return view('content/CoreTimses_view/ListCoreTimses', compact('coretimses'));
    }

    public function addCoreTimses(Request $request){
        $coretimses = Session::get('data_coretimses');
        return view('content/CoreTimses_view/FormAddCoreTimses', compact('coretimses'));
    }

    public function processAddCoreTimses(Request $request){
        $fields = $request->validate([
            'timses_name'             => 'required',
            // 'timses_partai'           => 'required',
        ]);

        $data = array(
            'timses_name'             => $fields['timses_name'], 
            // 'timses_partai'           => $fields['timses_partai'], 
            'created_id'              => Auth::id(),
            'created_at'              => date('Y-m-d'),
        );

        if(CoreTimses::create($data)){
            $msg = 'Tambah Data Timses Berhasil';
            return redirect('/timses/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Timses Gagal';
            return redirect('/timses/add')->with('msg',$msg);
        }
    }

    public function editCoreTimses($timses_id){
        $coretimses = CoreTimses::where('data_state', '=', 0)->first();
        return view('content/CoreTimses_view/FormEditCoreTimses', compact('coretimses'));
    }

    public function processEditCoreTimses(Request $request){
        $fields = $request->validate([
            'timses_id'               => 'required',
            'timses_name'             => 'required',
            // 'timses_partai'           => 'required',
        ]);

        $item  = CoreTimses::findOrFail($fields['timses_id']);
        $item->timses_name            = $fields['timses_name'];
        // $item->timses_partai          = $fields['timses_partai'];

        if($item->save()){
            $msg = 'Edit Data Timses Berhasil';
            return redirect('/timses')->with('msg',$msg);
        }else{
            $msg = 'Edit Data Timses Gagal';
            return redirect('/timses')->with('msg',$msg);
        }
    }

    public function getAkunName($user_id){
        $data = User::where('user_id',$user_id)
        ->first();

        if(empty($data)){
            return "_";
        }else{
            return $data['name'];
        }
    }

    public function addMemberCoreTimses($timses_id, Request $request){
        $coretimsesmember = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        $systemuser = User::where('data_state','=',0)->max('user_id');

        // print_r($systemuser); exit;

        $systemusergroup = SystemUserGroup::where('data_state','=',0)->pluck('user_group_name', 'user_group_id');
        $nullsystemusergoup = Session::get('user_group_id');
        return view('content/CoreTimses_view/FormAddMemberCoreTimses', compact('coretimsesmember', 'systemusergroup', 'nullsystemusergoup', 'systemuser'));
    }

    public function processAddMemberCoreTimses(Request $request){
        $fields = $request->validate([
            'timses_id'                      => 'required',
            'timses_member_name'             => 'required',
            'timses_member_nik'              => 'required',
            'timses_member_address'          => 'required',
            'timses_member_phone'            => 'required',
            'timses_member_gender'           => 'required',
        ]);

        $data = array(
            'timses_id'                      => $fields['timses_id'], 
            'timses_member_name'             => $fields['timses_member_name'], 
            'timses_member_nik'              => $fields['timses_member_nik'], 
            'timses_member_address'          => $fields['timses_member_address'], 
            'timses_member_phone'            => $fields['timses_member_phone'], 
            'timses_member_gender'           => $fields['timses_member_gender'], 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );

        if(CoreTimsesMember::create($data)){
            $msg = 'Tambah Data Anggota Timses Berhasil';
            return redirect('/timses/add-member/'.$fields['timses_id'])->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Anggota Timses Gagal';
            return redirect('/timses/add-member/'.$fields['timses_id'])->with('msg',$msg);
        }
    }
    public function addAccountMemberCoreTimses($timses_member_id){
        $systemuser = User::where('data_state','=',0)->max('user_id');
        $systemusergroup = SystemUserGroup::where('data_state','=',0)->pluck('user_group_name', 'user_group_id');
        $nullsystemusergoup = Session::get('user_group_id');

        return view('content/CoreTimses_view/FormAddAccountCoreTimsesMember', compact('systemusergroup', 'nullsystemusergoup', 'systemuser'));
    }

    public function processAddAccountMemberCoreTimses(Request $request){
        // $timses_id = $request['timses_id'];
        $fields = $request->validate([
            'name'                      => 'required',
            'password'                  => 'required',
            'user_group_id'             => 'required',
            'user_id'                   => 'required',
            'timses_member_id'          => 'required'
        ]);

        $user = User::create([
            'name'                  => $fields['name'],
            'password'              => Hash::make($fields['password']),
            'user_group_id'         => $fields['user_group_id'],
        ]);

        if($fields['timses_member_id']){
        $item  = CoreTimsesMember::findOrFail($fields['timses_member_id']);
        $item -> user_id = $fields['user_id'];
        $item ->save();
        }
        // print_r($request->all()); exit;

        $msg = 'Tambah System User Berhasil';
        return back()->with('msg',$msg);
    }

    public function addMemberCoreTimsesKTP($timses_member_id, Request $request){
        $timses_member_id = $request['timses_member_id'];

        $timses_member_ktp = CoreTimsesMemberKTP::where('data_state', '=', 0)
        ->where('core_timses_member_ktp.timses_member_id', $timses_member_id)
        ->get();

        // dd($timses_member_ktp);

        return view('content/CoreTimses_view/FormMemberCoreTimsesKTP', compact('timses_member_ktp'));
    }

    public function processAddMemberCoreTimsesKTP(Request $request){
        $timses_member_id = $request['timses_member_id'];
        $timses_id = $request['timses_id'];

        // dd($timses_id);

        if ($request->hasFile('timses_member_ktp')) {
            $resorce            = $request->file('timses_member_ktp');
            $timses_member_ktp    = time().'_ktp_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/timses_member_ktp', $timses_member_ktp);

        }else{
            $msg_err = "KTP Masih Kosong";
            return redirect('timses/add-ktp-member/'.$timses_id.'/'.$timses_member_id)->with('msgerror',$msg_err);

        }
        
        $data = array(
            'timses_member_id'               => $request['timses_member_id'],
            'timses_member_ktp'              => $timses_member_ktp, 
            'created_id'                     => Auth::id(),
            'created_at'                     => date('Y-m-d'),
        );

        if(CoreTimsesMemberKTP::create($data)){

                $msg = 'Tambah Upload KTP Berhasil';
                return redirect('timses/add-ktp-member/'.$timses_id.'/'.$timses_member_id)->with('msg',$msg);
            } else {
                $msg = 'Tambah Upload KTP Gagal';
                return redirect('timses/add-ktp-member/'.$timses_id.'/'.$timses_member_id)->with('msg',$msg);
        }
    }

    public function downloadMemberCoreTimsesKTP($timses_member_ktp_id){
        $timses_member_ktp = CoreTimsesMemberKTP::findOrFail($timses_member_ktp_id); 
        return response()->download(
            public_path('storage/timses_member_ktp/'.$timses_member_ktp['timses_member_ktp']),
            $timses_member_ktp['timses_member_ktp'],
        );
    }

    public function deleteMemberCoreTimsesKTP($timses_member_ktp_id){
        
        $item               = CoreTimsesMemberKTP::findOrFail($timses_member_ktp_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus KTP Berhasil';
        }else{
            $msg = 'Hapus KTP Gagal';
        }

        return redirect()->back()->with('msg',$msg);
    }

    public function editMemberCoreTimses($timses_id, $timses_member_id){
        $membertimses = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_member_id', $timses_member_id)
        ->where('core_timses_member.timses_id', $timses_id)
        ->first();

        return view('content/CoreTimses_view/FormEditMemberCoreTimses', compact('membertimses'));
    }

    public function processEdiMemberCoreTimses(Request $request){
        $timses_member_id = $request['timses_member_id'];
        $timses_id = $request['timses_id'];

        $fields = $request->validate([
            'timses_member_id'               => 'required',
            'timses_member_name'             => 'required',
            'timses_member_nik'              => 'required',
            'timses_member_address'          => 'required',
            'timses_member_phone'            => 'required',
            'timses_member_gender'           => 'required',
        ]);

        $item  = CoreTimsesMember::findOrFail($fields['timses_member_id']);
            $item->timses_member_name        = $fields['timses_member_name'];
            $item->timses_member_nik         = $fields['timses_member_nik'];
            $item->timses_member_address     = $fields['timses_member_address'];
            $item->timses_member_phone       = $fields['timses_member_phone'];
            $item->timses_member_gender      = $fields['timses_member_gender'];
    
            if($item->save()){
                $msg = 'Edit Data Timses Berhasil';
                return redirect('/timses/add-member/'.$timses_id)->with('msg',$msg);
            }else{
                $msg = 'Edit Data Timses Gagal';
                return redirect('/timses/add-member/'.$timses_id)->with('msg',$msg);
            }
    }

    public function deleteMemberCoreTimses($timses_member_id){

        $item  = CoreTimsesMember::findOrFail($timses_member_id);
        $item -> data_state = 1;
        if($item->save())
        {
            $msg = 'Hapus Data Anggota Timses Berhasil';
        }else{
            $msg = 'Hapus Data Anggota Timses Gagal';
        }
        // print_r($item); exit;
        

        return  redirect()->back()->with('msg',$msg);

    }

    public function detailCoreTimses($timses_id){
        $coretimses = CoreTimses::where('data_state','=',0)->findOrFail($timses_id);

        $membertimses = CoreTimsesMember::where('data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();
        
        return view('content/CoreTimses_view/FormDetailCoreTimses', compact('coretimses', 'membertimses'));
    }

    public function deleteCoreTimses($timses_id){

        $item  = CoreTimses::findOrFail($timses_id);
        $item -> data_state = 1;
        if($item->save())
        {
            $msg = 'Hapus Timses Berhasil';
        }else{
            $msg = 'Hapus Timses Gagal';
        }
        // print_r($item); exit;
        

        return  redirect()->back()->with('msg',$msg);

    }
}
