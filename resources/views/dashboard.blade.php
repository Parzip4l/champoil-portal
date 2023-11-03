@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/animate-css/animate.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="absen-wrap mb-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="button-absen">
                      @php 
                        $employee = \App\Employee::where('nik', Auth::user()->name)->first();
                      @endphp
                    <h4 class="mb-3 text-center">{{$greeting}} {{ $employee->nama }} {{$greeting === 'Selamat Pagi' ? 'Selamat Beraktifitas' : ''}}</h4>
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
                                        <a href="#" class="btn btn-lg btn-primary btn-icon-text mb-2 mb-md-0 w-100" id="btn-absen" onClick="formAbsen()">
                                        Clock IN</a>
                                </form>
                                @endif
                            @else
                            <h4 class="text-center text-danger">Day Off</h4>
                        @endif
                    @endif
                        <div class="log-absen-today mt-2">
                            <div class="card ">
                                <div class="card-header text-center bg-warning">
                                    <h5>Attendance Log</h5>   
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
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="menu-absen-wrap desktop">
                        <div class="owl-carousel owl-theme owl-basic">
                            <div class="item">
                                <a href="{{route('mylogs')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="git-branch"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Attendence Log</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{route('attendence-request.create')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="user-plus"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Request Attendence</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{ route('mySlip')}} ">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="file-text"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">My Payslip</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{route('attendence.backup')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="clock"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Backup Attendence</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{ route('list-class') }}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="book"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Class Room</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="javascript:void(0)">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="book"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">E-Workplan</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
</style>
@endpush