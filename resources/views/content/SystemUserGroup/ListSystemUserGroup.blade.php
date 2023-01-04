@inject('SystemUser', 'App\Http\Controllers\SystemUserController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar System User Group</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    <b>Daftar System User Group</b> <small>Mengelola System User Group </small>
</h3>
<br/>

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
    <div class="form-actions float-right">
        <button onclick="location.href='{{ url('system-user-group/add') }}'" name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah System User Group</button>
    </div>
</div>

<div class="card-body table-responsive">
    <div class="table-responsive">
        <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="2%" style='text-align:center'>User Group ID</th>
                        <th width="10%" style='text-align:center'>Nama</th>
                        <th width="10%" style='text-align:center'>User Group Level</th>
                        <th width="2%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($systemusergroup as $usergroup)
                    <tr>
                        <td style='text-align:center'>{{$usergroup['user_group_id']}}</td>
                        <td>{{$usergroup['user_group_name']}}</td>
                        <td>{{$usergroup['user_group_level']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge bg-warning " href="{{ url('/system-user-group/edit/'.$usergroup['user_group_id']) }}"><i class='fas fa-edit'></i> Edit</a>
                            <a type="button" class="badge bg-danger btn-sm" href="{{ url('/system-user-group/delete-system-user-group/'.$usergroup['user_group_id']) }}"><i class='far fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>
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