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
            <div class="card custom-card2 mobile">
                <div class="card-body">
                    <div class="button-absen">
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
                                $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
                                    ->select('unit_bisnis','organisasi')
                                    ->first();
                            @endphp
                            @if($karyawanLogin->organisasi === 'Management Leaders' || $karyawanLogin->unit_bisnis === 'CHAMPOIL' || ($hasScheduleForToday))
                                @if ($clockin)
                                <form action="{{ route('clockout') }}" method="POST" id="form-absen2">
                                @csrf   
                                    <input type="hidden" name="latitude_out" id="latitude_out">
                                    <input type="hidden" name="longitude_out" id="longitude_out">
                                    <input type="hidden" name="status" value="H">
                                    <a href="#" class="btn btn-lg btn-danger btn-icon-text mb-2 mb-md-0 w-100" id="btnout">Clock Out</a>
                                </form>
                                @else
                                <form action="{{ route('clockin') }}" method="POST" class="me-1" id="form-absen" enctype="multipart/form-data">
                                    @csrf
                                        <!-- Add an input for taking a photo -->
                                        <div class="card custom-card2 mb-3">
                                            <div class="card-body">
                                                <div class="photo-take d-flex">
                                                    <label for="photo" class="custom-file-upload">
                                                        <i class="icon-lg" data-feather="camera"></i>
                                                        <input type="file" name="photo" class="form-control custom-file-upload" accept="image/*" capture="camera" id="photoInput">
                                                    </label>
                                                    <p class="text-muted">Take Selfie</p>
                                                </div>
                                                <div id="photoPreview"></div>
                                            </div>
                                        </div>
                                        <!-- Add a preview container for the captured photo -->
                                        
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
<div class="row mb-3 mobile">
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

@if(in_array('am_access', $dataLogin) || $employee === 'Kas' )
<!-- Pengajuan Schedule -->
<div class="row mb-3">
    <h5 class="mb-2">Pengajuan Schedule</h5>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                @forelse($pengajuanSchedule as $pengajuanData)
                <div class="wrapper-pengajuan d-flex justify-content-between mb-4">
                    @php 
                        $pic = \App\Employee::where('nik', $pengajuanData->namapengaju)->first();
                        $project = \App\ModelCG\Project::where('id', $pengajuanData->project)->first();
                    @endphp
                    <div class="nama-karyawan align-self-center">
                        <a href="{{ route('pengajuanschedule.details', ['project' => $pengajuanData->project, 'periode' => $pengajuanData->periode]) }}" class="color-custom mb-2">
                            <h5 class="mb-2">{{$project->name}}</h5>
                        </a>
                        <span class="text-muted">{{$pengajuanData->periode}}</span>
                    </div>
                    <div class="nama-pic">
                        <h5 class="color-custom mb-2 text-right">PIC Name</h5>
                        <span class="text-muted">{{$pic->nama}}</span>
                    </div>
                </div>
                <hr>
                @empty
                <div class="text-null text-center">
                    <h5 class="mb-2">No Schedule Request</h5>
                    <p class="text-muted">Schedule Request Will Show Here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<!-- End Pengajuan -->
@endif

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
                @forelse($dataRequest as $dataAbsen)
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
                @empty
                <div class="text-null text-center">
                    <h5 class="mb-2">No Attendence Request</h5>
                    <p class="text-muted">Attendence Request Will Show Here.</p>
                </div>
                @endforelse
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

<!-- Modal Announcement -->
<div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="announcementModalLabel">TRUEST HRIS Announcement</h5>
            </div>
            <div class="modal-body">
                <!-- Add your announcement content here -->
                <p>1. Aplikasi TRUEST HRIS Untuk saat ini sedang dilakukan maintenance. Mohon Maaf Untuk Ketidaknyamanannya.</p><br>
                <p>2. Dikarenakan Aplikasi Sedang Dilakukan Meiantenance, Absensi bisa digunakan di browser HP Masing Masing. Klik <a href="https://hris.truest.co.id">Disini</a></p><br>
                <p>3. Saat untuk update data pribadi sudah bisa dilukan masing masing termasuk foto profile.</p>
                @if($employee === 'Kas' )
                <p>4. Untuk PIC Project Sudah Bisa Membuat Pengajuan Schedule Yang Akan Disetujui Oleh Area Manager.</p>
                @endif
                <button type="button" class="btn btn-primary mt-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
    $(document).ready(function () {
        // Check if the modal has been closed before using a cookie
        if (!getCookie("announcementModalClosed")) {
            $('#announcementModal').modal('show');
        }

        // Set a cookie when the modal is closed
        $('#announcementModal').on('hidden.bs.modal', function () {
            setCookie("announcementModalClosed", "true", 365);
        });
    });

    // Cookie functions
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
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
<style>
    label.custom-file-upload {
        position: relative;
        margin: 0;
        margin-left : 10px;
        width: 30px;
        height: 30px;
        overflow: hidden;
        font-size: 0;
    }
    label.custom-file-upload:hover i {
        color: #27ae60;
    }
    label.custom-file-upload input[type='file'] {
        z-index: 3;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
    }
    div#photoPreview img {
        height: 100px;
        width: 100px;
        object-fit: cover;
        border-radius: 100%;
    }
</style>
<!-- Absen -->
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btn-absen').on('click', function (e) {
            e.preventDefault(); // Prevent the default behavior of the link

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude').val(position.coords.latitude);
                    $('#longitude').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-absen').submit();
                }, function (error) {
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
<script>
    document.getElementById('photoInput').addEventListener('change', function (event) {
        var previewContainer = document.getElementById('photoPreview');
        previewContainer.innerHTML = '';

        var fileInput = event.target;
        var files = fileInput.files;

        if (files.length > 0) {
            var image = document.createElement('img');
            image.src = URL.createObjectURL(files[0]);
            image.style.maxWidth = '100%';
            previewContainer.appendChild(image);
        }
    });
</script>
@endpush