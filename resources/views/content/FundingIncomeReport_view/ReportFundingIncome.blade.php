@inject('ReportFundingIncome', 'App\Http\Controllers\FundingIncomeReportController')

@extends('adminlte::page')

@section('title', 'Sistem Timses Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Laporan Pemasukan</li>
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
    <b>Daftar Pemasukan Keuangan</b>
</h3> --}}
@if(session('msg'))
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif 
    <form  method="post" action="{{ route('filter-report-income') }}" enctype="multipart/form-data">
        @csrf
            <div class="card border border-dark">
            <div class="card-header bg-dark" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="mb-0">
                    Filter
                </h5>
                {{-- <div class="form-actions float-right">
                    <button onclick="location.href='{{ url('funding-income-timses/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Kembali</button>
                </div> --}}
            </div>
        
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div class = "row">
                        <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tanggal Mulai
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="date" class="form-control" name="start_date">

                                {{-- <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" name="start_date" id="start_date"  style="width: 15rem;"/> --}}
                            </div>
                        </div>
    
                        <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tanggal Akhir
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="date" class="form-control" name="end_date">

                                {{-- <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" name="end_date" id="end_date"  style="width: 15rem;"/> --}}
                            </div>
                        </div>
    
                        {{-- <div class = "col-md-6">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Nama Pemasok
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <select  class="form-control "  type="text" name="end_date" id="end_date" onChange="function_elements_add(this.name, this.value);" value="" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
    
                        <div class = "col-md-6">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Nama Gudang
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <select class="form-control"  type="text" name="end_date" id="end_date" onChange="function_elements_add(this.name, this.value);" value="">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="form-actions float-right">
                        <button type="reset" name="Reset" class="btn btn-danger btn-sm" onclick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" name="Find" class="btn btn-primary btn-sm" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
            </div>
        </form>
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Laporan Pemasukan
        </h5>
        {{-- <div class="form-actions float-right">
            <button onclick="location.href='{{ url('funding-income-timses/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Pemasukan Keuangan Baru</button>
        </div> --}}
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Kategori Pemasukan</th>
                        <th width="10%" style='text-align:center'>Timses</th>
                        <th width="10%" style='text-align:center'>Nominal</th>
                        <th width="10%" style='text-align:center'>Tanggal</th>
                        <th width="10%" style='text-align:center'>Keterangan</th>
                        {{-- <th width="3%" style='text-align:center'>Aksi</th> --}}
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
                    @foreach($fundingincome as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['financial_category_name']}}</td>
                        <td>{{$val['timses_name']}}</td>
                        <td>{{rupiah($val['financial_flow_nominal'])}}</td>
                        <td>{{$val['financial_flow_date']}}</td>
                        <td>{{$val['financial_flow_description']}}</td>
                        {{-- <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning btn-sm" href="{{ url('/funding-income-timses/edit/'.$val['financial_flow_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge badge-danger btn-sm" href="{{ url('/funding-income-timses/delete-funding-income/'.$val['financial_flow_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td> --}}
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <a class="btn btn-danger btn-sm" href="{{ url('purchase-invoice-by-item-report/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
                <a class="btn btn-success btn-sm" href="{{ url('purchase-invoice-by-item-report/export') }}"><i class="fa fa-download"></i> Export Data</a>
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