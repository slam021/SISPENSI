@inject('CoreCandidate', 'App\Http\Controllers\CoreCandidateController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Data Kandidat</li>
    </ol>
</nav>
@stop

@section('content')
<h3 class="page-title">
    <b>Daftar Kandidat</b>
</h3>
@if(session('msg'))
<div class="alert alert-success" role="alert">
     <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msg')}}
</div>
@endif  
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Mengelola Data Kandidat 
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ url('candidate/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Data Kandidat Baru</button>
        </div>
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="3%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama Lengkap</th>
                        {{-- <th width="10%" style='text-align:center'>Nama Panggilan</th> --}}
                        <th width="10%" style='text-align:center'>NIK</th>
                        <th width="10%" style='text-align:center'>Kelamin</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="10%" style='text-align:center'>Tempat Lahir</th>
                        <th width="10%" style='text-align:center'>Tanggal Lahir</th>
                        <th width="10%" style='text-align:center'>No. Telp</th>
                        <th width="5%" style='text-align:center'>Periode</th>
                        {{-- <th width="10%" style='text-align:center'>Foto</th> --}}
                        <th width="5%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                        $candidategender =array(
                            1 => 'Laki-laki',
                            2 => 'Perempuan',
                        );
                    ?>
                    @foreach($corecandidate as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['candidate_full_name']}}</td>
                        {{-- <td>{{$val['candidate_nick_name']}}</td> --}}
                        <td>{{$val['candidate_nik']}}</td>
                        <td>{{$candidategender[$val['candidate_gender']]}}</td>
                        <td>{{$val['candidate_address']}}</td>
                        <td>{{$val['candidate_birth_place']}}</td>
                        <td>{{$val['candidate_birth_date']}}</td>
                        <td>{{$val['candidate_phone_number']}}</td>
                        <td>{{$val['period_name']}}</td>
                        {{-- <td><img width="150px" src="{{ url('/candidate-photos/'.$val['photos']) }}"></td> --}}
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning" href="{{ url('/candidate/edit/'.$val['candidate_id'])}}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge bg-lime" href="{{ url('/candidate/detail/'.$val['candidate_id'])}}"><i class='fas fa-list-ul'></i> Detail</a>
                            <a type="button" class="badge badge-danger" href="{{ url('/candidate/delete-candidate/'.$val['candidate_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
                   

                    <?php $no++; ?>
                    @endforeach
                     
                </tbody>
            </table>
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