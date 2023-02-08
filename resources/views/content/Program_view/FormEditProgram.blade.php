@inject('Program', 'App\Http\Controllers\ProgramController')

@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

@section('js')
<script>
    function disabledTimses(value){
        var organizer = document.getElementById('program_organizer').value;
        console.log(organizer);
        if(organizer == 1){
            document.getElementById('timses_id').disabled = true;
            $("#timses_id").select2("val", "0"); 
        }
        if(organizer == 2){
            document.getElementById('timses_id').disabled = false;
        }
    }

    function toRp(angka){
        var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
        var rev2    = '';
        for(var i = 0; i < rev.length; i++){
            rev2  += rev[i];
            if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                rev2 += '.';
            }
        }
        return rev2.split('').reverse().join('') + ',00';
    }
    
    function rupiahSave(value){
        var rupiah_save = document.getElementById("program_fund");
        var rupiah_view = document.getElementById("program_fund_view");
        if(rupiah_view){
            document.getElementById('program_fund').value = rupiah_view.value;
            document.getElementById('program_fund_view').value =  toRp(rupiah_view.value);
        }
    }
</script>
@stop
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('program') }}">Daftar Acara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Acara</li>
    </ol>
</nav>

@stop

@section('content')

<h3 class="page-title">
    Form Edit Acara
</h3>
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
@if(session('msgerror'))
<div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    {{session('msgerror')}}
</div>
@endif
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Data Penyelenggara
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('program') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>
    
    <?php 
        $organizer =[
            ''  => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];
    ?>

    <form method="post" action="{{route('process-edit-program')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Penyelenggara<a class='red'> *</a></a>
                        {!! Form::select('program_organizer', $organizer, $program->program_organizer, ['class' => 'selection-search-clear select-form', 'id' => 'program_organizer', 'onChange' => 'disabledTimses(this.value);', '' ])!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Timses<a class='red'> *</a></a>
                        @if($program->program_organizer == 1)
                        <select class="selection-search-clear" name="timses_id" id="timses_id">
                                <option value="{{$program->timses_id}}"></option>
                                @foreach($coretimsesmember2 as $key => $val)
                                <option value="{{$val->timses_id}}">{{$val->timses_name}}</option>
                                @endforeach   
                            </select> 
                        {{-- {!! Form::select('timses_id', $coretimses, '', ['class' => 'selection-search-clear select-form', 'id' => 'timses_id' ])!!} --}}
                        @else
                            {!! Form::select('timses_id', $coretimses, $program->timses_id, ['class' => 'selection-search-clear select-form', 'id' => 'timses_id','' ])!!}
                        @endif
                        <input class="form-control input-bb" type="hidden" name="program_id" id="program_id" value="{{$program->program_id}}" autocomplete="off"/>
                        {{-- <input class="form-control input-bb" type="text" name="candidate_id" id="candidate_id" value="{{$corecandidate}}" autocomplete="off"/> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card border border-dark">
        <div class="card-header border-dark bg-dark">
            <h5 class="mb-0 float-left">
                Form Data Acara
            </h5>
        </div>
            @csrf
            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Nama Acara<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_name" id="program_name" value="{{$program->program_name}}" autocomplete="off"/>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Kandidate<a class='red'> *</a></a>
                            {!! Form::select('candidate_id', $corecandidate, $program->candidate_id, ['class' => 'selection-search-clear select-form', 'id' => 'candidate_id','' ])!!}
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Deskripsi<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_description" id="program_description" value="{{$program->program_description}}" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Tanggal<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="date" name="program_date" id="program_date" value="{{$program->program_date}}" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Dana<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_fund_view" id="program_fund_view" value="{{$program->program_fund}}" autocomplete="off" onChange="rupiahSave();"/>
                            <input class="form-control input-bb" type="hidden" name="program_fund" id="program_fund" value="{{$program->program_fund}}" autocomplete="off" />
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Lokasi<a class='red'> *</a></a>
                            {!! Form::select('location_id', $corelocation, $program->location_id, ['class' => 'selection-search-clear select-form', 'id' => 'location_id','' ])!!}
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Lokasi Acara<a class='red'> *</a></a>
                            <input class="form-control input-bb" type="text" name="program_address" id="program_address" value="{{$program->program_address}}" autocomplete="off" />
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <a class="text-dark">Periode<a class='red'> </a></a>
                            {!! Form::select('period_id', $coreperiod, $program->period_id, ['class' => 'selection-search-clear select-form', 'id' => 'period_id','' ])!!}
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <button type="reset" name="Reset" class="btn btn-danger btn-sm" value="reset"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <br>
    <br>
@stop

@section('footer')
    
@stop

@section('css')
    
@stop