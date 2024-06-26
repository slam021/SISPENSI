<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\SystemUserGroup;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

class SystemUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $systemuser = User::where('data_state','=',0)->get();
        return view('content/SystemUser/ListSystemUser',compact('systemuser'));
    }

    public function addSystemUser(Request $request)
    {
        $systemusergroup = SystemUserGroup::where('data_state','=',0)->pluck('user_group_name', 'user_group_id');
        $nullsystemusergoup = Session::get('user_group_id');
        return view('content/SystemUser/FormAddSystemUser',compact('systemusergroup', 'nullsystemusergoup'));
    }

    public function processAddSystemUser(Request $request)
    {
        $fields = $request->validate([
            'name'                      => 'required',
            'password'                  => 'required',
            'user_group_id'             => 'required'
        ]);

        $user = User::create([
            'name'                  => $fields['name'],
            'password'              => Hash::make($fields['password']),
            'user_group_id'         => $fields['user_group_id'],
        ]);

        $msg = 'Tambah System User Berhasil';
        return redirect('/system-user/add')->with('msg',$msg);
    }

    public function editSystemUser($user_id)
    {
        $systemusergroup = SystemUserGroup::where('data_state','=',0)->get()->pluck('user_group_name','user_group_id');
        $systemuser = User::where('user_id',$user_id)->first();
        return view('content/SystemUser/FormEditSystemUser',compact('systemusergroup', 'systemuser', 'user_id'));
    }

    public function processEditSystemUser(Request $request)
    {
        $fields = $request->validate([
            'user_id'                      => 'required',
            'name'                      => 'required',
            'password'                  => 'required',
            'user_group_id'             => 'required'
        ]);

        $user = User::findOrFail($fields['user_id']);
        $user->name = $fields['name'];
        $user->password = Hash::make($fields['password']);
        $user->user_group_id = $fields['user_group_id'];

        if($user->save()){
            $msg = 'Edit System User Berhasil';
            return redirect('/system-user')->with('msg',$msg);
        }else{
            $msg = 'Edit System User Gagal';
            return redirect('/system-user')->with('msg',$msg);
        }
    }

    public function deleteSystemUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->data_state = 1;
        if($user->save())
        {
            $msg = 'Hapus System User Berhasil';
        }else{
            $msg = 'Hapus System User Gagal';
        }

        return redirect('/system-user')->with('msg',$msg);
    }

    public function getUserGroupName($user_group_id)
    {
        $usergroupname =  User::select('system_user_group.user_group_name')->join('system_user_group','system_user_group.user_group_id','=','system_user.user_group_id')->where('system_user.user_group_id','=',$user_group_id)->first();

        return $usergroupname['user_group_name'];
    }
}
