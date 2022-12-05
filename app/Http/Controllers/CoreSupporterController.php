<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CoreSupporter;


class CoreSupporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $coresupporter = CoreSupporter::where('data_state', '=', 0)->get();
        $supportergender =array(
            1 => 'Laki-laki',
            2 => 'Perempuan',
        );
        return view('content/CoreSupporter_view/ListCoreSupporter', compact('coresupporter', 'supportergender'));
    }

    public function addElementsCoreSupporter(Request $request){
        $data_coresupporter[$request->name] = $request->value;

        $coresupporter = Session::get('data_coresupporter');
        
        return redirect('/supporter/add');
    }

    public function addReset(){
        Session::forget('data_coresupporter');

        return redirect('/supporter/add');
    }

    public function addCoreSupporter(Request $request){
        $coresupporter = Session::get('data_coresupporter');

        return view('content/CoreSupporter_view/FormAddCoreSupporter', compact('coresupporter'));
    }

    public function processAddCoreSupporter(Request $request){
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

                $msg = 'Tambah Data Pendukung Berhasil';
                return redirect('/supporter/add')->with('msg',$msg);
            } else {
                $msg = 'Tambah Data Pendukung Gagal';
                return redirect('/supporter/add')->with('msg',$msg);
        }
    }

    public function editCoreSupporter($supporter_id){
        $coresupporter = CoreSupporter::where('data_state','=',0)->where('supporter_id', $supporter_id)->first();

        return view('content/CoreSupporter_view/FormEditCoreSupporter', compact('coresupporter'));
    }

    public function processEditCoreSupporter(Request $request){
        $fields = $request->validate([
            'supporter_id'        => 'required',
            'supporter_full_name'           => 'required',
            'supporter_nik'                 => 'required',
            'supporter_address'             => 'required',
            'supporter_gender'              => 'required',
            'supporter_birth_place'         => 'required',
            'supporter_birth_date'          => 'required',
        ]);

        $item                    = CoreSupporter::findOrFail($fields['supporter_id']);
        $item->supporter_full_name         = $fields['supporter_full_name'];
        $item->supporter_nik               = $fields['supporter_nik'];
        $item->supporter_address           = $fields['supporter_address'];
        $item->supporter_gender            = $fields['supporter_gender'];
        $item->supporter_birth_place       = $fields['supporter_birth_place'];
        $item->supporter_birth_date        = $fields['supporter_birth_date'];

        if($item->save()){
            $msg = 'Edit Data Kandidat Berhasil';
            return redirect('/supporter')->with('msg',$msg);
        }else{
            $msg = 'Edit Data Kandidat Gagal';
            return redirect('/supporter')->with('msg',$msg);
        }
    }

    public function deleteCoreSupporter($supporter_id)
    {
        $item               = CoreSupporter::findOrFail($supporter_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Data Pendukung Berhasil';
        }else{
            $msg = 'Hapus Data Pendukung Gagal';
        }

        return redirect('/supporter')->with('msg',$msg);
    }
}
