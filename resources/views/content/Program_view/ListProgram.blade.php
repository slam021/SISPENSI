@inject('Program', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Acara</li>
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
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Acara 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('program/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Acara Baru</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="5%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Penyelenggara</th>
                        <th width="10%" style='text-align:center'>Kandidate</th>
                        <th width="10%" style='text-align:center'>Timses</th>
                        <th width="10%" style='text-align:center'>Nama Acara</th>
                        <th width="10%" style='text-align:center'>Deskripsi</th>
                        <th width="10%" style='text-align:center'>Lokasi</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="10%" style='text-align:center'>Tanggal</th>
                        <th width="10%" style='text-align:center'>Dana</th>
                        <th width="10%" style='text-align:center'>Periode</th>
                        {{-- <th width="10%" style='text-align:center'>Status</th> --}}
                        <th width="10%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 

                        function rupiah($angka){
                            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                            return $hasil_rupiah;
                        }

                        $organizer =[
                            ''  => '',
                            '1' => 'Kandidat',
                            '2' => 'Timses',
                        ];
                    ?>
                    @foreach($program as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$organizer[$val['program_organizer']]}}</td>
                        <td>{{$val['candidate_full_name']}}</td>
                        @if ($val['program_organizer'] == 1)
                            <td style='text-align:center'>{{'_'}}</td>
                        @else
                            <td>{{$Program->getTimsesName($val['timses_id'])}}</td>
                        @endif
                        <td>{{$val['program_name']}}</td>
                        <td>{{$val['program_description']}}</td>
                        <td>{{$val['location_name']}}</td>
                        <td>{{$val['program_address']}}</td>
                        <td>{{$val['program_date']}}</td>
                        <td>{{rupiah($val['program_fund'])}}</td>
                        <td>{{$val['period_name']}}</td>
                        {{-- <td><img width="150px" src="{{ url('/program-photos/'.$val['photos']) }}"></td> --}}
                        <td class="" style='text-align:left'>
                            <?php
                                if($val['program_status'] == 0){
                                    echo "<a type='button' class='badge badge-warning' href='".url('/program/edit/'.$val['program_id'])."' title='Edit Acara'><i class='fas fa-edit'></i> Edit</a>";
                                }else{
                                    
                                }
                            ?>
                            <a type="button" class="badge bg-lime" href="{{ url('/program/detail/'.$val['program_id'])}}" title="Detail Acara"><i class='fas fa-list-ul'></i> Detail</a>
                            <?php
                            if ($val['program_organizer'] == 1){

                            }else{
                                if($val['program_status'] == 0){
                                    echo "<a type='button' class='badge bg-olive' href='".url('/program/distribution-fund/'.$val['program_id'])."' title='Penyaluran Dana'><i class='fas fa-money-bill-alt'></i> Penyaluran</a>";
                                }else{
                                    
                                }
                            }
                            ?>
                            <?php
                                if($val['program_status'] == 0){
                                    echo "<a type='button' class='badge bg-indigo' href='".url('/program/add-program-support/'.$val['program_id'])."' title='Tambah Pendukung'><i class='fas fa-user'></i> Pendukung</a>";
                                }else{
                                    
                                }
                            ?>
                            <?php
                                if($val['program_status'] == 0){
                                    echo "<a type='button' class='badge bg-navy' href='".url('/program/closing-program/'.$val['program_id'])."' title='Tutup Acara' onClick='javascript:return confirm(\"apakah Anda yakin ingin Menutup Acara ?\")'><i class='fa fa-exclamation-circle'></i> Tutup Acara</a>";
                                }else{
                                    
                                }
                            ?>
                            <a type="button" class="badge bg-blue" href="{{ url('/program/documentation-program/'.$val['program_id'])}}" title="Dokumentasi Acara"><i class='far fa-image'></i> Dokumentasi</a>
                            <a type="button" class="badge badge-danger" href="{{ url('/program/delete-program/'.$val['program_id']) }}" title="Hapus Acara"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
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