@inject('ReportFC', 'App\Http\Controllers\FundingCombineReportController')

@extends('adminlte::page')

@section('title', 'Sistem Timses Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Lap Pemasukan & Pengeluaran</li>
    </ol>
</nav>
@stop

{{-- <?php 
    $type =[
        ''  => '',
        '1' => 'Pemasukan',
        '2' => 'Pengeluaran',
    ];
?> --}}

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
    <form  method="post" action="{{ route('filter-report-combine') }}" enctype="multipart/form-data">
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
                                <section class="control-label">Tanggal Mulai
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="date" class="form-control input-bb" name="start_date" value="{{ $start_date }}">

                                {{-- <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" name="start_date" id="start_date"  style="width: 15rem;"/> --}}
                            </div>
                        </div>
    
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Tanggal Akhir
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                <input type="date" class="form-control input-bb" name="end_date" value="{{ $end_date }}">
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class="form-group form-md-line-input">
                                <section class="control-label">Pemasukan & Pengeluaran
                                    <span class="required text-danger">
                                        *
                                    </span>
                                </section>
                                {!! Form::select('financial_flow_code', $code, $financial_flow_code, ['class' => 'selection-search-clear select-form', 'id' => 'financial_flow_code','' ])!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="form-actions float-right">
                        <a href="{{ route('filter-reset-report-combine') }}" type="button" name="Reset" class="btn bg-yellow btn-sm"><i class="fas fa-sync"></i> Reset</a>
                        <button type="submit" name="Find" class="btn btn-primary btn-sm" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
            </div>
        </form>
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Lap Pemasukan & Pengeluaran
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
                        <th width="10%" style='text-align:center'>Kategori</th>
                        <th width="10%" style='text-align:center'>Tipe</th>
                        {{-- <th width="10%" style='text-align:center'>Penyelenggara</th> --}}
                        <th width="10%" style='text-align:center'>Kandidat</th>
                        <th width="10%" style='text-align:center'>Timses</th>
                        <th width="10%" style='text-align:center'>Nominal</th>
                        <th width="10%" style='text-align:center'>Tanggal</th>
                        {{-- <th width="10%" style='text-align:center'>Keterangan</th> --}}
                        {{-- <th width="3%" style='text-align:center'>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;

                        $type =[
                            ''  => '',
                            '1' => 'Pemasukan',
                            '2' => 'Pengeluaran',
                        ];

                        function rupiah($angka){
                            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                            return $hasil_rupiah;
                        } 
                    ?>
                    @foreach($fundingcombine as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$ReportFC->getCategoryName($val['financial_category_id'])}}</td>
                        <td>{{$type[$val['financial_category_type']]}}</td>
                        {{-- <td>{{$val['candidate_id']}}</td> --}}
                        @if($val['candidate_id'] == null)
                        <td style='text-align:center'>-</td>
                        @else
                        <td>{{$ReportFC->getCandidateName($val['candidate_id'])}}</td>
                        @endif
                        @if($val['timses_id'] == null)
                        <td style='text-align:center'>-</td>
                        @else
                        <td>{{$ReportFC->getTimsesName($val['timses_id'])}}</td>
                        @endif
                        <td>{{rupiah($val['financial_flow_nominal'])}}</td>
                        <td>{{$val['financial_flow_date']}}</td>
                        {{-- <td>{{$val['financial_flow_description']}}</td> --}}
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
                <a class="btn bg-red btn-sm" href="{{ url('/report-combine/print') }}"><i class="fa fa-file-pdf"></i> Pdf</a>
                <a class="btn bg-olive btn-sm" href="{{ url('/report-combine/export') }}"><i class="fa fa-download"></i> Export Data</a>
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