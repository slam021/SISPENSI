<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreDapil;
use App\Models\CoreProvince;
use App\Models\CoreCity;
use App\Models\CoreDistrict;
use App\Models\CoreVillage;
use App\Models\CoreDapilCategory;
use App\Models\CoreDapilItem;

class CoreDapilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    
        $coredapil = CoreDapil::select('core_dapil.*', 'core_dapil_category.*')
        ->join('core_dapil_category', 'core_dapil_category.dapil_category_id', '=', 'core_dapil.dapil_category_id')
        ->where('core_dapil.data_state','=',0)
        ->get();

        // print_r($coredapil['dapil_id']); exit;
        return view('content/CoreDapil_view/ListCoreDapil', compact('coredapil'));
    }

    public function addCoreDapil(Request $request){
        $coredapil = Session::get('data_coredapil');

        $listdapilcategory  = CoreDapilCategory::pluck('dapil_category_name', 'dapil_category_id');
        $nulldapilcategory = Session::get('dapil_category_id');

        return view('content/CoreDapil_view/FormAddCoreDapil', compact('coredapil', 'listdapilcategory', 'nulldapilcategory'));
    }

    public function addCoreDapilItem($dapil_id){
        $coredapil = Session::get('data_coredapil');

        $coredapilitem = CoreDapilItem::where('core_dapil_item.data_state', '=', 0)
        ->where('core_dapil_item.dapil_id', $dapil_id)
        ->get();
        // dd($coredapilitem);

        $province  = CoreProvince::pluck('province_name', 'province_id');
        return view('content/CoreDapil_view/FormAddCoreDapilItem', compact('coredapil', 'coredapilitem', 'province'));
    }

    public function getProvinceName($province_id){
        $data = CoreProvince::where('province_id',$province_id)
        ->first();
        if($data == null){
            "-";
        }else{
            return $data['province_name'];
        }
    }

    public function getCityName($city_id){
        $data = CoreCity::where('city_id',$city_id)
        ->first();
        if($data == null){
            "-";
        }else{
            return $data['city_name'];
        }
    }

    public function getDistrictName($kecamatan_id){
        $data = CoreDistrict::where('kecamatan_id',$kecamatan_id)
        ->first();
        if($data == null){
            "-";
        }else{
            return $data['kecamatan_name'];
        }
    }

    public function getVillageName($kelurahan_id){
        $data = CoreVillage::where('kelurahan_id',$kelurahan_id)
        ->first();
        if($data == null){
            "-";
        }else{
            return $data['kelurahan_name'];
        }
    }

    public function addElementsCoreDapil(Request $request){
        $data_coredapil[$request->name] = $request->value;

        $coredapil = Session::get('data_coredapil');
        
        return redirect('/dapil/add');
    }

    public function addReset(){
        Session::forget('data_coredapil');

        return redirect('/dapil/add');
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

    public function getDapilName($dapil_id){
        $data = CoreDapil::where('data_state', 0)
        ->where('dapil_id', $dapil_id)
        ->first();

        if($data == null){
            "";
        }else{
            return $data->dapil_name;
        }
    }

    public function processAddCoreDapil(Request $request){
        $fields = $request->validate([
            'dapil_category_id'     => 'required',
            'dapil_name'            => 'required',
        ]);

        $data = array(
            'dapil_category_id'    => $fields['dapil_category_id'],
            'dapil_name'           => $fields['dapil_name'], 
            'created_id'           => Auth::id(),
            'created_at'           => date('Y-m-d'),
        );

        if(CoreDapil::create($data)){
            $msg = 'Tambah Data Dapil Berhasil';
            Session::forget('data_coredapil');
            return redirect('/dapil/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Dapil Gagal';
            return redirect('/dapil/add')->with('msg',$msg);
        }
        
    }

    public function processAddCoreDapilItem(Request $request){
        
        $dapil_id = $request->dapil_id;

        $data = array(
            'dapil_id'             => $dapil_id, 
            'province_id'          => $request['province_id'], 
            'city_id'              => $request['city_id'], 
            'kecamatan_id'         => $request['kecamatan_id'], 
            'kelurahan_id'         => $request['kelurahan_id'], 
            'created_id'           => Auth::id(),
            'created_at'           => date('Y-m-d'),
        );
        // dd($data);

        if(CoreDapilItem::create($data)){
            $msg = 'Tambah Data Daerah Bagian Dapil Berhasil';
            Session::forget('data_coredapil');
            return redirect('/dapil/add-dapil-item/'.$dapil_id)->with('msg',$msg);
        } else {
            $msg = 'Tambah Data Daerah Bagian Dapil Gagal';
            return redirect('/dapil/add-dapil-item/'.$dapil_id)->with('msg',$msg);
        }
    }

    public function editCoreDapil($dapil_id){
        $coredapil = CoreDapil::where('data_state', '=', 0)->where('dapil_id', $dapil_id)->first();
// dd( $coredapil);

        $listdapilcategory  = CoreDapilCategory::pluck('dapil_category_name', 'dapil_category_id');
        $nulldapilcategory = Session::get('dapil_category_id');

        return view('content/CoreDapil_view/FormEditCoreDapil', compact('coredapil', 'listdapilcategory', 'nulldapilcategory'));
    }

    public function editCoreDapilItem($dapil_id){
        $coredapil = Coredapil::where('data_state', '=', 0)->where('dapil_id', $dapil_id)->first();
        $province  = CoreProvince::pluck('province_name', 'province_id');
        $city  = CoreCity::where('province_id', $coredapil['province_id'])->pluck('city_name', 'city_id');
        $district  = CoreDistrict::where('city_id', $coredapil['city_id'])->pluck('kecamatan_name', 'kecamatan_id');
        $village  = CoreVillage::where('kecamatan_id', $coredapil['kecamatan_id'])->pluck('kelurahan_name', 'kelurahan_id');

        return view('content/Coredapil_view/FormEditCoredapil', compact('coredapil', 'province', 'city', 'district', 'village'));
    }

    public function processEditCoreDapil(Request $request){
        $fields = $request->validate([
            'dapil_id'            => 'required',
            'dapil_category_id'   => 'required',
            'dapil_name'          => 'required',
        ]); 

        $item                     = CoreDapil::findOrFail($fields['dapil_id']);
        $item->dapil_name         = $fields['dapil_name'];
        $item->dapil_category_id  = $fields['dapil_category_id'];

        // print_r($item); exit;
        if($item->save()){
            $msg = 'Edit Data Dapil Berhasil';
            return redirect('/dapil')->with('msg',$msg);
        }else{
            $msg = 'Edit Data Dapil Gagal';
            return redirect('/dapil')->with('msg',$msg);
        }
    }

    public function detailCoreDapil($dapil_id){
        $coredapil = CoreDapil::select('core_dapil.*', 'core_dapil_category.*')
        ->join('core_dapil_category', 'core_dapil_category.dapil_category_id', '=', 'core_dapil.dapil_category_id')
        ->where('core_dapil.data_state', '=', 0)
        ->where('core_dapil.dapil_id', $dapil_id)->first();
// dd( $coredapil);
        $coredapilitem = CoreDapilItem::where('core_dapil_item.data_state', '=', 0)
        ->where('core_dapil_item.dapil_id', $dapil_id)
        ->get();

        $listdapilcategory  = CoreDapilCategory::pluck('dapil_category_name', 'dapil_category_id');
        $nulldapilcategory = Session::get('dapil_category_id');

        return view('content/CoreDapil_view/FormDetailCoreDapil', compact('coredapil', 'listdapilcategory', 'nulldapilcategory', 'coredapilitem'));
    }

    public function processEditCoreDapilItem(Request $request){
        $fields = $request->validate([
            'dapil_id'           => 'required',
            'dapil_name'         => 'required',
            'province_id'           => 'required',
            'city_id'               => 'required',
            'kecamatan_id'          => 'required',
            'kelurahan_id'          => 'required',
        ]); 
        // print_r($fields); exit;


        // $data = Coredapil::where('dapil_id', $fields['dapil_id'])->update([
        //     'dapil_name'        => $fields['dapil_name'], 
        //     'province_id'          => $fields['province_id'], 
        //     'city_id'              => $fields['city_id'], 
        //     'kecamatan_id'         => $fields['kecamatan_id'], 
        //     'kelurahan_id'         => $fields['kelurahan_id'],
        // ]);

        // return redirect('/dapil');


        $item                   = CoreDapil::findOrFail($fields['dapil_id']);
        $item->dapil_name    = $fields['dapil_name'];
        $item->province_id      = $fields['province_id'];
        $item->city_id          = $fields['city_id'];
        $item->kecamatan_id     = $fields['kecamatan_id'];
        $item->kelurahan_id     = $fields['kelurahan_id'];

        // print_r($item); exit;
        if($item->save()){
            $msg = 'Edit Data Lokasi Berhasil';
            return redirect('/dapil')->with('msg',$msg);
        }else{
            $msg = 'Edit Data Lokasi Gagal';
            return redirect('/dapil')->with('msg',$msg);
        }
        // print_r($item); exit;

    }

    public function deleteCoredapil($dapil_id)
    {
        $item               = CoreDapil::findOrFail($dapil_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save()) {
            $msg = 'Hapus Data Dapil Berhasil';
        }else{
            $msg = 'Hapus Data Dapil Gagal';
        }

        return redirect('/dapil')->with('msg',$msg);
    }

    public function deleteCoredapilItem($dapil_id)
    {
        $item               = Coredapil::findOrFail($dapil_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save()) {
            $msg = 'Hapus Data dapil Berhasil';
        }else{
            $msg = 'Hapus Data dapil Gagal';
        }

        return redirect('/dapil')->with('msg',$msg);
    }
}
