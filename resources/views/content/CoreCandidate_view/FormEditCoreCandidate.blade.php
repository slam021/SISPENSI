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
        <li class="breadcrumb-item"><a href="{{ url('candidate') }}">Daftar Data Kandidat</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data Kandidat</li>
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
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit Data Kandidat
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ url('candidate') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    {{-- <?php 
        if (empty($coresection)){
            $coresection['section_name'] = '';
        }
    ?> --}}
    
    <?php 
        $gender =[
            ''  => '',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];
    ?>

    <form method="post" action="{{route('process-edit-candidate')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama Lengkap<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_full_name" id="candidate_full_name" value="{{$corecandidate->candidate_full_name}}" autocomplete="off" />
                        <input class="form-control input-bb" type="hidden" name="candidate_id" id="candidate_id" value="{{$corecandidate->candidate_id}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Nama panggilan<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nick_name" id="candidate_nick_name" value="{{$corecandidate->candidate_nick_name}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">NIK<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_nik" id="candidate_nik" value="{{$corecandidate->candidate_nik}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Kelamin<a class='red'> *</a></a>
                        {!! Form::select('candidate_gender', $gender, $corecandidate->candidate_gender, ['class' => 'selection-search-clear select-form', 'id' => 'candidate_gender'])!!}
                        {{-- <input class="form-control input-bb" type="text" name="gender" id="gender" value="{{$corecandidate('gender')}}" autocomplete="off"/> --}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Alamat<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_address" id="candidate_address" value="{{$corecandidate->candidate_address}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">No.Telp<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_phone_number" id="candidate_phone_number" value="{{$corecandidate->candidate_phone_number}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tempat Lahir<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="candidate_birth_place" id="candidate_birth_place" value="{{$corecandidate->candidate_birth_place}}" autocomplete="off" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Tanggal Lahir<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="date" name="candidate_birth_date" id="candidate_birth_date" value="{{$corecandidate->candidate_birth_date}}" autocomplete="off" />
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Periode<a class='red'> *</a></a>
                        {!! Form::select('period_id', $coreperiod, $corecandidate->period_id, ['class' => 'selection-search-clear select-form', 'id' => 'period_id','' ])!!}
                    </div>
                </div> --}}
                {{-- <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Foto Sekarang<a class='red'> </a></a>
                        <input class="form-control input-bb" type="text" name="candidate_photos_old" id="candidate_photos_old" value="{{$corecandidate->candidate_photos}}" autocomplete="off" readonly/>
                    </div>
                </div> --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <a class="text-dark">Ganti Foto<a class='red'> </a></a>
                        <input class="form-control input-bb" type="file" name="candidate_photos" id="candidate_photos"  autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger btn-sm" onClick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="Save" class="btn btn-success btn-sm" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </div>
    </div>
</form>

@stop

@section('footer')
    
@stop

@section('css')
    
@stop