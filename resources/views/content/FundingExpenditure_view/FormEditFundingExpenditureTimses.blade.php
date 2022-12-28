@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>

    function reset_add(){
		$.ajax({
				type: "GET",
				url : "{{route('add-funding-income-reset')}}",
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
        <li class="breadcrumb-item"><a href="{{ url('funding-income') }}">Daftar Pemasukan Keuangan Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Pemasukan Keuangan Timses</li>
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
            Form Edit Pemasukan Keuangan Timses
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('funding-expenditure-timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-edit-funding-expenditure-timses')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Kategori Pemasukan<a class='red'> *</a></a>
                        {!! Form::select('financial_category_id', $financialcategory, $fundingexpenditure->financial_category_id, ['class' => 'selection-search-clear select-form', 'id' => 'financial_category_id','' ])!!}
                        <input class="form-control input-bb" type="hidden" name="financial_flow_id" id="financial_flow_id" value="{{$fundingexpenditure->financial_flow_id}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Timses<a class='red'> *</a></a>
                        {!! Form::select('timses_member_id', $coretimsesmember, $fundingexpenditure->timses_member_id, ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_id','' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nominal<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="financial_flow_nominal" id="financial_flow_nominal" value="{{$fundingexpenditure->financial_flow_nominal}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Tanggal<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="date" name="financial_flow_date" id="financial_flow_date" value="{{$fundingexpenditure->financial_flow_date}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Keterangan<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="financial_flow_description" id="financial_flow_description" value="{{$fundingexpenditure->financial_flow_description}}" autocomplete="off" />
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