@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function function_elements_add(name, value){
		$.ajax({
				type: "POST",
				url : "{{route('add-candidate-elements')}}",
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
				url : "{{route('add-candidate-reset')}}",
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
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('candidate') }}">Daftar Data Kandidat</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Data Kandidat</li>
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
            Form Tambah Data Kandidat
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

    <form method="post" action="{{route('process-add-candidate')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama Lengkap<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_full_name" id="candidate_full_name" value="{{old('candidate_full_name')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama panggilan<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nick_name" id="candidate_nick_name" value="{{old('candidate_nick_name')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">NIK<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nik" id="candidate_nik" value="{{old('candidate_nik')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Kelamin<a class='red'> *</a></a>
                        {!! Form::select('candidate_gender', $gender, '', ['class' => 'selection-search-clear select-form', 'id' => 'candidate_gender','' ])!!}
                        {{-- <input class="form-control input-bb" type="text" name="gender" id="gender" value="{{old('gender')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off"/> --}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Alamat<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_address" id="candidate_address" value="{{old('candidate_address')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">No.Telp<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_phone_number" id="candidate_phone_number" value="{{old('candidate_phone_number')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tempat Lahir<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_birth_place" id="candidate_birth_place" value="{{old('candidate_birth_place')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tanggal Lahir<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="date" name="candidate_birth_date" id="candidate_birth_date" value="{{old('candidate_birth_date')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Periode<a class='red'> *</a></a>
                        {!! Form::select('period_id', $coreperiod, $nullcoreperiod, ['class' => 'selection-search-clear select-form', 'id' => 'period_id','' ])!!}                   
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Foto<a class='red'> </a></a>
                        <input class="form-control input-bb" type="file" name="candidate_photos" id="candidate_photos" value="{{old('candidate_photos')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off"/>
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