@inject('CDI', 'App\Http\Controllers\CoreDapilController')

@extends('adminlte::page')
@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-dapil-elements')}}",
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
				url : "{{route('add-dapil-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}

    $(document).ready(function(){
        $("#province_id").select2("val", "0");
        $("#province_id").change(function(){
			var province_id 	= $("#province_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('dapil-city')}}",
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
        $("#city_id").select2("val", "0");
        $("#city_id").change(function(){
			var city_id 	= $("#city_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('dapil-district')}}",
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
        $("#kecamatan_id").select2("val", "0");
        $("#kecamatan_id").change(function(){
			var kecamatan_id 	= $("#kecamatan_id").val();
                $.ajax({
                    type: "POST",
                    url : "{{route('dapil-village')}}",
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
        <li class="breadcrumb-item"><a href="{{ url('section') }}">Daftar Data Dapil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Data Daerah Bagian Dapil</li>
    </ol>
</nav>

@stop

@section('content')
@if(session('msg'))
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-hidden='true'></button>	
    {{session('msg')}}
</div>
@endif
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Tambah Data Bagian Dapil
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('dapil') }}'" name="back" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <?php 
        $dapil_id = Request::segment(3);
    ?>

    <form method="post" action="{{route('process-add-dapil-item')}}" enctype="multipart/form-data">
    @csrf
        <div class="card-body">
            <div class="row form-group">
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Lokasi<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="dapil_name" id="dapil_name" value="{{old('dapil_name')}}"  autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-6"></div> --}}
                <div class="col-md-6">
                    <div class="form-group">	
                        <a class="text-dark">Provinsi<a class='red'> </a></a>
                        {!! Form::select('province_id', $province, 0, ['class' => 'selection-search-clear select-form', 'id' => 'province_id','' ])!!}
                        <input class="form-control input-bb" type="hidden" name="dapil_id" id="dapil_id" value="{{$dapil_id}}"  autocomplete="off"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kabupaten/Kota<a class='red'> </a></a>
                        <select class="selection-search-clear" name="city_id" id="city_id" style="width: 100% !important" >
                        </select>
                        {{-- <input class="form-control input-bb" type="hidden" name="city_id" id="city_id" value="{{$coredapil['city_id']}}"  autocomplete="off"/> --}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kecamatan<a class='red'> </a></a>
                        <select class="selection-search-clear" name="kecamatan_id" id="kecamatan_id" style="width: 100% !important" >
                        </select>
                        {{-- <input class="form-control input-bb" type="hidden" name="kecamatan_id" id="kecamatan_id" value="{{$coredapil['kecamatan_id']}}"  autocomplete="off"/> --}}
                        {{-- {!! Form::select('', $districts, '', ['class' => 'selection-search-clear select-form', 'id' => 'kecamatan_id']) !!} --}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kelurahan/Desa<a class='red'> </a></a>
                        <select class="selection-search-clear" name="kelurahan_id" id="kelurahan_id" style="width: 100% !important" >
                        </select>
                        {{-- <input class="form-control input-bb" type="hidden" name="kelurahan_id" id="kelurahan_id" value="{{$coredapil['kelurahan_id']}}"  autocomplete="off"/> --}}
                        {{-- {!! Form::select('', $villages, '', ['class' => 'selection-search-clear select-form', 'id' => 'kelurahan_id']) !!} --}}
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
    </form>
</div>
    <div class="card border border-dark">
        <div class="card-header bg-dark clearfix">
            <h5 class="mb-0 float-left">
                Mengelola Data Bagian Dapil  
            </h5>
        </div>
    
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-sm table-striped table-bordered table-hover table-full-width">
                    <thead>
                        <tr>
                            <th width="2%" style='text-align:center'>No</th>
                            <th width="5%" style='text-align:center'>Provinsi</th>
                            <th width="5%" style='text-align:center'>Kota/Kabupaten</th>
                            <th width="5%" style='text-align:center'>Kecamatan</th>
                            <th width="5%" style='text-align:center'>Desa/Kelurahan</th>
                            <th width="3%" style='text-align:center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($coredapilitem as $val)
                        
                        <tr>
                            <td style='text-align:center'>{{$no}}</td>
                            <td>{{$CDI->getProvinceName($val['province_id'])}}</td>
                            <td>{{$CDI->getCityName($val['city_id'])}}</td>
                            <td>{{$CDI->getDistrictName($val['kecamatan_id'])}}</td>
                            <td>{{$CDI->getVillageName($val['kelurahan_id'])}}</td>
                            <td class="" style='text-align:center'>
                                <a type="button" class="badge badge-danger btn-sm" href="{{ url('/dapil/delete-dapil-item/'.$val['dapil_item_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                            </td>
                        </tr>
                        {{-- {{print_r($corelocation)}} --}}
    
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