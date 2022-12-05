<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreLocation;
use App\Models\CoreProvince;
use App\Models\CoreCity;
use App\Models\CoreDistrict;
use App\Models\CoreVillage;

class CoreLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    
        $corelocation = CoreLocation::select('core_location.*','core_province.province_name', 'core_city.city_name', 'core_district.kecamatan_name', 'core_village.kelurahan_name')
        ->join('core_province', 'core_province.province_id', '=', 'core_location.province_id')
        ->join('core_city', 'core_city.city_id', '=', 'core_location.city_id')
        ->join('core_district', 'core_district.kecamatan_id', '=', 'core_location.kecamatan_id')
        ->join('core_village', 'core_village.kelurahan_id', '=', 'core_location.kelurahan_id')
        ->where('core_location.data_state','=',0)
        ->get();

        // print_r($corelocation['location_id']); exit;
        return view('content/CoreLocation_view/ListCoreLocation', compact('corelocation'));
    }

    public function addCoreLocation(Request $request){
        $corelocation = Session::get('data_corelocation');

        $province  = CoreProvince::pluck('province_name', 'province_id');
        return view('content/CoreLocation_view/FormAddCoreLocation', compact('corelocation', 'province'));
    }

    public function addElementsCoreLocation(Request $request){
        $data_corelocation[$request->name] = $request->value;

        $corelocation = Session::get('data_corelocation');
        
        return redirect('/location/add');
    }

    public function addReset(){
        Session::forget('data_corelocation');

        return redirect('/location/add');
    }

    public function getCoreCity(Request $request){
        $province_id = $request->province_id;
        $data='';

        $city = CoreCity::where('province_id', $province_id)
        ->where('data_state','=',0)
        ->get();

        $data .= "<option value=''>--Choose One--</option>";
        foreach ($city as $mp){
            $data .= "<option value='$mp[city_id]'>$mp[city_name]</option>\n";	
        }

        return $data;
    } 
    
    public function getCoreDistrict(Request $request){
        $city_id = $request->city_id;
        $data='';

        $district = CoreDistrict::where('city_id', $city_id)
        ->where('data_state','=',0)
        ->get();

        $data .= "<option value=''>--Choose One--</option>";
        foreach ($district as $mp){
            $data .= "<option value='$mp[kecamatan_id]'>$mp[kecamatan_name]</option>\n";	
        }

        return $data;
    }

    public function getCoreVillage(Request $request){
        $kecamatan_id = $request->kecamatan_id;
        $data='';

        $village = CoreVillage::where('kecamatan_id', $kecamatan_id)
        ->where('data_state','=',0)
        ->get();

        $data .= "<option value=''>--Choose One--</option>";
        foreach ($village as $mp){
            $data .= "<option value='$mp[kelurahan_id]'>$mp[kelurahan_name]</option>\n";	
        }

        return $data;
    }

    public function processAddCoreLocation(Request $request){
        $fields = $request->validate([
            'location_name'         => 'required',
            'province_id'           => 'required',
            'city_id'               => 'required',
            'kecamatan_id'          => 'required',
            'kelurahan_id'          => 'required',
        ]);
        
        $data = array(
            'location_name'        => $fields['location_name'], 
            'province_id'          => $fields['province_id'], 
            'city_id'              => $fields['city_id'], 
            'kecamatan_id'         => $fields['kecamatan_id'], 
            'kelurahan_id'         => $fields['kelurahan_id'], 
            'created_id'           => Auth::id(),
            'created_at'           => date('Y-m-d'),
        );

        if(CoreLocation::create($data)){
            $msg = 'Tambah Data Lokasi Berhasil';
            Session::forget('data_corelocation');
            return redirect('/location/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Lokasi Gagal';
            return redirect('/location/add')->with('msg',$msg);
        }
    }

    public function editCoreLocation($location_id){
        $corelocation = CoreLocation::where('data_state', '=', 0)->where('location_id', $location_id)->first();
        $province  = CoreProvince::pluck('province_name', 'province_id');
        $city  = CoreCity::where('province_id', $corelocation['province_id'])->pluck('city_name', 'city_id');
        $district  = CoreDistrict::where('city_id', $corelocation['city_id'])->pluck('kecamatan_name', 'kecamatan_id');
        $village  = CoreVillage::where('kecamatan_id', $corelocation['kecamatan_id'])->pluck('kelurahan_name', 'kelurahan_id');

        return view('content/CoreLocation_view/FormEditCoreLocation', compact('corelocation', 'province', 'city', 'district', 'village'));
    }

    public function processEditCoreLocation(Request $request){
        $fields = $request->validate([
            'location_id'           => 'required',
            'location_name'         => 'required',
            'province_id'           => 'required',
            'city_id'               => 'required',
            'kecamatan_id'          => 'required',
            'kelurahan_id'          => 'required',
        ]); 
        // print_r($fields); exit;


        // $data = CoreLocation::where('location_id', $fields['location_id'])->update([
        //     'location_name'        => $fields['location_name'], 
        //     'province_id'          => $fields['province_id'], 
        //     'city_id'              => $fields['city_id'], 
        //     'kecamatan_id'         => $fields['kecamatan_id'], 
        //     'kelurahan_id'         => $fields['kelurahan_id'],
        // ]);

        // return redirect('/location');


        $item                   = CoreLocation::findOrFail($fields['location_id']);
        $item->location_name    = $fields['location_name'];
        $item->province_id      = $fields['province_id'];
        $item->city_id          = $fields['city_id'];
        $item->kecamatan_id     = $fields['kecamatan_id'];
        $item->kelurahan_id     = $fields['kelurahan_id'];

        // print_r($item); exit;
        if($item->save()){
            $msg = 'Edit Data Lokasi Berhasil';
            return redirect('/location')->with('msg',$msg);
        }else{
            $msg = 'Edit Data Lokasi Gagal';
            return redirect('/location')->with('msg',$msg);
        }
        // print_r($item); exit;

    }

    public function deleteCoreLocation($location_id)
    {
        $item               = CoreLocation::findOrFail($location_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save()) {
            $msg = 'Hapus Data Location Berhasil';
        }else{
            $msg = 'Hapus Data Location Gagal';
        }

        return redirect('/location')->with('msg',$msg);
    }
}
