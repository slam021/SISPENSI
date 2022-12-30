@inject('Program', 'App\Http\Controllers\ProgramController')

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
        <li class="breadcrumb-item"><a href="{{ url('candidate') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Acara</li>
    </ol>
</nav>

@stop

@section('content')

{{-- <h3 class="page-title">
    Form Edit Bagian
</h3> --}}
@if(session('msg'))
<div class="alert alert-success" role="alert">
    {{session('msg')}}
</div>
@endif
<div class="card border border-dark" >
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Data Penyelenggara
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('program') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <?php 
        $gender =[
            ''  => '',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];

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
    @csrf
    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-6">
                <div class="form-group" >
                    <a class="text-dark">Penyelengara<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="program_organizer" id="program_organizer" value="{{$organizer[$program->program_organizer]}}" autocomplete="off" readonly/>
                </div>
            </div>
            @if ($program->timses_member_id == null)
            <div class="col-md-6">
                <div class="form-group" >
                    <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_name" id="timses_name" value="" autocomplete="off" readonly/>
                </div>
            </div>
            @else
            <div class="col-md-6">
                <div class="form-group" >
                    <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_name" id="timses_name" value="{{$Program->getTimsesName($program->timses_member_id)}}" autocomplete="off" readonly/>
                </div>
            </div>
            @endif
        </div>
        {{-- @if ($membertimses('timses_id') == null)
            <br>
        @else
        <div class="card-header border-dark bg-dark">
            <h5 class="mb-0 float-left">
                Anggota Timses
            </h5>
        </div>
        <div class="card-body table-responsive">
            <div class="table-responsive">
                <table id="example2" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                    <thead>
                        <tr>
                            <th width="3%" style='text-align:center'>No</th>
                            <th width="10%" style='text-align:center'>Nama</th>
                            <th width="10%" style='text-align:center'>NIK</th>
                            <th width="10%" style='text-align:center'>Alamat</th>
                            <th width="10%" style='text-align:center'>No. Telp</th>
                            <th width="10%" style='text-align:center'>Kelamin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 1; 
                        ?>
                        @foreach($membertimses as $key => $val)
                        <tr>
                            <td style='text-align:center'>{{$no}}</td>
                            <td>{{$val['timses_member_name']}}</td>
                            <td>{{$val['timses_member_nik']}}</td>
                            <td>{{$val['timses_member_address']}}</td>
                            <td>{{$val['timses_member_phone']}}</td>
                            <td>{{$gender[$val['timses_member_gender']]}}</td>
                        </tr>

                        <?php $no++; ?>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        @endif --}}
    </div>
</div>
    
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Data Acara
        </h5>
    </div>
    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Nama Acara<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{$program->program_name}}" autocomplete="off" readonly/>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Kandidate<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="candidate_full_name" id="candidate_full_name" value="{{$program->candidate_full_name}}" autocomplete="off" readonly/>

                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Deskripsi<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="program_description" id="program_description" value="{{$program->program_description}}" autocomplete="off" readonly/>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Tanggal<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="date" name="program_date" id="program_date" value="{{$program->program_date}}" autocomplete="off" readonly/>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Lokasi<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="location_name" id="location_name" value="{{$program->location_name}}" autocomplete="off" readonly/>
                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Lokasi Acara<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="program_address" id="program_address" value="{{$program->program_address}}" autocomplete="off" readonly/>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Dana<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="program_fund" id="program_fund" value="{{rupiah($program->program_fund)}}" autocomplete="off" readonly/>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <a class="text-dark">Periode<a class='red'> </a></a>
                    <input class="form-control input-bb" type="text" name="period_name" id="period_name" value="{{$program->period_name}}" autocomplete="off" readonly/>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<br>

@stop

@section('footer')
    
@stop

@section('css')
    
@stop