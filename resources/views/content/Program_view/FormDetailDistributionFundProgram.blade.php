@inject('DDF', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Penyaluran Dana Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Alokasi Dana Acara</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Acara</b>
</h3>
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
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Data Penyelenggara
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('program/distribution-fund/'.$programdistributionfund['program_id']) }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    {{-- <?php 
        if (empty($coresection)){
            $coresection['section_name'] = '';
        }
    ?> --}}
    
    <?php 
        $organizer =[
            ''  => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];

        function rupiah($angka){
            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
            return $hasil_rupiah;
        }
    ?>

    <form method="post" action="{{route('process-add-program')}}" enctype="multipart/form-data">
        @csrf
            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Penyelenggara<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{$organizer[$program['program_organizer']]}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" readonly />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{$program['timses_name']}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" readonly/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Anggota Timses<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{$membertimses['timses_member_name']}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" readonly />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Dana Diterima<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{rupiah($programdistributionfund['distribution_fund_nominal'])}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>

<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Alokasi Dana Acara 
        </h5>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="4%" style='text-align:center'>No</th>
                        <th width="8%" style='text-align:center'>Tanggal</th>
                        <th width="10%" style='text-align:center'>Nama Kegiatan</th>
                        <th width="10%" style='text-align:center'>Dana Digunakan</th>
                        <th width="15%" style='text-align:center'>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 

                        $organizer =[
                            ''  => '',
                            '1' => 'Kandidat',
                            '2' => 'Timses',
                        ];
                    ?>
                    @foreach($programtimsesactivity as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_activity_date']}}</td>
                        <td>{{$val['timses_activity_name']}}</td>
                        <td style='text-align:right'>{{rupiah($val['timses_activity_fund'])}}</td>
                        <td>{{$val['timses_activity_description']}}</td>
                    </tr>

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

@section('js')
    
@stop