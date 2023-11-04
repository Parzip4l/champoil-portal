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
                                        <a href="#" class="btn btn-lg btn-primary btn-icon-text mb-2 mb-md-0 w-100 bg-custom-biru" id="btn-absen" onClick="formAbsen()" style="border-radius:10px">
                                        CLOCK IN</a>
                                </form>
                                @endif
                            @else
                            <h4 class="text-center text-danger">Day Off</h4>
                        @endif
                    @endif
                        <div class="log-absen-today mt-2">
                            <div class="card custom-card2">
                                <div class="card-header text-center bg-custom-biru" style="border-radius:12px 12px 0 0">
                                    <h5 class="text-white">Attendance Log</h5>   
                                </div>
                                <div class="card-body">
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
                                    <div class="clock-in-wrap d-flex justify-content-between">
                                    @if (isset($log->clock_out) && !empty($log->clock_out))
                                    <div class="con">
                                            <h5 class="text-bold mb-1">{{ $log->clock_out}}</h5>
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
    <div class="menu-absen-wrap desktop">
        <div class="owl-carousel owl-theme owl-basic">
            <div class="item">
                <a href="{{route('mylogs')}}">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="git-branch"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Attendence Log</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('attendence-request.create')}}">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="user-plus"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Request</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('mySlip')}} ">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="file-text"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">My Payslip</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('attendence.backup')}}">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="clock"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Backup</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('list-class') }}">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Class Room</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="javascript:void(0)">
                    <div class="icon text-center">
                        <i class="icon-lg color-custom" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">E-Workplan</p>
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

<!-- Task -->
<div class="row mb-3">
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

<!-- Mobile Menu -->
<div class="row">
    <div class="container">
        <div class="menu-mobile-wrap d-flex justify-content-between">
            <a href="" class="text-white nav-link {{ active_class(['dashboard']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="home"></i>
                    <p>Home</p>
                </div>
            </a>
            <a href="{{route('attendence-request.create')}}" class="text-white nav-link {{ active_class(['request']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="plus-circle"></i>
                    <p>Request</p>
                </div>
            </a>
            <a href="{{ route('mySlip')}}" class="text-white nav-link {{ active_class(['myslip']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="file-text"></i>
                    <p>My Slip</p>
                </div>
            </a>
            <a href="#" class="text-white nav-link {{ active_class(['profile']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="user"></i>
                    <p>Profile</p>
                </div>
            </a>
        </div>
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

  <!-- Absen -->
<script>
$(document).ready(function () {
    // Mengambil data lokasi pengguna saat tombol absen ditekan
    $('#btn-absen').on('click', function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // Mengisi nilai hidden input dengan data lokasi pengguna
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);

                // Mengirim form absen
                $('#form-absen').submit();
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
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
    function formAbsen() {
    document.getElementById("btn-absen").submit();
    }
</script>
<style>
    .owl-theme .owl-nav.disabled+.owl-dots{
        display : none;
    }
    .card.custom-card {
        background: #424874;
        border-radius:15px;
    }

    .card.custom-card2 {
        border-radius:15px;
    }

    .employee-title-job p {
        color : #eee;
    }

    .gambar {
        height:55px;
        width:55px;
        float: right;
    }

    .gambar img {
        object-fit:cover;
        border-radius : 10px;
    }

    .menu-item {
        background: #7286D3;
        padding: 15px;
        border-radius: 12px;
    }

    .menu-title h5 {
        font-weight : 400;
    }

    .bg-custom-biru {
        background: #424874!important;
    }

    .menu-mobile-wrap {
        background: #A6B1E1;
        border-radius: 15px;
        padding: 15px 20px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0 10px;
        z-index: 1020;
    }

    .menu-item-nav p {
        font-size : 13px;
        font-weight : 500;
        margin-top : 3px;
        letter-spacing : 0.3px;
        text-transform: uppercase;
    }

    .menu-item-nav svg.icon-lg {
        height : 23px;
        width : 23px;
    }

    a.text-white.nav-link.active {
        color : #424874!important;
    }

    .icon.text-center {
        background: #A6B1E1;
        border-radius: 100%;
        height: 50px;
        width: 50px;
        display: flex;
        margin: auto;
    }

    .icon.text-center svg.icon-lg {
        height: 20px;
        width: 20px;
        align-self: center;
        text-align: center;
        margin: auto;
    }

    .menu-name.text-center {
        margin-top: 10px;
        line-height: 15px;
        font-size: 12px;
    }

    .color-custom {
        color : #545C95;
    }

    @media (min-width : 676px) {
        .menu-mobile-wrap {
            display : none!important;
        }
    }

    @media (max-width:675px)
    {
        footer.footer.border-top {
            display: none;
        }

        .horizontal-menu .navbar .navbar-content .navbar-nav, button.navbar-toggler.navbar-toggler-right.d-lg-none.align-self-center {
            display : none;
        }

        .horizontal-menu {
            display : none;
        }

        .page-wrapper {
            margin-top : 0;
        }
    }
</style>
@endpush