@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('program-timses') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Dokumentatsi Acara</li>
    </ol>
</nav>

@stop

@section('content')

{{-- <h3 class="page-title">
    Form Tambah Dokumentatsi Acara
</h3> --}}
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
@if(session('msgerror'))
<div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msgerror')}}
</div>
@endif

<?php
    $program_id = Request::segment(3);
?>
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah Dokumentatsi Acara
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ route('program-timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-documentation-program2')}}" enctype="multipart/form-data">
        @csrf
            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="text-dark">Dokumentasi<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="file" name="program_documentation_file" id="program_documentation_file" value="{{old('program_documentation_file')}}" autocomplete="off" />
                            <input class="form-control input-bb" type="hidden" name="program_id" id="program_id" value="{{$program_id}}" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" style="padding-top: 6px">
                            <br>
                            <button type="submit" name="Save" class="btn btn-success btn-sm"  title="Save"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border border-dark">
            <div class="card-header border-dark bg-dark">
                <h5 class="mb-0 float-left">
                    Dokumentasi Acara
                </h5>
            </div>
                @csrf
                <div class="card-body">
                    <div class="row form-group">
                        @if(count($documentation_file) <= 0)
                        <div class="container"  style='text-align:center;''>  
                            <h6 style='font-weight:bold; padding-top:20px'>Dokumentasi Kosong</h6>
                        </div> 
                        @else
                            @foreach($documentation_file as $key => $val)
                            <div class="col-md-4" style="padding-left: 45px">
                                <div class="form-group" >
                                    <img class="image" src="{{ url('storage/program_documentation_file/'.$val['program_documentation_file']) }}" width="250px" height="250px">
                                    <br>
                                    <a type="button" style="margin-top: 7px; margin-left: 90px" class="btn bg-blue btn-sm" target="_blank" href="{{ url('/program/download-documentation/'.$val->program_documentation_id) }}" ><i class="fa fa-download"></i> </a>
                                    <a type="button" style="margin-top: 7px;" class="btn bg-danger btn-sm" href="{{ url('/program/delete-documentation/'.$val->program_documentation_id) }}" ><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </form>
        </div>
    <br>
<br>
@stop

@section('footer')
    
@stop

@section('css')
    
@stop