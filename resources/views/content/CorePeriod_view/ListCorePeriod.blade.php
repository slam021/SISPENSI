@inject('CorePeriod', 'App\Http\Controllers\CorePeriodController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Data Periode</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Periode</b>
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
            Mengelola Data Periode 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('period/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Data Periode Baru</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Tahun</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($coreperiod as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['period_year']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning" href="{{ url('/period/edit/'.$val['period_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge badge-danger" href="{{ url('/period/delete-period/'.$val['period_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
                   

                    <?php $no++; ?>
                    @endforeach
                     
                </tbody>
            </table>
        </div>
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