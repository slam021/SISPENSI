@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-timses-elements')}}",
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
				url : "{{route('add-timses-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}

    $(document).ready(function(){
        var user_group_id = {!! json_encode($nullsystemusergoup) !!};
        
        if(user_group_id == null){
            $("#user_group_id").select2("val", "0");
        }
    });

</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Data Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Akun Anggota Timses</li>
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
<?php 
    $systemuser += 1; 
    $timses_member_id = Request::segment(3);
?>
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah Akun Anggota
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-add-timses-member-account')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">User Group<a class='red'> *</a></a>
                        {!! Form::select('user_group_id', $systemusergroup, $nullsystemusergoup, ['class' => 'selection-search-clear select-form', 'id' => 'user_group_id','' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nama<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text"name="name" id="timses_name" value="{{old('name')}}" autocomplete="off" />
                        <input class="form-control input-bb" type="hidden"name="user_id" id="timses_user_id" value="{{$systemuser}}" autocomplete="off" readonly/> 
                        <input class="form-control input-bb" type="hidden"name="timses_member_id" id="timses_member_id" value="{{$timses_member_id}}" autocomplete="off" readonly/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Password<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text"name="password" id="timses_password" value="{{old('password')}}" autocomplete="off" />
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