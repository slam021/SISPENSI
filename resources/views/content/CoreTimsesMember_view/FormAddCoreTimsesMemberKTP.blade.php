@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Timses</a></li>
        {{-- <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Timses Member</a></li> --}}
        <li class="breadcrumb-item active" aria-current="page">Tambah KTP Timses Member</li>
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
    $timses_member_id = Request::segment(3);
?>
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah KTP Timses Member
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('timses-member') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-add-ktp-member2')}}" enctype="multipart/form-data">
        @csrf
            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="text-dark">KTP<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="file" name="timses_member_ktp" id="timses_member_ktp" value="{{old('timses_member_ktp')}}" autocomplete="off" />
                            <input class="form-control input-bb" type="hidden" name="timses_member_id" id="timses_member_id" value="{{$timses_member_id}}" autocomplete="off" />
                            {{-- <input class="form-control input-bb" type="hidden" name="timses_id" id="timses_id" value="{{$timses_id}}" autocomplete="off" /> --}}
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
                    KTP Timses Member
                </h5>
            </div>
                @csrf
                <div class="card-body">
                    <div class="row form-group">
                        @if(count($timses_member_ktp) <= 0)
                        <div class="container"  style='text-align:center;''>  
                            <h6 style='font-weight:bold; padding-top:20px'>Belum Upload KTP</h6>
                        </div> 
                        @else
                            @foreach($timses_member_ktp as $key => $val)
                            <div class="col-md-4" style="padding-left: 45px">
                                <div class="form-group" >
                                    <img class="image" src="{{ asset('/storage/timses_member_ktp/'.$val['timses_member_ktp']) }}" width="325px" height="205px">
                                    <br>
                                    <a type="button" style="margin-top: 7px; margin-left: 125px" class="btn bg-blue btn-sm" target="_blank" href="{{ url('/timses/download-ktp-member/'.$val->timses_member_ktp_id) }}" ><i class="fa fa-download"></i> </a>
                                    <a type="button" style="margin-top: 7px;" class="btn bg-danger btn-sm" href="{{ url('/timses/delete-ktp-member/'.$val->timses_member_ktp_id) }}" ><i class="fas fa-trash-alt"></i></a>
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