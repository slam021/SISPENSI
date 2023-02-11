<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreCandidate;
use App\Models\CoreDapil;
use App\Models\CorePollingStation;
use App\Models\CoreSupporter;
use App\Models\CoreTimsesMember;
use App\Models\CoreTimses;

class HomeController extends Controller
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
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('financial_flow_code');

        $menus =  User::select('system_menu_mapping.*','system_menu.*')
        ->join('system_user_group','system_user_group.user_group_id','=','system_user.user_group_id')
        ->join('system_menu_mapping','system_menu_mapping.user_group_level','=','system_user_group.user_group_level')
        ->join('system_menu','system_menu.id_menu','=','system_menu_mapping.id_menu')
        ->where('system_user.user_id','=',Auth::id())
        ->orderBy('system_menu_mapping.id_menu','ASC')
        ->get();

        $user_group_login = User::where('data_state', 0)
        ->where('system_user.user_id', Auth::id())->first()->user_group_id;
        // dd($user_group_login);

        $corecandidate = CoreCandidate::select('core_candidate.*')
        ->where('data_state', '=', 0)
        ->first();

        $coredapil = CoreDapil::select('core_dapil.*')
        ->where('data_state', '=', 0)
        ->get();

        $corepollingstation = CorePollingStation::select('core_polling_station.*')
        ->where('data_state', '=', 0)
        ->get();

        $coresupporter = CoreSupporter::select('core_supporter.*')
        ->where('data_state', '=', 0)
        ->get();

        
        $timses_id = CoreTimses::where('core_timses.data_state', 0)
        ->where('core_timses.user_id', Auth::id())->first();
        // dd($timses_id);

        // $timses_id = $take_timses_id;

        $coretimsesmember = CoreTimsesMember::where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();


        $coretimses = CoreTimses::select('core_timses.*')
        ->where('core_timses.data_state','=',0)
        ->get();

        return view('home',compact('timses_id', 'coretimses', 'user_group_login', 'menus', 'corecandidate', 'coredapil', 'corepollingstation', 'coresupporter', 'coretimsesmember'));
    }

}
