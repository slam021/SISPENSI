@inject('DTMR', 'App\Http\Controllers\DataTimsesMemberReportController')

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
        <li class="breadcrumb-item active" aria-current="page">Daftar Laporan Data Anggota Timses</li>
    </ol>
</nav>
@stop

<?php 
    // dd($programtimsesactivity);
?>

@section('content')
{{-- <h3 class="page-title">
    <b>Daftar Pemasukan Keuangan</b>
</h3> --}}
@if(session('msg'))
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif 
    
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            List Laporan Data Anggota Timses
        </h5>
        {{-- <div class="form-actions float-right">
            <button onclick="location.href='{{ url('funding-income-timses/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Pemasukan Keuangan Baru</button>
        </div> --}}
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama Anggota</th>
                        <th width="7%" style='text-align:center'>NIK</th>
                        <th width="10%" style='text-align:center'>Tempat Lahir</th>
                        <th width="10%" style='text-align:center'>Tanggal Lahir</th>
                        <th width="10%" style='text-align:center'>Umur</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="10%" style='text-align:center'>Agama</th>
                        <th width="10%" style='text-align:center'>No Telp</th>
                        <th width="10%" style='text-align:center'>Kelamin</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach($coretimsesmember as $key => $val)
                    <?php  
                        $no = 1;

                        $lahir = new DateTime(date($val['timses_member_date_of_birth']));
                        $today = new DateTime('today');
                        $umur  = $today->diff($lahir);

                        $gender =[
                                    ''  => '',
                                    '1' => 'Laki-laki',
                                    '2' => 'Perempuan',
                                ];
                    ?>
                    <tr>
                        <td style='text-align:center'>{{$no + $key}}</td>
                        <td>{{$val['timses_member_name']}}</td>
                        <td>{{$val['timses_member_nik']}}</td>
                        <td>{{$val['timses_member_place_of_birth']}}</td>
                        <td>{{date('d/m/Y', strtotime($val['timses_member_date_of_birth']))}}</td>
                        <td>{{$umur->y.' Tahun'}}</td>
                        <td>{{$val['timses_member_address']}}</td>
                        <td>{{$val['timses_member_religion']}}</td>
                        <td>{{$val['timses_member_phone']}}</td>
                        <td>{{$gender[$val['timses_member_gender']]}}</td>
                    </tr>
                    {{-- <?php $no++; ?> --}}
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <a class="btn bg-orange btn-sm" href="{{ url('/report-data-member/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
                <a class="btn bg-olive btn-sm" href="{{ url('/report-data-member/export') }}"><i class="fa fa-download"></i> Export Data</a>
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