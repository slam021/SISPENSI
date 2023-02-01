@inject('CT', 'App\Http\Controllers\CoreTimsesController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
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
{{-- <h3 class="page-title">
    <b>Daftar Timses</b>
</h3> --}}
@if(session('msg'))
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif 

<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar Timses
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('timses/add') }}'" name="add" class="btn btn-sm bg-info" title="Add Data"><i class="fas fa-plus"></i> Tambah Timses</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="20%" style='text-align:center'>Nama Timses</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($coretimses as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_name']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge bg-warning" href="{{url('/timses/edit/'.$val['timses_id']) }}" title='Edit'><i class='fas fa-edit'></i> Edit</a> 
                            <a type="button" class="badge bg-success" href="{{url('/timses/add-member/'.$val['timses_id']) }}" title='Tambah Member'><i class='fas fa-users'></i> Tambah Member</a> 
                            <a type="button" class="badge bg-lime" href="{{url('/timses/detail/'.$val['timses_id']) }}" title='Detail'><i class='fas fa-list'></i> Detail</a> 
                            <a type="button" class="badge badge-danger" href="{{url('/timses/delete-timses/'.$val['timses_id']) }}" title="Hapus"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>

                    <?php $no++; ?>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
<br>
@stop

@section('footer')
    
@stop

@section('css')
    
@stop

@section('js')
    
@stop