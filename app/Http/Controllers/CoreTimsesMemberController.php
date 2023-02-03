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

class CoreTimsesMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addCoreTimsesMember($timses_id, Request $request){
        $coretimsesmember = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        // $systemuser = User::where('data_state','=',0)->max('user_id');

        // $systemusergroup = SystemUserGroup::where('data_state','=',0)->pluck('user_group_name', 'user_group_id');
        // $nullsystemusergoup = Session::get('user_group_id');
        return view('content/CoreTimses_view/FormAddCoreTimsesMember', compact('coretimsesmember'));
    }


    public function processAddCoreTimsesMember(Request $request){
        $fields = $request->validate([
            'timses_id'                      => 'required',
            'timses_member_name'             => 'required',
            'timses_member_date_of_birth'    => 'required',
            'timses_member_place_of_birth'   => 'required',
            'timses_member_address'          => 'required',
            'timses_member_phone'            => 'required',
            'timses_member_gender'           => 'required',
        ]);

        $data = array(
            'timses_id'                      => $fields['timses_id'], 
            'timses_member_name'             => $fields['timses_member_name'], 
            'timses_member_date_of_birth'    => $fields['timses_member_date_of_birth'], 
            'timses_member_place_of_birth'   => $fields['timses_member_place_of_birth'], 
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

    public function addCoreTimsesMemberKTP($timses_member_id, Request $request){
        $timses_member_id = $request['timses_member_id'];

        $timses_member_ktp = CoreTimsesMemberKTP::where('data_state', '=', 0)
        ->where('core_timses_member_ktp.timses_member_id', $timses_member_id)
        ->get();

        // dd($timses_member_ktp);

        return view('content/CoreTimses_view/FormAddCoreTimsesMemberKTP', compact('timses_member_ktp'));
    }

    public function processAddCoreTimsesMemberKTP(Request $request){
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

    public function downloadCoreTimsesMemberKTP($timses_member_ktp_id){
        $timses_member_ktp = CoreTimsesMemberKTP::findOrFail($timses_member_ktp_id); 
        return response()->download(
            public_path('storage/timses_member_ktp/'.$timses_member_ktp['timses_member_ktp']),
            $timses_member_ktp['timses_member_ktp'],
        );
    }

    public function deleteCoreTimsesMemberKTP($timses_member_ktp_id){
        
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

    public function editCoreTimsesMember($timses_id, $timses_member_id){
        $membertimses = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_member_id', $timses_member_id)
        ->where('core_timses_member.timses_id', $timses_id)
        ->first();

        return view('content/CoreTimses_view/FormEditMemberCoreTimses', compact('membertimses'));
    }

    public function processEdiCoreTimsesMember(Request $request){
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
}

