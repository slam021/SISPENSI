<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreCandidate;
use App\Models\CoreLocation;
use App\Models\CorePollingStation;
use App\Models\CoreSupporter;

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

        $corecandidate = CoreCandidate::select('core_candidate.*')
        ->where('data_state', '=', 0)
        ->get();

        $corelocation = CoreLocation::select('core_location.*')
        ->where('data_state', '=', 0)
        ->get();

        $corepollingstation = CorePollingStation::select('core_polling_station.*')
        ->where('data_state', '=', 0)
        ->get();

        $coresupporter = CoreSupporter::select('core_supporter.*')
        ->where('data_state', '=', 0)
        ->get();

        return view('home',compact('menus', 'corecandidate', 'corelocation', 'corepollingstation', 'coresupporter'));
    }
}
