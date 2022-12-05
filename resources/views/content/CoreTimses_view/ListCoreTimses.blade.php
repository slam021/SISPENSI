@inject('CoreTimses', 'App\Http\Controllers\CoreTimsesController')

@extends('adminlte::page')

@section('title', 'Sistem Timses Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Data Timses</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Timses</b>
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
            Mengelola Data Timses 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('timses/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Timses Baru</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama Timses</th>
                        <th width="10%" style='text-align:center'>Saldo Akhir</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                         function rupiah($angka){
                            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                            return $hasil_rupiah;
                        }
                    ?>
                    @foreach($coretimses as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_name']}}</td>
                        <td>{{rupiah($val['last_balance'])}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/timses/edit/'.$val['timses_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge bg-lime" href="{{ url('/timses/detail/'.$val['timses_id'])}}"><i class='fas fa-list-ul'></i> Detail</a>
                            <a type="button" class="badge bg-indigo" href="{{ url('/timses/add-member/'.$val['timses_id'])}}"><i class='fas fa-users'></i> Anggota</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/timses/delete-timses/'.$val['timses_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
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