@extends('adminlte::page')
@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-location-elements')}}",
				data : {
                    'name'      : name, 
                    'value'     : value,
                    // '_token'    : '{{csrf_token()}}'
                },
				success: function(msg){
			}
		});
	}

    function reset_add(){
		$.ajax({
				type: "GET",
				url : "{{route('add-location-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}

    $(document).ready(function(){
        // $("#province_id").select2("val", "0");
        $("#province_id").change(function(){
			var province_id 	= $("#province_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('location-city')}}",
                    dataType: "html",
                    data: {
                        'province_id'	: province_id,
                        '_token'        : '{{csrf_token()}}',
                    },
                    success: function(return_data){ 
					    $('#city_id').html(return_data);
                        // console.log(return_data);
                    },
                    error: function(data)
                    {
                        console.log(data);

                    }
                });

		});
	});

    $(document).ready(function(){
        // $("#city_id").select2("val", "0");
        $("#city_id").change(function(){
			var city_id 	= $("#city_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('location-district')}}",
                    dataType: "html",
                    data: {
                        'city_id'	: city_id,
                        '_token'        : '{{csrf_token()}}',
                    },
                    success: function(return_data){ 
					    $('#kecamatan_id').html(return_data);
                        // console.log(return_data);
                    },
                    error: function(data)
                    {
                        console.log(data);

                    }
                });

		});
	});

    $(document).ready(function(){
        // $("#kecamatan_id").select2("val", "0");
        $("#kecamatan_id").change(function(){
			var kecamatan_id 	= $("#kecamatan_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('location-village')}}",
                    dataType: "html",
                    data: {
                        'kecamatan_id'	: kecamatan_id,
                        '_token'        : '{{csrf_token()}}',
                    },
                    success: function(return_data){ 
					    $('#kelurahan_id').html(return_data);
                        // console.log(return_data);
                    },
                    error: function(data)
                    {
                        console.log(data);

                    }
                });
		});
	});

</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('section') }}">Daftar Data Lokasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data Lokasi</li>
    </ol>
  </nav>

@stop

@section('content')
@if(session('msg'))
<div class="alert alert-info close" role="alert">
    {{session('msg')}}
</div>
@endif
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit Data Lokasi
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('location') }}'" name="back" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>
    
    <form method="post" action="{{route('process-edit-location')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Lokasi<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="location_name" id="location_name" value="{{$corelocation['location_name']}}"  autocomplete="off"/>
                        <input class="form-control input-bb" type="hidden" name="location_id" id="location_id" value="{{$corelocation['location_id']}}"  autocomplete="off"/>
                    </div>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="form-group">	
                        <a class="text-dark">Provinsi<a class='red'> *</a></a>
                        {!! Form::select('province_id', $province, $corelocation->province_id, ['class' => 'selection-search-clear select-form', 'id' => 'province_id']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kabupaten/Kota<a class='red'> *</a></a>
                        {{-- <select class="selection-search-clear" name="city_id" id="city_id" style="width: 100% !important" value="{{$city->city_id}}">
                        </select> --}}
                        {!! Form::select('city_id', $city, $corelocation->city_id, ['class' => 'selection-search-clear select-form', 'id' => 'city_id']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kecamatan<a class='red'> *</a></a>
                        {{-- <select class="selection-search-clear" name="kecamatan_id" id="kecamatan_id" style="width: 100% !important">
                        </select> --}}
                        {!! Form::select('kecamatan_id', $district, $corelocation->kecamatan_id, ['class' => 'selection-search-clear select-form', 'id' => 'kecamatan_id']) !!}
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kelurahan/Desa<a class='red'> *</a></a>
                        {{-- <select class="selection-search-clear" name="kelurahan_id" id="kelurahan_id" style="width: 100% !important">
                        </select> --}}
                         {!! Form::select('kelurahan_id', $village, $corelocation->kelurahan_id, ['class' => 'selection-search-clear select-form', 'id' => 'kelurahan_id']) !!}
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