@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/animate-css/animate.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
    $feedback = \App\Feedback::where('name', Auth::user()->name)->first();
    $dataLogin = json_decode(Auth::user()->permission);
@endphp
<div class="absen-wrap mb-2">
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- Employee Login Details -->
            <div class="card custom-card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                            <div class="content-left align-self-center">
                                <div class="employee-name mb-1">
                                    <h5 class="text-white text-uppercase">{{ $employee->nama }}</h5>
                                </div>
                                <div class="employee-title-job">
                                    <p>{{ $employee->jabatan }}</p>
                                </div>
                            </div>
                            <div class="content-right">
                                <div class="gambar">
                                    <img src="{{ asset('images/' . $employee->gambar) }}" alt="" class="w-100">
                                </div>
                            </div>
                        </div>
                        <div class="content-wrap-employee-card d-flex justify-content-between">
                            <div class="content-left align-self-center">
                                <div class="employee-title-job">
                                    <p>Employee ID</p>
                                </div>
                                <div class="employee-name mb-1">
                                    <h5 class="text-white text-uppercase">{{ $employee->nik }}</h5>
                                </div>
                            </div>
                            <div class="content-right">
                                <div class="employee-title-job text-right">
                                    <p>Division</p>
                                </div>
                                <div class="employee-name mb-1">
                                    <h5 class="text-white text-uppercase">{{ $employee->organisasi }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End  -->
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="button-absen">
                        @foreach ($datakaryawan as $data)
                            @if (Auth::check())
                                @php
                                    $user = Auth::user();
                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    $hasScheduleForToday = \App\ModelCG\Schedule::where('employee', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->exists();
                                    $clockin = \App\Absen::where('nik', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->first();
                                @endphp
                                @if ($clockin)
                                <h5 class="text-center mb-3">Let's Get To Home !</h5>
                                @else
                                <h5 class="text-center mb-3">Let's Get To Work !</h5>
                                @endif
                            @endif
                        @endforeach
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                                $today = \Carbon\Carbon::now()->format('Y-m-d');
                                $hasScheduleForToday = \App\ModelCG\Schedule::where('employee', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->exists();
                                $clockin = \App\Absen::where('nik', $user->employee_code)
                                    ->whereDate('tanggal', $today)
                                    ->first();
                            @endphp
                            @if($hasScheduleForToday)
                                @if ($clockin)
                                <form action="{{ route('clockout') }}" method="POST" id="form-absen2">
                                @csrf
                                    <input type="hidden" name="latitude_out" id="latitude_out">
                                    <input type="hidden" name="longitude_out" id="longitude_out">
                                    <input type="hidden" name="status" value="H">
                                    <button type="submit" class="btn btn-lg btn-danger btn-icon-text mb-2 mb-md-0 w-100" id="btnout">Clock Out</button>
                                </form>
                                @else
                                <form action="{{ route('clockin') }}" method="POST" class="me-1" id="form-absen">
                                    @csrf
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                        <input type="hidden" name="status" value="H">
                                        <button type="submit" class="btn btn-lg btn-primary btn-icon-text mb-2 mb-md-0 w-100 button-biru" id="btn-absen" onClick="requestLocation()">
                                            Clock IN
                                        </button>
                                </form>
                                @endif
                            @else
                            <h6 class="text-center text-danger">Enjoy Off The Rest Of The Day !</h6>
                        @endif
                    @endif
                        <div class="log-absen-today mt-2">
                            <div class="card custom-card2">
                                <div class="card-header text-center bg-custom-biru" style="border-radius:12px 12px 0 0">
                                    <h5 class="text-white">Attendance Log</h5>   
                                </div>
                                <div class="card-body">
                                    @if (count($logs) > 0)
                                        @foreach ($logs as $log)
                                        <div class="clock-in-wrap d-flex justify-content-between">
                                            <div class="con">
                                                <h5 class="text-bold mb-1">{{ $log->clock_in }}</h5>
                                                <h6 class="text-muted">{{ date('d M', strtotime($log->tanggal)) }}</h6>
                                            </div>
                                            <div class="ket align-self-center">
                                                <h5 class="mb-1 text-end text-success">CLOCK IN</h5>
                                            </div>
                                        </div>
                                        <hr>
                                        @if (isset($log->clock_out) && !empty($log->clock_out))
                                        <div class="clock-in-wrap d-flex justify-content-between">
                                            <div class="con">
                                                <h5 class="text-bold mb-1">{{ $log->clock_out }}</h5>
                                                <h6 class="text-muted">{{ date('d M', strtotime($log->tanggal)) }}</h6>
                                            </div>
                                            <div class="ket align-self-center">
                                                <h5 class="mb-1 text-end text-danger">CLOCK OUT</h5>
                                            </div>
                                        </div>
                                        @else
                                        <div class="w-100">
                                            <p class="text-center">Anda Belum Absen Pulang</p>  
                                        </div>
                                        @endif
                                        @endforeach
                                    @else
                                        <div class="w-100">
                                            <p class="text-center">Anda Belum Absen Masuk</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu -->
<div class="row mb-3">
    <h5 class="mb-3">My Menu</h5>
    <div class="menu-absen-wrap">
        <div class="owl-carousel owl-theme owl-basic">
            <div class="item">
                <a href="{{route('mylogs')}}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="git-branch"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Attendence Log</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('attendence-request.create')}}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="user-plus"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Request</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('mySlip')}} ">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="file-text"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">My Payslip</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('attendence.backup')}}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="clock"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Backup</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('list-class') }}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Class Room</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('patroli') }}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Patroli</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Menu -->

<!-- Announcement -->
<div class="row mb-3">
    <h5 class="mb-2">Announcement</h5>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <h5 class="text-center mb-2">No announcement</h5>
                <p class="text-center text-muted">Your Announcement Will Show Here</p>
            </div>
        </div>
    </div>
</div>
<!-- End Announcement -->

@if(in_array('hr_access', $dataLogin))
<!-- Request -->
<div class="row mb-3">
    <h5 class="mb-2">Need My Approval</h5>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="item-request">
                    @foreach($dataRequest as $dataAbsen)
                    <div class="wrapper-pengajuan d-flex mb-4">
                        <div class="foto-profile me-2">
                            <img src="{{ asset('images/' . $dataAbsen->gambar) }}" alt="" class="w-100">
                        </div>
                        <div class="nama-karyawan align-self-center">
                            <a href="#" class="color-custom" data-bs-toggle="modal" data-bs-target=".Request{{$dataAbsen->unik_code}}">
                                <h6>{{$dataAbsen->nama}}</h6>
                            </a>
                            <span class="text-muted">{{$dataAbsen->status}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($dataRequest as $dataAbsen)
<div class="modal fade bd-example-modal-lg Request{{$dataAbsen->unik_code}}" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Attendence Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="input-custom mb-2">
                    <p>Full Name</p>
                    <h5>{{ $dataAbsen->nama }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Type</p>
                    <h5>{{ $dataAbsen->status }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Reason</p>
                    <h5>{{ $dataAbsen->alasan }}</h5>
                </div>
                <div class="input-custom mb-4">
                    <p>Date</p>
                    <h5>{{ $dataAbsen->tanggal }}</h5>
                </div>
                <a href="{{route('dokumen.download', ['id' => $dataAbsen->unik_code])}}" class="btn btn-primary mb-2 w-100 bg-custom-biru" style="border : none;">Download Attachment</a>
                @if ($dataAbsen->aprrove_status !=="Approved")
                <a class="btn btn-sm btn-success w-100 mb-2" href="{{ route('approve.request', $dataAbsen->id)}}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $dataAbsen->unik_code }}').submit();">
                    <span class="">Approve</span>
                </a>
                @endif

                @if ($dataAbsen->aprrove_status !=="Reject")
                <a class="btn btn-sm btn-danger w-100" href="{{ route('reject.request', ['id' => $dataAbsen->unik_code])}}" onclick="event.preventDefault(); document.getElementById('reject-usulan-form-{{ $dataAbsen->unik_code }}').submit();">
                    <span class="">Reject</span>
                </a>
                @endif
                <!-- Form Approved -->
                <form id="setujui-usulan-form-{{ $dataAbsen->unik_code }}" action="{{ route('approve.request', ['id' => $dataAbsen->unik_code]) }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <!-- Form Reject -->
                <form id="reject-usulan-form-{{ $dataAbsen->unik_code }}" action="{{ route('reject.request', ['id' => $dataAbsen->unik_code]) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
<!-- End -->

<!-- Task -->
<div class="row mb-6">
    <h5 class="mb-2">Task</h5>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <h5 class="text-center mb-2">No Task</h5>
                <p class="text-center text-muted">Your Task Will Show Here</p>
            </div>
        </div>
    </div>
</div>
<!-- End Task -->

<!-- Feedback Form -->
<div class="feedback-button-wrap">
    <a href="#" data-bs-toggle="modal" data-bs-target=".Feedback">
        <div class="icon-feedback d-flex">
            <i class="icon-lg text-white" data-feather="heart"></i>
            <p class="text-white">Feedback</p>
        </div>
    </a>
</div>

<div class="modal fade bd-example-modal-lg Feedback" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Feedback & Suggestions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                @if(!$feedback)
                <form action="{{route('feedback.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $employee->nik }}">
                    <input type="hidden" name="email" value="{{ $employee->email }}">
                    <div class="rating">
                    <h6 class="mb-3">How was your experience using TRUEST ?</h6>
                    <ul class="feedback mb-3">
                        <li class="angry">
                            <div>
                                <label for="rating1">
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth"></use>
                                    </svg>
                                </label>
                            </div>
                            <input type="radio" name="rating" value="1" class="rating" id="rating1">
                        </li>
                        <li class="sad">
                            <div>
                                <label for="rating2">
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth"></use>
                                    </svg>
                                </label>
                            </div>
                            <input type="radio" name="rating" value="2" class="rating" id="rating2">
                        </li>
                        <li class="ok">
                            <div>
                                <label for="rating3"> 
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                </label> 
                            </div>
                            <input type="radio" name="rating" value="3" class="rating" id="rating3">
                        </li>
                        <li class="good active">
                            <div>
                                <label for="rating4">
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth"></use>
                                    </svg>
                                </label>
                            </div>
                            <input type="radio" name="rating" value="4" class="rating" id="rating4">
                        </li>
                        <li class="happy">
                            <div>
                                <label for="rating5">
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                </label>
                            </div>
                            <input type="radio" name="rating" value="5" class="rating" id="rating5">
                        </li>
                    </ul>
                            
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 4" id="eye">
                            <path d="M1,1 C1.83333333,2.16666667 2.66666667,2.75 3.5,2.75 C4.33333333,2.75 5.16666667,2.16666667 6,1"></path>
                        </symbol>
                        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 7" id="mouth">
                            <path d="M1,5.5 C3.66666667,2.5 6.33333333,1 9,1 C11.6666667,1 14.3333333,2.5 17,5.5"></path>
                        </symbol>
                    </svg>

                    <h6 class="mb-2">Every feedback helps us a lot. What can we improve on ?</h6>
                    <input type="text" class="form-control mb-3" name="feedback" placeholder="Share your feedback.." required>

                    <button type="submit" class="btn btn-primary w-100 bg-custom-biru" style="border-radius:10px; border-color: #424874;">Share Feedback</button>
                </form>
                @else
                <div class="feedback-berhasil">
                    <ul class="feedback mb-3">
                        <li class="good active">
                            <div>
                                <label for="rating4">
                                    <svg class="eye left">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye"></use>
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth"></use>
                                    </svg>
                                </label>
                            </div>
                        </li>
                    </ul>
                    <h5 class="text-center mb-1">Thanks For Feedback</h5>
                    <p class="text-muted text-center">Every feedback helps us a lot.</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 4" id="eye">
                        <path d="M1,1 C1.83333333,2.16666667 2.66666667,2.75 3.5,2.75 C4.33333333,2.75 5.16666667,2.16666667 6,1"></path>
                    </symbol>
                    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 7" id="mouth">
                        <path d="M1,5.5 C3.66666667,2.5 6.33333333,1 9,1 C11.6666667,1 14.3333333,2.5 17,5.5"></path>
                    </symbol>
                </svg>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- End Feedback -->
<div class="row">
    <div class="download-apk">
        
    </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/carousel.js') }}"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif
</script>

<script>
$(function() {
    'use strict';

    if ($('.owl-basic').length) {
        $('.owl-basic').owlCarousel({
            loop: true,
            margin: 25,
            nav: false,
            responsive: {
                0: {
                    items: 3.5
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 6.5
                }
            }
        });
    }
});
</script>

<!-- Absen -->
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btn-absen').on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {

                    function successCallback(position) {
                        console.log('Latitude:', position.coords.latitude);
                        console.log('Longitude:', position.coords.longitude);
                    }

                    function errorCallback(error) {
                        console.error('Error getting location:', error.message);
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        // Jika tombol di klik, maka akan meminta lokasi
                        document.getElementById('get-location-btn').addEventListener('click', function () {
                            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                        });
                    });
                    
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude').val(position.coords.latitude);
                    $('#longitude').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-absen').submit();
                }, function(error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        // Pengguna menolak izin lokasi
                        alert('Anda perlu memberikan izin lokasi untuk menggunakan fitur ini');
                    }
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btnout').on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude_out').val(position.coords.latitude);
                    $('#longitude_out').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-absen2').submit();
                }, function(error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        // Pengguna menolak izin lokasi
                        alert('Anda perlu memberikan izin lokasi untuk menggunakan fitur ini');
                    }
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
    const feedbackItems = document.querySelectorAll('.feedback li');

    feedbackItems.forEach(item => {
        const radio = item.querySelector('input[type="radio"]');
        const svg = item.querySelector('svg');

        item.addEventListener('click', () => {

            feedbackItems.forEach(otherItem => {
                otherItem.classList.remove('active');
            });

            if (radio.checked) {
                item.classList.add('active');
                svg.classList.add('checked');
            } else {
                svg.classList.remove('checked');
            }
        });
    });
</script>
@endpush