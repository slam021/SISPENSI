@inject('RR','App\Http\Controllers\RecapitulationReportController')
@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    $(document).ready(function(){
        var financial_category_id = {!! json_encode($financial_category_id) !!};
        
        if(financial_category_id == null){
            $("#financial_category_id").select2("val", "0");
        }
    });
    $(document).ready(function(){
        var candidate_id = {!! json_encode($candidate_id) !!};
        
        if(candidate_id == null){
            $("#candidate_id").select2("val", "0");
        }
    });
    $(document).ready(function(){
        var timses_id = {!! json_encode($timses_id) !!};
        
        if(timses_id == null){
            $("#timses_id").select2("val", "0");
        }
    });
</script>
@stop

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Daftar Laporan Rekapitulasi </li>
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
                        <div class = "col-md-6">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kepemilikan
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financialflow_list', $code, $financialflow_list, ['class' => 'selection-search-clear select-form', 'id' => 'financialflow_list' ])!!}
                            </div>
                        </div>
                        <div class = "col-md-6">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Kategori
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financial_category_id', $listfinancialcategory, $financial_category_id, ['class' => 'selection-search-clear select-form', 'id' => 'financial_category_id','' ])!!}
                            </div>
                        </div>
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
                        <a href="{{ route('filter-reset-report-recap') }}" type="button" name="Reset" class="btn bg-yellow btn-sm"><i class="fas fa-sync"></i> Reset</a>
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
                        <th width="8%" rowspan="2" style="vertical-align : middle;text-align:center;">Tanggal</th>
                        <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">Kepemilikan</th>
                        <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">Deskripsi</th>
                        <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">Kategori</th>
                        <th width="13%" rowspan="2" style="vertical-align : middle;text-align:center;">Pemasukan</th>
                        <th width="13%" rowspan="2" style="vertical-align : middle;text-align:center;">Pengeluaran</th>
                        <th width="13%" rowspan="2" style="vertical-align : middle;text-align:center;">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3"></td>
                        <th style="text-align: center">Saldo Awal</th>
                        <td colspan="3"></td>

                        @if($financialflow_list != "" || $financial_category_id != "")
                            @if($financialflow_list == 1)
                                @if($last_balance_candidate_old['last_balance_candidate'] >= 0)
                                    <th style='text-align: right'>{{number_format($last_balance_candidate_old['last_balance_candidate'],2,'.',',')}}</th>
                                @else 
                                    <th style='text-align: right'>0,00</th>
                                @endif   
                            @else
                                @if($last_balance_timses_old['last_balance_timses'] >= 0)
                                    <th style='text-align: right'>{{number_format($last_balance_timses_old['last_balance_timses'],2,'.',',')}}</th>
                                @else
                                    <th style='text-align: right'>0,00</th>
                                @endif
                            @endif
                        @else
                            <th style='text-align: right'>0,00</th>
                        @endif
                    </tr> 

                @php
                    $no = 1;
                    $total_nominal_in = 0;
                    $total_nominal_out = 0;
                    $saldo_candidate = $last_balance_candidate_old['last_balance_candidate'];
                    $saldo_timses = $last_balance_timses_old['last_balance_timses'];
                @endphp 

                @foreach($financialflow as $key => $val)
                    @php
                        if($val['financial_category_type'] == 1){
                            if($val['candidate_id']){
                                $saldo_candidate += $val['financial_flow_nominal'];
                            }else{
                                $saldo_timses += $val['financial_flow_nominal'];
                            }
                        }else{
                            if($val['candidate_id']){
                                $saldo_candidate -= $val['financial_flow_nominal'];
                            }else{
                                $saldo_timses -= $val['financial_flow_nominal'];
                            }
                        }
                    @endphp
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['financial_flow_date']}}</td>
                        @if($val['candidate_id'])
                        <td>{{$RR->getCandidateName($val['candidate_id'])}}</td>
                        @else
                        <td>{{$RR->getTimsesName($val['timses_id'])}}</td>
                        @endif
                        <td></td>
                        <td>{{$RR->getCategoryName($val['financial_category_id'])}}</td>
                        @if($val['financial_category_type']==1)
                            <td style='text-align:right'>{{rupiah($val['financial_flow_nominal'])}}</td>
                        @else
                            <td style='text-align:right'>0,00</td>
                        @endif

                        @if($val['financial_category_type']==2)
                            <td style='text-align:right'>{{rupiah($val['financial_flow_nominal'])}}</td>
                        @else
                            <td style='text-align:right'>0,00</td>
                        @endif

                        @if($val['candidate_id'])
                            <td style='text-align:right'>{{rupiah($saldo_candidate)}}</td>
                        @else
                            <td style='text-align:right'>{{rupiah($saldo_timses)}}</td>
                        @endif
                    </tr>
                    @php 
                        $no++; 
                        
                        if($val['financial_category_type'] == 1){
                            $total_nominal_in += $val['financial_flow_nominal'];
                        }else{
                            $total_nominal_out += $val['financial_flow_nominal'];
                        } 
                        $financialflow_array = array();
                        array_push($financialflow_array, $val['candidate_id']);
                    @endphp
                @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <th style="text-align: center" colspan="3">Total Pemasukan Pengeluaran</th>
                            <th style="text-align: right">{{rupiah($total_nominal_in)}}</th>
                            <th style="text-align: right">{{rupiah($total_nominal_out)}}</th>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <th style="text-align: center">Saldo Akhir</th>
                        @if($financialflow_list != "" && $financial_category_id != "" )
                            @if($financialflow_array)
                                <th style="text-align: right" colspan="4">{{rupiah($saldo_candidate)}}</th>
                            @else
                                <th style="text-align: right" colspan="4">{{rupiah($saldo_timses)}}</th>
                            @endif
                        @else
                            <th style="text-align: right" colspan="4">0,00</th>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted">
        <div class="form-actions float-right">
            <a class="btn bg-orange btn-sm" href="{{ url('/report-recap/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
            <a class="btn bg-olive btn-sm" href="{{ url('/report-recap/export') }}"><i class="fa fa-download"></i> Export Data</a>
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