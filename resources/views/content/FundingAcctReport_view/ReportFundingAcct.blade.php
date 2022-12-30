@inject('RFA','App\Http\Controllers\FundingAcctReportController')
@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
</script>
@stop

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Daftar Laporan Perhitungan Keuangan </li>
    </ol>
</nav>

@stop

@section('content')
<?php
    function rupiah($angka){
        $hasil_rupiah = number_format($angka,2,',','.');
        return $hasil_rupiah;
    } 
?>
{{-- <h3 class="page-title">
    <b>Daftar Buku Besar </b> <small>Kelola Daftar Buku Besar  </small>
</h3> --}}
<div id="accordion">
    <form  method="post" action="{{ route('filter-report-funding') }}" enctype="multipart/form-data">
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
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Periode Awal
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select(0, $monthlist, $start_month, ['class' => 'selection-search-clear select-form', 'id' => 'start_month', 'name' => 'start_month']) !!}
                            </div>
                        </div>
    
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Periode Akhir
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select(0, $monthlist, $end_month, ['class' => 'selection-search-clear select-form', 'id' => 'end_month', 'name' => 'end_month']) !!}
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tahun
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select(0, $yearlist, $year, ['class' => 'selection-search-clear select-form', 'id' => 'year', 'name' => 'year']) !!}
                            </div>
                        </div>

                        {{-- <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tanggal Mulai
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="month" class="form-control input-bb" id="start_month" name="start_month" value="{{ $start_month }}">
                            </div>
                        </div>
    
                        <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tanggal Akhir
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="month" class="form-control input-bb"  id="end_month" name="end_month" value="{{ $end_month }}">
                            </div>
                        </div> --}}
                        {{-- <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kepemilikan
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financialflow_list', $code, $financialflow_list, ['class' => 'selection-search-clear select-form', 'id' => 'financialflow_list' ])!!}
                            </div>
                        </div> --}}
                        {{-- <div class = "col-md-6">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kategori
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financial_category_id', $listfinancialcategory, $financial_category_id, ['class' => 'selection-search-clear select-form', 'id' => 'financial_category_id','' ])!!}
                            </div>
                        </div> --}}
                        {{-- <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Nama Kandidat
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('candidate_id', $listcorecandidate, $candidate_id, ['class' => 'selection-search-clear select-form', 'id' => 'candidate_id','' ])!!}
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Nama Timses
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('timses_id', $listcoretimses, $timses_id, ['class' => 'selection-search-clear select-form', 'id' => 'timses_id','' ])!!}
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="form-actions float-right">
                        <a href="{{ route('filter-reset-report-funding') }}" type="button" name="Reset" class="btn bg-yellow btn-sm"><i class="fas fa-sync"></i> Reset</a>
                        <button type="submit" name="Find" class="btn btn-primary btn-sm" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
            </div>
        </form>
</div>
@if(session('msg'))
<div class="alert alert-info" role="alert">
    {{session('msg')}}
</div>
@endif 
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar
        </h5>
    </div>

    <div class="container"> 
    <div class="card-body">
        <div class="table-responsive pt-3">
            <table id="" style="width:100%" class="table table-bordered table-hover" class="table table-hover table-bordered table-full-width table-responsive-sm">
                <thead>
                    <tr>
                        <td colspan='2' style='text-align:center;'>
                            <div style='font-weight:bold'>Laporan Perhitungan Keuangan
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' style='text-align:center;'>
                            <div style='font-weight:bold'>
                                Periode {{ $monthlist[$start_month] }} s.d. {{  $monthlist[$end_month] }}  {{ $year }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'></td>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td colspan='2' style='font-weight:bold'>Rincian Pemasukan</td>
                    </tr> --}}
                    <tr>
                        <td colspan='2' style='font-weight:bold; padding-right:1px'>Kategori Pemasukan</td>
                    </tr>
                    @php
                        $total_nominal_income = 0;
                        $total_nominal_expenditure = 0;
                        $last_balance_acct = 0;
                    @endphp
                    @foreach ($category_income as $key => $val)
                        <tr>
                            <td colspan=''>&emsp;&emsp;{{$val['financial_category_name']}}</td>
                            <td style='text-align:right;'>{{rupiah($RFA->getFinanciaLFlowNominal($val->financial_category_id))}}</td>
                        </tr>
                    @php
                        $total_nominal_income += $RFA->getFinanciaLFlowNominal($val->financial_category_id); 
                    @endphp
                    @endforeach
                    <tr>
                        <td style='font-weight:bold'>Total Pemasukan</td>
                        <td style='font-weight:bold; text-align:right;'>{{rupiah($total_nominal_income)}}</td>
                    </tr>
                    <tr>
                        <td colspan='2'></td>
                    </tr>
                    {{-- <tr>
                        <td colspan='2' style='font-weight:bold'>Rincian Pengeluaran</td>
                    </tr> --}}
                    <tr>
                        <td colspan='2' style='font-weight:bold'>Kategori Pengeluaran</td>
                    </tr>
                    @foreach ($category_expenditure as $key => $val)
                    <tr>
                        <td>&emsp;&emsp;{{$val['financial_category_name']}}</td>    
                        <td style='text-align:right;'>{{rupiah($RFA->getFinanciaLFlowNominal($val->financial_category_id))}}</td>    
                    </tr>
                    @php
                        $total_nominal_expenditure += $RFA->getFinanciaLFlowNominal($val->financial_category_id); 
                        $last_balance_acct = $total_nominal_income - $total_nominal_expenditure;
                    @endphp
                    @endforeach
                    <tr>
                        <td style='font-weight:bold'>Total Pengeluaran</td>
                        <td style='font-weight:bold; text-align:right;'>{{rupiah($total_nominal_expenditure)}}</td>
                    </tr>
                    <tr>
                        <td colspan='2'></td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width: 80%">Sisa Saldo</td>
                        <td style='font-weight:bold; text-align:right;'>{{rupiah($last_balance_acct)}}</td>
                        {{-- <th style="width: 20%; text-align: right">{{ number_format($grand_total_account_amount1 - $grand_total_account_amount2,2,'.',',') }}</th> --}}
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <div class="card-footer text-muted">
        <div class="form-actions float-right">
            <a class="btn bg-orange btn-sm" href="{{ url('/report-funding/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
            <a class="btn bg-olive btn-sm" href="{{ url('/report-funding/export') }}"><i class="fa fa-download"></i> Export Data</a>
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