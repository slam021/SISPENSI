@inject('MemberCoreTimses', 'App\Http\Controllers\CoreTimsesController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('js')
<script>
</script>
    
@stop
@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Data Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data Timses</li>
    </ol>
</nav>
@stop

@section('content')

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
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit Data Timses
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <?php 
        $gender =[
            ''  => '',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];

        // $timses_member_id = Request::segment(3);
        
    ?>

    <form method="post" action="{{route('process-edit-timses-member')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Nama<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_name" id="timses_member_name" value="{{$membertimses->timses_member_name}}" autocomplete="off" />
                    <input class="form-control input-bb" type="hidden" name="timses_member_id" id="timses_member_id" value="{{$membertimses->timses_member_id}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">NIK<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_nik" id="timses_member_nik" value="{{$membertimses->timses_member_nik}}"  autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Alamat<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_address" id="timses_member_address" value="{{$membertimses->timses_member_address}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">No. Telp<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_phone" id="timses_member_phone" value="{{$membertimses->timses_member_phone}}"  autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Kelamin<a class='red'> *</a></a>
                    {!! Form::select('timses_member_gender', $gender, $membertimses->timses_member_gender, ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_gender','' ])!!}
                    </div>
                </div>
            </div>
            <div class="form-actions float-right" style="margin-bottom: -15px; margin-top: -20px" >
                <button type="reset" name="Reset" class="btn btn-danger btn-sm" onClick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
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

@section('js')
    
@stop