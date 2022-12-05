@inject('ProgramSupport', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="icon" href="{{ asset('resources/assets/logo_vote.ico')}}" />

@section('js')
<script>
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})
</script>

@stop

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('program') }}">Daftar Pendukung Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Pendukung Acara</li>
    </ol>
</nav>
@stop

@section('content')
{{-- <h3 class="page-title">
    <b>Daftar Pendukung Acara</b>
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
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah Pendukung Acara
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

        $program_id = Request::segment(3);
        
    ?>

    <form method="post" action="{{route('process-add-program-support')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                    <a class="text-dark">Pendukung<a class='red'> *</a></a>
                    {!! Form::select('supporter_id', $coresupporter, '', ['placeholder'=>'Masukkan NIK','class' => 'selection-search-clear form-control', 'id' => 'supporter_id','' ])!!}
                    <input class="form-control input-bb" type="hidden" name="program_id" id="program_id" value="{{$program_id}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <br>
                        <button type="submit" name="form1" class="btn btn-success btn-sm" title="Select"><i class="fa fa-plus"></i> Tambah</button></form>
                        <button style="margin-left: 10px" type="button" name="form2" class="btn bg-teal btn-sm" title="Add" data-toggle='modal' data-target='#exampleModal'><i class="fas fa-user-plus"></i> Tambah Data Pendukung Baru</button>
                    </div>
                </div>
            </div>
        </div>
    <form method="post" action="{{route('process-add-supporter-new')}}" enctype="multipart/form-data">
        @csrf    
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Pendukung Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Lengkap<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text" name="supporter_full_name" id="supporter_full_name" value="{{old('supporter_full_name')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                    <input class="form-control input-bb" type="hidden" name="program_id" id="program_id" value="{{$program_id}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">NIK<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text" name="supporter_nik" id="supporter_nik" value="{{old('supporter_nik')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Kelamin<a class='red'> *</a></a>
                                    {!! Form::select('supporter_gender', $gender, '', ['class' => 'selection-search-clear select-form', 'id' => 'supporter_gender','' ])!!}
                                    {{-- <input class="form-control input-bb" type="text" name="gender" id="gender" value="{{old('gender')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off"/> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Alamat<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text" name="supporter_address" id="supporter_address" value="{{old('supporter_address')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Tempat Lahir<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text" name="supporter_birth_place" id="supporter_birth_place" value="{{old('supporter_birth_place')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Tanggal Lahir<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="date" name="supporter_birth_date" id="supporter_birth_date" value="{{old('supporter_birth_date')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" ><i class="fas fa-check" ></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form> 
</div>
<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
        <h5 class="mb-0 float-left">
            Daftar Pendukung Acara 
        </h5>
        {{-- <div class="form-actions float-right">
            <button onclick="location.href='{{ url('program/add') }}'" name="add" class="btn btn-sm bg-cyan" title="Add Data"><i class="fa fa-plus"></i> Tambah Pendukung Acara Baru</button>
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
                        <th width="10%" style='text-align:center'>Kelamin</th>
                        <th width="10%" style='text-align:center'>Alamat</th>
                        <th width="5%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($programsupport as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['supporter_full_name']}}</td>
                        <td>{{$val['supporter_nik']}}</td>
                        <td>{{$supportergender[$val['supporter_gender']]}}</td>
                        <td>{{$val['supporter_address']}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-danger" href="{{ url('/program/delete-program-support/'.$program_id.'/'.$val['program_support_id']) }}" title="Hapus"><i class='far fa-trash-alt'></i> Hapus</a>
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