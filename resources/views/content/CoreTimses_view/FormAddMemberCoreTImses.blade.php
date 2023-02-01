@inject('CT', 'App\Http\Controllers\CoreTimsesController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('js')
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $(document).ready(function(){
        var user_group_id = {!! json_encode($nullsystemusergoup) !!};
        
        if(user_group_id == null){
            $("#user_group_id").select2("val", "0");
        }
    });

</script>
    
@stop
@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('timses') }}">Daftar Data Timses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Data Timses</li>
    </ol>
</nav>
@stop

@section('content')

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
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah Data Timses 
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('timses') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

@php
    $gender =[
        ''  => '',
        '1' => 'Laki-laki',
        '2' => 'Perempuan',
    ];

    $timses_id = Request::segment(3);    
    
@endphp

    <form method="post" action="{{route('process-add-timses-member')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Nama<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="hidden" name="timses_id" id="timses_id" value="{{$timses_id}}" autocomplete="off" />
                    <input class="form-control input-bb" type="text" name="timses_member_name" id="timses_member_name" value="{{old('timses_member_name')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">NIK<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_nik" id="timses_member_nik" value="{{old('timses_member_nik')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Alamat<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_address" id="timses_member_address" value="{{old('timses_member_address')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">No. Telp<a class='red'> *</a></a>
                    <input class="form-control input-bb" type="text" name="timses_member_phone" id="timses_member_phone" value="{{old('timses_member_phone')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Kelamin<a class='red'> *</a></a>
                    {!! Form::select('timses_member_gender', $gender, '', ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_gender','' ])!!}
                    </div>
                </div>
            </div>
            <div class="form-actions float-right" style="margin-bottom: -15px; margin-top: -20px" >
                <button type="reset" name="Reset" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </form> 
</div>

<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar Timses
        </h5>
        {{-- <div class="form-actions float-right">
            <button onclick="location.href='{{ url('timses/add-member') }}'" name="add" class="btn btn-sm bg-info" title="Add Data"><i class="fas fa-plus"></i> Tambah Timses</button>
        </div> --}}
    </div>

    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="3%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama</th>
                        <th width="10%" style='text-align:center'>NIK</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="10%" style='text-align:center'>No. Telp</th>
                        <th width="10%" style='text-align:center'>Kelamin</th>
                        <th width="10%" style='text-align:center'>Akun</th>
                        <th width="5%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($coretimsesmember as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_member_name']}}</td>
                        <td>{{$val['timses_member_nik']}}</td>
                        <td>{{$val['timses_member_address']}}</td>
                        <td>{{$val['timses_member_phone']}}</td>
                        <td>{{$gender[$val['timses_member_gender']]}}</td>
                        <td>{{$CT->getAkunName($val['user_id'])}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge bg-warning" href="{{url('/timses/edit-member/'.$timses_id.'/'.$val['timses_member_id']) }}" title='Edit Acara'><i class='fas fa-edit'></i> Edit</a> 
                            <a type="button" class="badge bg-indigo" href="{{url('/timses/add-ktp-member/'.$timses_id.'/'.$val['timses_member_id']) }}" title='Tambah KTP'><i class='fas fa-camera'></i> Tambah KTP</a> 
                            <?php
                                if($val['user_id'] == null){
                                    echo "<a type='button' class='badge bg-success' href='".url('/timses/add-account-member/'.$timses_id.'/'.$val['timses_member_id'])."' title='Buat Akun'><i class='fas fa-user-circle'></i> Buat Akun</a>";
                                }else{
                                    // echo "<button disabled type='button' class='badge bg-info' title='Edit Acara'><i class='fas fa-edit'></i> Buat akun</button>";
                                }
                            ?>
                            <a type="button" class="badge badge-danger" href="{{url('/timses/delete-timses-member/'.$val['timses_member_id']) }}" title="Hapus"><i class='far fa-trash-alt'></i> Hapus</a>
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