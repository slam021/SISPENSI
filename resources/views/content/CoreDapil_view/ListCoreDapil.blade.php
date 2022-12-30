@inject('CoreDapil', 'App\Http\Controllers\CoreDapilController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />


@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Data Dapil</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Dapil</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
    {{session('msg')}}
</div>
@endif 
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Data Dapil 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('dapil/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Data Dapil Baru</button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover table-full-width">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="5%" style='text-align:center'>Kategori Dapil</th>
                        <th width="5%" style='text-align:center'>Nama Dapil</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($coredapil as $val)
                    
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['dapil_category_name']}}</td>
                        <td>{{$val['dapil_name']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/dapil/edit/'.$val['dapil_id']) }}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge bg-indigo btn-sm" href="{{ url('/dapil/add-dapil-item/'.$val['dapil_id']) }}"><i class='fas fa-search-location'></i> Daerah Bagian</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/dapil/delete-dapil/'.$val['dapil_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
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