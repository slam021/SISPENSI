@extends('adminlte::page')

@section('title', 'Sistem Pendukung Eleksi')
<link rel="shortcut icon" href="{{ asset('resources/assets/logo_vote.ico') }}" />

{{-- @section('content_header')
    
Dashboard

@stop --}}

@section('content')
    
<br>
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Menu Utama
        </h5>
    </div>

    <div class="card-body">
        <div class="row">
            <div class='col-md-3'>
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 style="text-align: left; margin-left:10px"><b>{{ $corecandidate->count('core_candidate.candidate_id') }}</b></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie" style='font-size:60px; color:rgb(37, 35, 35); margin-right: 70px'></i>
                    </div>
                    <br>
                    <a class="small-box-footer" href="{{url('candidate')}}">
                        <b>Kandidate</b>
                        <i class='far fa-arrow-alt-circle-right'></i>
                    </a>
                </div>
            </div>
            <div class='col-md-3'>
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 style="text-align: left; margin-left:10px "><b>{{$coredapil->count('core_dapil.dapil_id') }}</b></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marked-alt	" style='font-size:60px; color:rgb(255, 255, 255); margin-right: 70px'></i>
                    </div>
                    <br>
                    <a class="small-box-footer" href="{{url('dapil')}}">
                        <b>Dapil</b>
                        <i class='far fa-arrow-alt-circle-right'></i>
                    </a>
                </div>
            </div>
            <div class='col-md-3'>
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 style="text-align: left; margin-left:10px"><b>{{$corepollingstation->count('core_polling_station.polling_station_id') }}</b></h3>
                    </div>
                    <div class="icon">
                        <i class="	fas fa-vote-yea" style='font-size:60px; color:rgb(255, 255, 255); margin-right: 70px'></i>
                    </div>
                    <br>
                    <a class="small-box-footer" href="{{url('polling-station')}}">
                        <b>TPS</b>
                        <i class='far fa-arrow-alt-circle-right'></i>
                    </a>
                </div>
            </div>
            <div class='col-md-3'>
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 style="text-align: left; margin-left:10px "><b>{{$coretimsesmember->count('core_timses_member.timses_member_id')}}</b></h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users" style='font-size:60px; color:rgb(255, 255, 255); margin-right: 70px'></i>
                    </div>
                    <br>
                    <a class="small-box-footer" href="{{url('timses')}}">
                        <b>Timses</b>
                        <i class='far fa-arrow-alt-circle-right'></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class='col-md-6'>
                <div class="card" style="height: 250px;">
                    <div class="card-header bg-secondary">
                    Other
                    </div>
                    <div class="card-body">
                    <ul class="list-group">
                    <?php foreach($menus as $menu){
                        if($menu['id_menu']==4){
                    ?>
                        <li class="list-group-item main-menu-item" onClick="location.href='{{url('program')}}'"> <i class="fa fa-angle-right"></i> List Acara</li>         
                    <?php   }
                        if($menu['id_menu']==53){
                    ?> 
                        <li class="list-group-item main-menu-item" onClick="location.href='{{url('period')}}'"> <i class="fa fa-angle-right"></i> List Periode</li>  
                    <?php 
                            }
                        if($menu['id_menu']==33){
                    ?> 
                        <li class="list-group-item main-menu-item" onClick="location.href='{{url('financial-category')}}'"> <i class="fa fa-angle-right"></i> List Kategori Keuangan</li>  
                    <?php 
                            }
                        } 
                    ?>                    
                    </ul>
                </div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class="card" style="height: 250px;">
                    <div class="card-header bg-secondary">
                    Laporan
                    </div>
                    <div class="card-body">
                    <ul class="list-group">
                    <?php foreach($menus as $menu){
                            if($menu['id_menu']==43){
                    ?>
                        <li class="list-group-item main-menu-item" onClick="location.href='{{url('report-combine')}}'"> <i class="fa fa-angle-right"></i> Laporan Pemasukan & Pengeluaran</li>
                    <?php   }
                            if($menu['id_menu']==46){
                    ?> 
                         <li class="list-group-item main-menu-item" onClick="location.href='{{url('report-funding')}}'"> <i class="fa fa-angle-right"></i> Laporan Perhitungan Keuangan</li>      
                    <?php   }
                            if($menu['id_menu']==44){
                    ?> 
                            <li class="list-group-item main-menu-item" onClick="location.href='{{url('report-recap')}}'"> <i class="fa fa-angle-right"></i> Laporan Rekapitulasi</li>  
                    <?php   }
                            if($menu['id_menu']==45){
                    
                            }
                        } 
                      ?>                                
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
<br>
<br>
<br>

@stop

@section('css')
    
@stop

@section('js')
    
@stop