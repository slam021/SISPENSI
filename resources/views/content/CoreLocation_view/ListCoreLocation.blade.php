@inject('CoreLocation', 'App\Http\Controllers\CoreLocationController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />


@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Data Lokasi</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Lokasi</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
    {{session('msg')}}
</div>
@endif 
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Data Lokasi 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('location/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Data Lokasi Baru</button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover table-full-width">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="5%" style='text-align:center'>Nama Lokasi</th>
                        <th width="5%" style='text-align:center'>Kelurahan/Desa</th>
                        <th width="5%" style='text-align:center'>Kecamatan</th>
                        <th width="5%" style='text-align:center'>Kabupaten/Kota</th>
                        <th width="5%" style='text-align:center'>Provinsi</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($corelocation as $val)
                    
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['location_name']}}</td>
                        <td>{{$val['kelurahan_name']}}</td>
                        <td>{{$val['kecamatan_name']}}</td>
                        <td>{{$val['city_name']}}</td>
                        <td>{{$val['province_name']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/location/edit/'.$val['location_id']) }}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/location/delete-location/'.$val['location_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
                    {{-- {{print_r($corelocation)}} --}}

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