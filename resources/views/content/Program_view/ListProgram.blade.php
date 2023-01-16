@inject('Program', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('js')
<script>
      $(document).ready(function(){
        var timses_member_id = {!! json_encode($timses_member_id) !!};
        
        if(timses_member_id == null){
            $("#timses_member_id").select2("val", "0");
        }
    });
</script>
@stop

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Acara</li>
    </ol>
</nav>
@stop

@section('content')
{{-- <h3 class="page-title">
    <b>Daftar Acara</b>
</h3> --}}
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
<form  method="post" action="{{ route('filter-program') }}" enctype="multipart/form-data">
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
                            <section class="control-label">Nama Timses
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            {!! Form::select('timses_member_id', $coretimsesmember, $timses_member_id, ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_id','' ])!!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <a href="{{ route('filter-reset-program') }}" type="button" name="Reset" class="btn bg-yellow btn-sm"><i class="fas fa-sync"></i> Reset</a>
                    <button type="submit" name="Find" class="btn btn-primary btn-sm" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
        </div>
    </form>
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar Acara 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('program/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Acara</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="5%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Tanggal</th>
                        <th width="10%" style='text-align:center'>Penyelenggara</th>
                        {{-- <th width="10%" style='text-align:center'>Kandidate</th> --}}
                        <th width="10%" style='text-align:center'>Nama Timses</th>
                        <th width="10%" style='text-align:center'>Nama Acara</th>
                        <th width="10%" style='text-align:center'>Deskripsi</th>
                        {{-- <th width="10%" style='text-align:center'>Lokasi</th> --}}
                        <th width="10%" style='text-align:center'>Lokasi Acara</th>
                        <th width="10%" style='text-align:center'>Dana</th>
                        {{-- <th width="10%" style='text-align:center'>Periode</th> --}}
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
                        <td>{{date('d/m/Y', strtotime($val['program_date']))}}</td>
                        <td>{{$organizer[$val['program_organizer']]}}</td>
                        {{-- <td>{{$val['candidate_full_name']}}</td> --}}
                        @if ($val['program_organizer'] == 1)
                            <td style='text-align:center'>{{'-'}}</td>
                        @else
                            <td>{{$Program->getTimsesName($val['timses_member_id'])}}</td>
                        @endif
                        <td>{{$val['program_name']}}</td>
                        <td>{{$val['program_description']}}</td>
                        {{-- <td>{{$val['location_name']}}</td> --}}
                        <td>{{$val['program_address']}}</td>
                        <td style='text-align:right'>{{rupiah($val['program_fund'])}}</td>
                        {{-- <td>{{$val['period_name']}}</td> --}}
                        {{-- <td><img width="150px" src="{{ url('/program-photos/'.$val['photos']) }}"></td> --}}
                        <td class="" style='text-align:left'>
                            <?php
                                if($val['program_status'] == 0){
                                    echo "<a type='button' class='badge bg-warning' href='".url('/program/edit/'.$val['program_id'])."' title='Edit Acara'><i class='fas fa-edit'></i> Edit</a>";
                                }else{
                                    
                                }
                            ?>
                            <a type="button" class="badge bg-lime" href="{{ url('/program/detail/'.$val['program_id'])}}" title="Detail Acara"><i class='fas fa-list-ul'></i> Detail</a>
                            <a type="button" class="badge bg-indigo" href="{{ url('/program/documentation-program/'.$val['program_id'])}}" title="Dokumentasi Acara"><i class='far fa-image'></i> Dokumentasi</a>
                            <a type="button" class="badge bg-danger" href="{{ url('/program/delete-program/'.$val['program_id']) }}" title="Hapus Acara"><i class='far fa-trash-alt'></i> Hapus</a>
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