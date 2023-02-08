@inject('FundingExpenditure', 'App\Http\Controllers\FundingExpenditureController')

@extends('adminlte::page')

@section('title', 'Sistem Timses Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Pengeluaran Keuangan Timses</li>
    </ol>
</nav>
@stop

<?php 
    $type =[
        ''  => '',
        '1' => 'Pemasukan',
        '2' => 'Pengeluaran',
    ];
?>

@section('content')
{{-- <h3 class="page-title">
    <b>Daftar Pengeluaran Keuangan Timses</b>
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
            Mengelola Data Pengeluaran Keuangan Timses
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('funding-expenditure-timses/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Pengeluaran Timses</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Tanggal</th>
                        <th width="10%" style='text-align:center'>Kategori Pengeluaran</th>
                        <th width="10%" style='text-align:center'>Timses</th>
                        <th width="10%" style='text-align:center'>Nominal</th>
                        <th width="10%" style='text-align:center'>Keterangan</th>
                        <th width="3%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                        function rupiah($angka){
                            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                            return $hasil_rupiah;
                        }
                    ?>
                    @foreach($fundingexpenditure as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{date('d/m/Y', strtotime($val['financial_flow_date']))}}</td>
                        <td>{{$val['financial_category_name']}}</td>
                        <td>{{$val['timses_name']}}</td>
                        <td style='text-align:right'>{{rupiah($val['financial_flow_nominal'])}}</td>
                        <td>{{$val['financial_flow_description']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/funding-expenditure-timses/edit/'.$val['financial_flow_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/funding-expenditure-timses/delete-funding-expenditure/'.$val['financial_flow_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    
@stop

@section('css')
    
@stop

@section('js')
    
@stop