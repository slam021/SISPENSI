@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-polling-station-elements')}}",
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
				url : "{{route('add-polling-station-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('polling-station') }}">Daftar Data TPS</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data TPS</li>
    </ol>
</nav>

@stop

@section('content')

{{-- <h3 class="page-title">
    Form Edit Bagian
</h3> --}}
@if(session('msg'))
<div class="alert alert-success" role="alert">
    {{session('msg')}}
</div>
@endif
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit Data TPS
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('polling-station') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>
    <form method="post" action="{{route('process-edit-polling-station')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Dapil<a class='red'> *</a></a>
                        {!! Form::select('dapil_id', $coredapil, $corepollingstation->dapil_id, ['class' => 'selection-search-clear select-form', 'id' => 'dapil_id','' ])!!}
                        <input class="form-control input-bb" type="hidden" name="polling_station_id" id="polling_station_id" value="{{$corepollingstation->polling_station_id}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nama TPS<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="polling_station_name" id="polling_station_name" value="{{$corepollingstation->polling_station_name}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Alamat TPS<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="polling_station_address" id="polling_station_address" value="{{$corepollingstation->polling_station_address}}" autocomplete="off" />
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