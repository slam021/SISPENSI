@inject('MemberCoreTimses', 'App\Http\Controllers\CoreTimsesController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('js')
<script>
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})
</script>

@stop

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Data Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Anggota Timses</li>
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
            Form Data Timses
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

        $timses_id = Request::segment(3);
        
    ?>


    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-6">
                <div class="form-group">
                <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                <input class="form-control input-bb" type="text" name="timses_name" id="timses_name" value="{{$coretimses->timses_name}}" autocomplete="off" readonly/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <a class="text-dark">Partai Timses<a class='red'> *</a></a>
                <input class="form-control input-bb" type="text" name="timses_partai" id="timses_partai" value="{{$coretimses->timses_partai}}" autocomplete="off" readonly />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar Anggota Timses
        </h5>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="3%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama</th>
                        <th width="10%" style='text-align:center'>NIK</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="10%" style='text-align:center'>No. Telp</th>
                        <th width="10%" style='text-align:center'>Kelamin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($membertimses as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_member_name']}}</td>
                        <td>{{$val['timses_member_nik']}}</td>
                        <td>{{$val['timses_member_address']}}</td>
                        <td>{{$val['timses_member_phone']}}</td>
                        <td>{{$gender[$val['timses_member_gender']]}}</td>
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