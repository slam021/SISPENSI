@inject('DFP', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $(document).ready(function(){
        var timses_member_id = {!! json_encode($nullmembertimses) !!};
        
        if(timses_member_id == null){
            $("#timses_member_id").select2("val", "0");
        }
    });

    function getUserAkun(timses_member_id){
        // alert(timses_member_id);
    $.ajax({
				type: "GET",
				url : "{{url('/program/get-user-akun')}}" + '/' + timses_member_id,
				success: function(msg){
                    console.log(msg);
                        $("#user_id").val(msg); 
                },

		});
		
	}

</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('program') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penyaluran Dana Acara</li>
    </ol>
</nav>

@stop

@section('content')
<?php 
//format rupiah PHP
function rupiah($angka){
	
	$hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
?>

{{-- <h3 class="page-title">
    Form Edit Bagian
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
<button type="submit" class="btn btn-success btn-sm" style="margin-bottom: 10px" data-toggle='modal' data-target='#exampleModal'><i class='far fa-paper-plane'></i> Penyaluran Dana</button>
        
<div class="card border border-dark" >
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Penyaluran Dana Acara
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('program') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <?php 
        $program_id = Request::segment(3);
    ?>
<form method="post" action="{{route('process-distribution-fund')}}" enctype="multipart/form-data">        
    @csrf
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Penayluran Dana ke Anggota Timses</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Timses<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text"name="timses_name" id="timses_name" value="{{$program->timses_name}}" readonly />
                                    <input class="form-control input-bb" type="hidden" name="program_id" id="program_id" value="{{$program_id}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                    <input class="form-control input-bb" type="hidden" name="timses_id" id="timses_id" value="{{$program->timses_id}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Anggota Timses<a class='red'> *</a></a>
                                    {!! Form::select('timses_member_id', $membertimses, $nullmembertimses, ['class' => 'selection-search-clear select-form', 'id' => 'timses_member_id','onChange' => 'getUserAkun(this.value)' ])!!} 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Akun<a class='red'> *</a></a>
                                    {{-- {!! Form::select('user_id', $systemuser, $nullsystemuser, ['class' => 'selection-search-clear select-form', 'id' => 'user_id','' ])!!}  --}}
                                    <input class="form-control input-bb" type="text" name="user_id" id="user_id" autocomplete="off" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nominal<a class='red'> *</a></a>
                                    <input class="form-control input-bb" type="text" name="distribution_fund_nominal" id="distribution_fund_nominal" value="{{old('distribution_fund_nominal')}}" onChange="function_elements_add(this.name, this.value);" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm" ><i class="fas fa-check" ></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="card-body table-responsive">
        <div class="table-responsive">
            <table id="example" class="table table-sm table-striped table-bordered table-hover " style="width:auto">
                <thead>
                    <tr>
                        <th width="3%" style='text-align:center'>No</th>
                        <th width="10%" style='text-align:center'>Nama Timses</th>
                        <th width="10%" style='text-align:center'>Anggota Timses</th>
                        <th width="10%" style='text-align:center'>Akun</th>
                        <th width="10%" style='text-align:center'>Nominal</th>
                        <th width="5%" style='text-align:center'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1; 
                    ?>
                    @foreach($programdistributionfund as $key => $val)
                    <tr>
                        <td style='text-align:center'>{{$no}}</td>
                        <td>{{$val['timses_name']}}</td>
                        <td>{{$val['timses_member_name']}}</td>
                        <td>{{$DFP->getAkunName($val['user_id'])}}</td>
                        <td>{{rupiah($val['distribution_fund_nominal'])}}</td>
                        <td class="" style='text-align:center'>
                            <a type="button" class="badge badge-warning" href="{{ url('/program/edit-distribution-fund/'.$val['program_id'].'/'.$val['timses_id'].'/'.$val['distribution_fund_id']) }}" title="edit"><i class='far fa-edit'></i> Edit</a>
                            <a type="button" class="badge bg-lime" href="{{ url('/program/distribution-fund/'.$val['distribution_fund_id']) }}" title="Detail"><i class='fas fa-list-ul'></i> Detail</a>
                        </td>
                    </tr>

                    <?php $no++; ?>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<br>

@stop

@section('footer')
    
@stop

@section('css')
    
@stop