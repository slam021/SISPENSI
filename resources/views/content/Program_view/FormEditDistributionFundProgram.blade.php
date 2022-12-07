@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-program-elements')}}",
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
				url : "{{route('add-program-reset')}}",
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
        <li class="breadcrumb-item"><a href="{{ url('program') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item"><a href="{{ url('distribution-fund') }}">Penyaluran Dana </a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Penyaluran Dana </li>
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
            Form Edit Penyaluran Dana
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('program/distribution-fund/'.$programdistributionfund->program_id.'/'.$programdistributionfund->timses_id) }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    {{-- <?php 
        $gender =[
            ''  => '',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];
    ?> --}}

    <form method="post" action="{{route('process-edit-distribution-fund')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text"name="timses_name" id="timses_name" value="{{$programdistributionfund->timses_name}}" readonly />
                        <input class="form-control input-bb" type="text" name="program_id" id="program_id" value="{{$programdistributionfund->program_id}}" autocomplete="off" />
                        <input class="form-control input-bb" type="text" name="timses_id" id="timses_id" value="{{$programdistributionfund->timses_id}}" autocomplete="off" />
                        <input class="form-control input-bb" type="text" name="distribution_fund_id" id="distribution_fund_id" value="{{$programdistributionfund->distribution_fund_id}}" autocomplete="off" />
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Anggota Timses<a class='red'> *</a></a>
                        {!! Form::select('timses_member_id', $membertimses, $programdistributionfund->timses_member_id, ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_id','' ])!!} 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Akun<a class='red'> *</a></a>
                        {!! Form::select('user_id', $systemuser, $programdistributionfund->user_id, ['class' => 'selection-search-clear select-form', 'id' => 'user_id','' ])!!} 
                        {{-- <input class="form-control input-bb" type="text" name="user_id" id="user_id" value="{{$programdistributionfund->user_id}}" autocomplete="off" readonly/> --}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nominal<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="distribution_fund_nominal" id="distribution_fund_nominal" value="{{$programdistributionfund->distribution_fund_nominal}}"  autocomplete="off" />
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