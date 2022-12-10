@inject('RecapitulationReport','App\Http\Controllers\RecapitulationReportController')
@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
     $(document).ready(function(){
        var financial_category_id = {!! json_encode($nullfinancialflow_category) !!};
        
        if(financial_category_id == null){
            $("#financial_category_id").select2("val", "0");
        }
    });
</script>
@stop

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page"> Daftar Rekapitulasi </li>
    </ol>
  </nav>

@stop

@section('content')
<?php
    $kategori=[
        '' => '',
        '1' => 'A',
        '2' => 'B',
    ];
?>
{{-- <h3 class="page-title">
    <b>Daftar Buku Besar </b> <small>Kelola Daftar Buku Besar  </small>
</h3> --}}
<div id="accordion">
    <form  method="post" action="{{ route('filter-report-recap') }}" enctype="multipart/form-data">
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
                                <input type="date" class="form-control input-bb" name="start_date" value="{{ $start_date }}">

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
                                <input type="date" class="form-control input-bb" name="end_date" value="{{ $end_date }}">
                            </div>
                        </div>
                        <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kategori
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financial_category_id', $listfinancialflow, $nullfinancialflow_category, ['class' => 'selection-search-clear select-form', 'id' => 'financial_category_id','' ])!!}
                            </div>
                        </div>
                        <div class = "col-md-3">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kepemilikan
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financial_flow_code', $code, '', ['class' => 'selection-search-clear select-form', 'id' => 'financial_flow_code','' ])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="form-actions float-right">
                        <a href="{{ route('filter-reset-report-income') }}" type="button" name="Reset" class="btn bg-yellow btn-sm"><i class="fas fa-sync"></i> Reset</a>
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

    <div class="card-body">
        <div class="table-responsive">
            <table id="" style="width:100%" class="table table-striped table-bordered table-hover table-full-width table-sm">
                <thead>
                    <tr>
                        <th width="5%" rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                        <th width="10%" rowspan="2" style="vertical-align : middle;text-align:center;">Tanggal</th>
                        <th width="10%" rowspan="2" style="vertical-align : middle;text-align:center;">Kepemilikan</th>
                        <th width="25%" rowspan="2" style="vertical-align : middle;text-align:center;">Deskripsi</th>
                        <th width="20%" rowspan="2" style="vertical-align : middle;text-align:center;">Nama Kategori</th>
                        <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">Pemasukan</th>
                        <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">Pengeluaran</th>
                        <th width="15%" colspan="2" style="vertical-align : middle;text-align:center;">Saldo</th>
                    </tr>
                    <tr>
                        <th width="15%" style="vertical-align : middle;text-align:center;">Pemasukan</th>
						<th width="15%" style="vertical-align : middle;text-align:center;">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="text-align: center" colspan="5">Saldo Awal</th>
                        <td></td>
                        <td></td>
                        {{-- <?php 
                            if($account['account_default_status']==0 || $accountbalancedetail_old['last_balance'] >= 0){  
                                if (isset($accountbalancedetail_old['last_balance'])) {
                                    if($accountbalancedetail_old['last_balance'] >= 0){
                                        echo "
                                            <td style='text-align: right'>".number_format($accountbalancedetail_old['last_balance'],2,'.',',')."</td>
                                            <td style='text-align: right'>0.00</td>
                                        ";
                                    } else {
                                        echo "
                                            <td style='text-align: right'>0.00</td>
                                            <td style='text-align: right'>".number_format($accountbalancedetail_old['last_balance'],2,'.',',')."</td>
                                        ";
                                    }
                                } else {
                                    echo "
                                        <td style='text-align: right'>0.00</td>
                                        <td style='text-align: right'>0.00</td>
                                    ";
                                }
                                
                            
                            } else {
                                if (isset($accountbalancedetail_old['last_balance'])) {
                                    if($accountbalancedetail_old['last_balance'] >= 0){
                                        echo "
                                            <td style='text-align: right'>0.00</td>
                                            <td style='text-align: right'>".number_format($accountbalancedetail_old['last_balance'],2,'.',',')."</td>
                                            
                                        ";
                                    } else {
                                        echo "
                                            <td style='text-align: right'>".number_format($accountbalancedetail_old['last_balance'],2,'.',',')."</td>
                                            <td style='text-align: right'>0.00</td>
                                        ";
                                    }
                                } else {
                                    echo "
                                        <td style='text-align: right'>0.00</td>
                                        <td style='text-align: right'>0.00</td>
                                    ";
                                }
                            }
                        ?>
                    </tr>
                    
                        <?php
                        $no = 1;
                        $voucher_debit = 0;
                        $voucher_credit = 0;
                        $last_balance_debit = 0;
                        $last_balance_credit = 0;
                        foreach ($acctgeneralledgerreport as $key => $val) {
                            if($val['data_state']==0){
                                echo "<tr>
                                    <td class='text-center'>".$no++.".</td>
                                    <td>".$val['date']."</td>
                                    <td>".$val['no_journal']."</td>
                                    <td>".$val['description']."</td>
                                    <td>".$AcctLedgerReport->getAccountName($val['account_id'])."</td>
                                    <td style='text-align: right'>".number_format($val['account_in'],2,'.',',')."</td>
                                    <td style='text-align: right'>".number_format($val['account_out'],2,'.',',')."</td>
                                    <td style='text-align: right'>".number_format($val['last_balance_debit'],2,'.',',')."</td>
                                    <td style='text-align: right'>".number_format($val['last_balance_credit'],2,'.',',')."</td>
                                ";
                            }
                                $voucher_debit += $val['account_in'];
                                $voucher_credit += $val['account_out'];
                                $last_balance_debit = $val['last_balance_debit'];
                                $last_balance_credit = $val['last_balance_credit'];
                        }
                        ?> --}}
                    
                    {{-- <tr>
                        <th style="text-align: center" colspan="5">Total Debet Kredit</th>
                        <?php
                            echo "
                                <td style='text-align: right'>".number_format($voucher_debit,2,'.',',')."</td>
                                <td style='text-align: right'>".number_format($voucher_credit,2,'.',',')."</td>
                            ";
                        ?>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th style="text-align: center" colspan="5">Saldo Akhir</th>
                        <td></td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($last_balance_debit,2,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($last_balance_credit,2,'.',',') }}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted">
        <div class="form-actions float-right">
            <a class="btn bg-red btn-sm" href="{{ url('/report-combine/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
            <a class="btn bg-olive btn-sm" href="{{ url('/report-combine/export') }}"><i class="fa fa-download"></i> Export Data</a>
        </div>
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