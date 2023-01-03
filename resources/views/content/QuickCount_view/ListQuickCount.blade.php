@inject('QuickCount', 'App\Http\Controllers\QuickCountController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Quick Count</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Acara</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
     <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif 
@if (count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif 
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Quick Count 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('quick-count/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Quick Count Baru</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="3%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Periode</th>
                        <th width="10%" style='text-align:center'>Dapil</th>
                        <th width="10%" style='text-align:center'>TPS</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($quickcount as $key => $val)
                    
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['period_year']}}</td>
                        <td>{{$val['dapil_name']}}</td>
                        <td>{{$val['polling_station_name']}}</td>
                        <td class="" style='text-align:left'>
                            <?php
                                if($val['quick_count_status'] == 0){
                                    echo "<a type='button' class='badge badge-warning' href='".url('/quick-count/edit/'.$val['quick_count_id'])."' title='Edit'><i class='fas fa-edit'></i> Edit</a>";
                                }else{
                                    
                                }
                            ?>
                            <?php
                                if($val['quick_count_status'] == 0){
                                    echo "<a type='button' class='badge bg-lime' href='".url('/quick-count/starting-quick-count/'.$val['quick_count_id'].'/'.$val['period_id'])."' title='Mulai Quick Count'><i class='fa fa-hourglass-start'></i> Mulai</a>";
                                }else{
                                    
                                }
                            ?>
                            <?php
                                if($val['quick_count_status'] == 0){
                                    echo "<a type='button' class='badge bg-navy' href='".url('/quick-count/closing-quick-count/'.$val['quick_count_id'])."' title='Edit' onClick='javascript:return confirm(\"apakah Anda yakin ingin Menutup Acara ?\")'><i class='fa fa-exclamation-circle'></i> Tutup </a>";
                                }else{
                                    echo "<button type='button' class='badge bg-olive' href='' title='Edit' disabled><i class='fa fa-check'></i> Selesai</button>";
                                }
                            ?>
                            <a type="button" class="badge badge-danger" href="{{ url('/quick-count/delete-quick-count/'.$val['quick_count_id']) }}" title="Hapus"><i class='far fa-trash-alt'></i> Hapus</a>
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