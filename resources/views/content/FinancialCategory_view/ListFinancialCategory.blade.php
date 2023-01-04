@inject('FinancialCategory', 'App\Http\Controllers\FinancialCategoryController')

@extends('adminlte::page')

@section('title', 'Sistem Timses Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Kategori Keuangan</li>
    </ol>
</nav>
@stop

<?php 
    $type =[
        ''  => '',
        '1' => 'Pemasukan',
        '2' => 'Pengeluaran',
    ];
?>

@section('content')
<h3 class="page-title">
    <b>Daftar Kategori Keuangan</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif 
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Data Kategori Keuangan 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('financial-category/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Kategori Keuangan</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama Kategori</th>
                        <th width="10%" style='text-align:center'>Tipe Kategori</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($financialcategory as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['financial_category_name']}}</td>
                        <td>{{$type[$val['financial_category_type']]}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/financial-category/edit/'.$val['financial_category_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/financial-category/delete-financial-category/'.$val['financial_category_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    
@stop

@section('css')
    
@stop

@section('js')
    
@stop