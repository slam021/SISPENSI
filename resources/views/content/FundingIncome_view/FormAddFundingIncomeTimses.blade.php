@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-funding-income-elements')}}",
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
				url : "{{route('add-funding-income-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}

    function toRp(angka){
        var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
        var rev2    = '';
        for(var i = 0; i < rev.length; i++){
            rev2  += rev[i];
            if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                rev2 += '.';
            }
        }
        return rev2.split('').reverse().join('') + ',00';
    }
    
    function rupiahSave(value){
        var rupiah_save = document.getElementById("financial_flow_nominal");
        var rupiah_view = document.getElementById("financial_flow_nominal_view");
        if(rupiah_view){
            document.getElementById('financial_flow_nominal').value = rupiah_view.value;
            document.getElementById('financial_flow_nominal_view').value =  toRp(rupiah_view.value);
        }
    }

    $(document).ready(function(){
        var financial_category_id = {!! json_encode($nullfinancialcategory) !!};
        
        if(financial_category_id == null){
            $("#financial_category_id").select2("val", "0");
        }
    });

    $(document).ready(function(){
        var timses_id = {!! json_encode($nullcoretimses) !!};
        
        if(timses_id == null){
            $("#timses_id").select2("val", "0");
        }
    });
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('funding-income') }}">Daftar Pemasukan Keuangan Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Pemasukan Keuangan Timses</li>
    </ol>
</nav>

@stop

<?php 
//Pemasukan
    $categorytype = 1;
?>

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
            Form Tambah Pemasukan Keuangan Timses
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('funding-income-timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-add-funding-income-timses')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Kategori Pemasukan<a class='red'> *</a></a>
                        {!! Form::select('financial_category_id', $financialcategory, $nullfinancialcategory, ['class' => 'selection-search-clear select-form', 'id' => 'financial_category_id','' ])!!}
                        <input class="form-control input-bb" type="hidden" name="financial_category_type" id="financial_category_type" value="{{$categorytype}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Timses<a class='red'> *</a></a>
                        {!! Form::select('timses_id', $coretimses, $nullcoretimses, ['class' => 'selection-search-clear select-form', 'id' => 'timses_id','' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nominal<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="financial_flow_nominal_view" id="financial_flow_nominal_view" value="{{old('financial_flow_nominal_view')}}" autocomplete="off" onchange="rupiahSave();" />
                        <input class="form-control input-bb" type="hidden" name="financial_flow_nominal" id="financial_flow_nominal" value="{{old('financial_flow_nominal')}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Tanggal<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="date" name="financial_flow_date" id="financial_flow_date" value="{{old('financial_flow_date')}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Keterangan<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="financial_flow_description" id="financial_flow_description" value="{{old('financial_flow_description')}}" autocomplete="off" />
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