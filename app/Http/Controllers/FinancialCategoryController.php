<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialCategory;

class FinancialCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $financialcategory = FinancialCategory::where('data_state','=',0)->get();
        return view('content/FinancialCategory_view/ListFinancialCategory', compact('financialcategory'));
    }

    public function addFinancialCategory(Request $request){
        $financialcategory = Session::get('data_financialcategory');
        return view('content/FinancialCategory_view/FormAddFinancialCategory', compact('financialcategory'));
    }

    public function addElementsFinancialCategory(Request $request){
        $data_financialcategory[$request->name] = $request->value;

        $financialcategory = Session::get('data_financialcategory');
        
        return redirect('/financial-category/add');
    }

    public function addReset(){
        Session::forget('data_financialcategory');

        return redirect('/financial-category/add');
    }

    public function processAddFinancialCategory(Request $request){
        $fields = $request->validate([
            'financial_category_name'       => 'required',
            'financial_category_type'       => 'required',
        ]);

        $data = array(
            'financial_category_name'       => $fields['financial_category_name'], 
            'financial_category_type'       => $fields['financial_category_type'],
            'created_id'                    => Auth::id(),
            'created_at'                    => date('Y-m-d'),
        );

        if(FinancialCategory::create($data)){
            $msg = 'Tambah Kategori Keuangan Berhasil';
            return redirect('/financial-category/add')->with('msg',$msg);
        } else {
            $msg = 'Tambah Kategori Keuangan Gagal';
            return redirect('/financial-category/add')->with('msg',$msg);
        }
    }

    public function editFinancialCategory($financial_category_id){
        $financialcategory = FinancialCategory::where('data_state','=',0)->where('financial_category_id', $financial_category_id)->first();
        return view('content/FinancialCategory_view/FormEditFinancialCategory', compact('financialcategory'));
    }

    public function processEditFinancialCategory(Request $request){
        $fields = $request->validate([
            'financial_category_id'         => 'required',
            'financial_category_name'       => 'required',
            'financial_category_type'       => 'required',
        ]);

        $item  = FinancialCategory::findOrFail($fields['financial_category_id']);
        $item->financial_category_name      = $fields['financial_category_name'];
        $item->financial_category_type      = $fields['financial_category_type'];

        if($item->save()){
            $msg = 'Edit Kategori Keuangan Berhasil';
            return redirect('/financial-category')->with('msg',$msg);
        }else{
            $msg = 'Edit Kategori Keuangan Gagal';
            return redirect('/financial-category')->with('msg',$msg);
        }
    }

    public function deleteFinancialCategory($financial_category_id){
        $item               = FinancialCategory::findOrFail($financial_category_id);
        $item->data_state   = 1;
        // $item->deleted_id   = Auth::id();
        // $item->deleted_at   = date("Y-m-d H:i:s");
        if($item->save())
        {
            $msg = 'Hapus Kategori Keuangan Berhasil';
        }else{
            $msg = 'Hapus Kategori Keuangan Gagal';
        }

        return redirect('/financial-category')->with('msg',$msg);
    }
}
