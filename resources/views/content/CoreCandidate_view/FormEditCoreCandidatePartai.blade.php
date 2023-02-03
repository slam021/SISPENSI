@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    $(document).ready(function(){
        var period_id = {!! json_encode($nullcoreperiod) !!};
        
        if(period_id == null){
            $("#period_id").select2("val", "0");
        }
    });
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('candidate') }}">Data Kandidat</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data Partai</li>
    </ol>
</nav>

@stop

@section('content')

{{-- <h3 class="page-title">
    Form Tambah Bagian
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
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit Data Partai
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('candidate') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    {{-- <?php 
        if (empty($coresection)){
            $coresection['section_name'] = '';
        }
    ?> --}}
    
    <?php 
        $gender =[
            ''  => '',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];
    ?>

    <form method="post" action="{{route('process-edit-candidate-partai')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama Partai<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="partai_name" id="partai_name" value="{{old('partai_name')}}" autocomplete="off" />
                        <input class="form-control input-bb" type="hidden" name="candidate_id" id="candidate_id" value="{{ $corecandidatepartai->candidate_id}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nomor Partai<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="partai_number" id="partai_number" value="{{old('partai_number')}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nomor Candidate<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_number" id="candidate_number" value="{{old('candidate_number')}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Periode<a class='red'> *</a></a>
                        {!! Form::select('period_id', $coreperiod, $nullcoreperiod, ['class' => 'selection-search-clear select-form', 'id' => 'period_id','' ])!!}                   
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger btn-sm" onClick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </div>
    </div>
</form>

@stop

@section('footer')
    
@stop

@section('css')
    
@stop