@extends('layout.master') 
@push('plugin-styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet" /> 
<link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/animate-css/animate.min.css') }}" rel="stylesheet" />
@endpush 
@section('content')
<div class="filter-wrap mb-3">
    <div class="row">
        <div class="form-filter-wrap">
            <form method="GET" action="{{ route('mylogs') }}">
                <h5>Pilih Bulan :</h5>
                <input type="month" id="bulan" name="bulan" value="{{ old('bulan') }}" class="form-control mt-1">
                <button type="submit" class="btn btn-primary w-100 mt-1">FILTER</button>
            </form>
        </div>
    </div>
</div>
<div class="hitungAbsen-wrap mb-3">
    <div class="card">
        <div class="card-header text-center">
            <h5>Tidak Absen Bulan Ini</h5>
        </div>
        <div class="card-body text-center">
            <h3>{{$daysWithNoLogs}} Hari</h3>
        </div>
    </div>
</div>
<div class="banner-carousel-wrap">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h5>Log Absensi</h5>
                </div>
                <div class="card-body">
                    @foreach ($logsfilter as $log)
                    <div class="clock-in-wrap d-flex justify-content-between">
                        <div class="con">
                            <h6 class="mb-1">{{ date('M', strtotime($log->tanggal)) }}</h6>
                            <h4 class="text-muted">{{ date('d', strtotime($log->tanggal)) }}</h4>
                        </div>
                        <div class="in align-self-center">
                            <p class="text-muted">Clock In</p>
                            <h6 class="mb-1 text-end text-success">{{ $log->clock_in}}</h6>
                        </div>
                        <div class="in align-self-center">
                            <p class="text-muted">Clock Out</p>
                            <h6 class="mb-1 text-end text-danger">{{ $log->clock_out ? $log->clock_out : '-- / -- / ---' }}</h6>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection