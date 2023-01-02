@inject('CDI', 'App\Http\Controllers\CoreDapilController')

@extends('adminlte::page')
@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('section') }}">Daftar Data Dapil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data Dapil</li>
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
            Detail Data Dapil
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
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Tingkat Pemilihan<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="dapil_category_id" id="dapil_category_id" value="{{ $coredapil->dapil_category_name}}"  autocomplete="off" readonly />
                        {{-- {!! Form::select('dapil_category_id', $listdapilcategory, $nulldapilcategory, ['class' => 'selection-search-clear select-form', 'id' => 'dapil_category_id','required' ])!!} --}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Dapil<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="dapil_name" id="dapil_name" value="{{$coredapil->dapil_name}}"  autocomplete="off" readonly />
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
    <div class="card border border-dark">
        <div class="card-header bg-dark clearfix">
            <h5 class="mb-0 float-left">
                Detail Data Bagian Dapil  
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