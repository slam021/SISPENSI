@inject('CoreCandidate', 'App\Http\Controllers\CoreCandidateController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Kandidat</li>
    </ol>
</nav>
@stop

<?php 
$gender =[
    ''  => '',
    '1' => 'Laki-laki',
    '2' => 'Perempuan',
];
?>

@section('content')
<h3 class="page-title">
    <b>Profil Kandidat</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
     <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif  
@foreach($corecandidate as $key => $val)
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Data Kandidat 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('candidate/edit/'.$val->candidate_id) }}'" name="add" class="btn btn-sm bg-warning" title="Add Data"><i class="fas fa-edit"></i> Edit Data Kandidat</button>
        </div>
    </div>
    <form method="post" action="{{route('process-edit-candidate')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-5"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark"><a class='red'></a></a>
                        <img width="250px" height="320px" src="{{ url('storage/candidate_photos/'.$val['candidate_photos']) }}">
                        <a type="button" style="margin-top: 7px; margin-left: 80px" class="btn bg-blue btn-sm" target="_blank" href="{{ url('/candidate/download/'. $val['candidate_id']) }}" ><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>
                <div class="col-md-3"></div>
                <br>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama Lengkap<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_full_name" id="candidate_full_name" value="{{$val->candidate_full_name}}" autocomplete="off" readonly/>
                        <input class="form-control input-bb" type="hidden" name="candidate_id" id="candidate_id" value="{{$val->candidate_id}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama panggilan<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nick_name" id="candidate_nick_name" value="{{$val->candidate_nick_name}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">NIK<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nik" id="candidate_nik" value="{{$val->candidate_nik}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Kelamin<a class='red'> </a></a>
                        {{-- {!! Form::select('candidate_gender', $gender, $val->candidate_gender, ['class' => 'selection-search-clear select-form', 'id' => 'candidate_gender', 'disabled'])!!} --}}
                        <input class="form-control input-bb" type="text" name="gender" id="gender" value="{{$gender[$val->candidate_gender]}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Alamat<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_address" id="candidate_address" value="{{$val->candidate_address}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">No.Telp<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_phone_number" id="candidate_phone_number" value="{{$val->candidate_phone_number}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tempat Lahir<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_birth_place" id="candidate_birth_place" value="{{$val->candidate_birth_place}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tanggal Lahir<a class='red'> </a></a>
                        <input class="form-control input-bb" type="date" name="candidate_birth_date" id="candidate_birth_date" value="{{$val->candidate_birth_date}}" autocomplete="off" readonly/>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endforeach
</div>

<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Data Partai     
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{url('candidate/edit-partai/'.$val->candidate_id)}}'" name="Find" class="btn btn-sm btn-warning" title="Back"><i class="	fas fa-edit"></i>  Edit Partai</button>
        </div>
    </div>
    @foreach($corecandidatepartai as $key => $partai)
    <form method="post" action="{{route('process-edit-candidate-partai')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama Partai<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="partai_name" id="partai_name" value="{{$partai->partai_name}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nomor Partai<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="partai_number" id="partai_number" value="{{$partai->partai_number}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nomor Kandidat<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_number" id="candidate_number" value="{{$partai->candidate_number}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Periode<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="period_id" id="period_id" value="{{$partai->period_year}}" autocomplete="off" readonly/>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger btn-sm" onClick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div> --}}
        {{-- <input class="form-control input-bb" type="hidden"name="timses_id" id="timses_id" value="{{$timses_id}}" autocomplete="off" readonly/> --}}
    </form>
    @endforeach
</div>
<br>
<br>
<br>



@stop

@section('footer')
    
@stop

@section('css')
    
@stop

@section('js')
    
@stop