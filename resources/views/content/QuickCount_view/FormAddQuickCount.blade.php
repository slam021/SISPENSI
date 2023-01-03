@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-quick-count-elements')}}",
				data : {
                    'name'      : name, 
                    'value'     : value,
                    '_token'    : '{{csrf_token()}}'
                },
				success: function(msg){
			}
		});
	}

    function reset_add(){
		$.ajax({
				type: "GET",
				url : "{{route('add-quick-count-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}

    $(document).ready(function(){
        var period_id = {!! json_encode($nullcoreperiod) !!};
        
        if(period_id == null){
            $("#period_id").select2("val", "0");
        }
    });

    $(document).ready(function(){
        var dapil_id = {!! json_encode($nullcoredapil) !!};
        
        if(dapil_id == null){
            $("#dapil_id").select2("val", "0");
        }
    });

    $(document).ready(function(){
        var polling_station_id = {!! json_encode($nullcorepollingstation) !!};
        
        if(polling_station_id == null){
            $("#polling_station_id").select2("val", "0");
        }
    });
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('quick-count') }}">Daftar Quick Count</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Quick Count</li>
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
            Form Tambah Periode
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('quick-count') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    {{-- <?php 
        if (empty($coresection)){
            $coresection['section_name'] = '';
        }
    ?> --}}

    <form method="post" action="{{route('process-add-quick-count')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Periode<a class='red'> *</a></a>
                        {!! Form::select('period_id', $coreperiod, $nullcoreperiod, ['class' => 'selection-search-clear select-form', 'id' => 'period_id','' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Dapil<a class='red'> *</a></a>
                        {!! Form::select('dapil_id', $coredapil, $nullcoredapil, ['class' => 'selection-search-clear select-form', 'id' => 'dapil_id','' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">TPS<a class='red'> *</a></a>
                        {!! Form::select('polling_station_id', $corepollingstation, $nullcorepollingstation, ['class' => 'selection-search-clear select-form', 'id' => 'polling_station_id','' ])!!}
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
</form>

@stop

@section('footer')
    
@stop

@section('css')
    
@stop