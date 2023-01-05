@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
     $(document).ready(function(){
        var dapil_category_id = {!! json_encode($nulldapilcategory) !!};
        
        if(dapil_category_id == null){
            $("#dapil_category_id").select2("val", "0");
        }
    });
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('quick-count') }}">Daftar Data Dapil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Data Dapil</li>
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
            Form Tambah Dapil
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('dapil') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{route('process-add-dapil')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Tingkat Pemilihan<a class='red'> *</a></a>
                        {{-- <input class="form-control input-bb" type="text" name="dapil_category_id" id="dapil_category_id" value="{{old('dapil_category_id')}}"  autocomplete="off" /> --}}
                        {!! Form::select('dapil_category_id', $listdapilcategory, $nulldapilcategory, ['class' => 'selection-search-clear select-form', 'id' => 'dapil_category_id','required' ])!!}

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Dapil<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="dapil_name" id="dapil_name" value="{{old('dapil_name')}}"  autocomplete="off" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger btn-sm" value="reset"><i class="fa fa-times"></i> Batal</button>
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