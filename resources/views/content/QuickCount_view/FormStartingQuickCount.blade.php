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

    
    function sum(candidate_id){
    $.ajax({
				type: "GET",
				url : "{{url('/quick-count/sum-starting-quick-count')}}" + '/' + candidate_id,
				success: function(msg){
                    console.log(msg);
                    $("#candidate_id_"+candidate_id).text(msg);
                    if(msg > 0 ){
                        document.getElementById('quick_count_subtraction'+candidate_id).style.visibility='visible';
                    }
                },

		});
		
	}

    
    function subtraction(candidate_id){
    $.ajax({
        
				type: "GET",
				url : "{{url('/quick-count/subtraction-starting-quick-count')}}" + '/' + candidate_id,
				success: function(msg){
                    console.log(msg);
                    $("#candidate_id_"+candidate_id).text(msg);
                },

		});
		
	}

    
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('quick-count') }}">Daftar Quick Count</a></li>
        <li class="breadcrumb-item active" aria-current="page">Proses Quick Count</li>
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
            Data Quick Count
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('quick-count') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-4">
                <div class="form-group">
                    <a class="text-dark">Nama Periode<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="hidden" name="quick_count_id" id="quick_count_id" value="{{$quickcount->quick_count_id}}" autocomplete="off" />
                    <input class="form-control input-bb" type="text" name="period_id" id="period_id" value="{{$quickcount->period_name}}" autocomplete="off" readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <a class="text-dark">Nama Lokasi<a class='red'> *</a></a>                        
                    <input class="form-control input-bb" type="text" name="location_id" id="location_id" value="{{$quickcount->location_name}}" autocomplete="off" readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <a class="text-dark">Nama TPU<a class='red'> *</a></a>                        
                    <input class="form-control input-bb" type="text" name="polling_station_id" id="polling_station_id" value="{{$quickcount->polling_station_name}}" autocomplete="off" readonly/>
                </div>
            </div>
        </div>
    </div>
</div>
    <form method="post" id="form_data" enctype="multipart/form-data">
        @csrf
        <div class="card border border-dark">
            <div class="card-header border-dark bg-dark">
                <h5 class="mb-0 float-left">
                    Proses Quick Count Kandidate
                </h5>
            </div>
            <div class=" example row form-group"> 
                @foreach($corecandidate as $key => $val)
                <div class="col-md-3" style="padding-left: 30px; padding-top: 20px; padding-right: 30px">
                        <div class="form-group" >
                            <div class="card">
                                <img class="card-img-top" src="{{ url('storage/candidate_photos/'.$val['candidate_photos']) }}" alt="Card image" width="255px" height="330px">
                                <div class="d-flex justify-content-center" style="margin-bottom: -10px; padding-top: 10px">
                                    <h5>
                                        @isset($corecandidate) {{$val->candidate_full_name}} @endisset
                                    </h5>
                                </div>
                                <div class="d-flex justify-content-center" style="margin-bottom: -10px">
                                    <h5>
                                        @isset($corecandidate) {{$val->candidate_nik}} @endisset
                                    </h5>
                                </div>
                                <div class="card-body d-flex justify-content-center" >
                                    <h1>
                                        <b style="color:#228B22" id="candidate_id_{{$val->candidate_id}}">
                                            {{$val->candidate_point}}
                                        </b>
                                    </h1>
                                </div>
                                <div class="d-flex justify-content-center" style="margin-bottom: 30px">
                                
                                    <a type="button" name="Subtraction" id="quick_count_subtraction{{$val->candidate_id}}" onclick='subtraction({{$val->candidate_id}});' class="btn btn-danger" style="margin-right: 10px" ><i class='fas fa-minus-circle'></i></a>
                        
                                    <a type="button" name="Sum" id="quick_count_sum{{$val->candidate_id}}" onclick='sum({{$val->candidate_id}});' class="btn btn-primary" ><i class='fas fa-plus-circle'></i></a>
                                </div>
                            </div>  
                        </div>
                    </div>
                @endforeach
                @if(count( $corecandidate ) == 0)
                <div class="container">  
                    <p style="padding-left:500px; padding-top: 25px"> Data Kosong</p>
                </div> 
                @endif
            </div>
    </form>
</div>
<br>
<br>
<br>
<br>


@stop

@section('footer')
    
@stop

@section('css')
    
@stop