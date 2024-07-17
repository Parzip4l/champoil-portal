@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
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
    $user = Auth::user();
@endphp
<!-- Mobile Wrap -->
<div class="absen-wrap mb-2 mobile">
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- Employee Login Details -->
            @if($user->project_id == NULL)
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
            @endif
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
                <a href="#">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="dollar-sign"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Koperasi</p>
                    </div>
                </a>
            </div>
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
            <div class="item">
                <a href="javascript:void(0)" id="maps-update">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="map"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Update Residence</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Menu -->

@if(in_array('am_access', $dataLogin)  || $employee === 'Kas' )
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
<div class="row mb-3 mobile">
    <h5 class="mb-2">Announcement</h5>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
            @if($pengumuman->isEmpty())
                <h5 class="text-center mb-2">No announcement</h5>
                <p class="text-center text-muted">Your Announcement Will Show Here</p>
            @else
                @foreach($pengumuman as $item)
                <div class="content-pengumuman mb-3">
                    <a href="" class="text-muted d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#ViewModalPengumuman{{$item->id}}">
                        <div class="judul-isi">
                            <h5 class="">{{ $item->judul }}</h5>
                            <p class="textmuted">{{ $item->konten }}</p>
                        </div>
                        <p class="card-text align-self-center"><small class="text-muted">{{ $item->publish_date }}</small></p>
                    </a>
                </div>
                @endforeach
            @endif
            </div>
        </div>
    </div>
</div>
<!-- End Announcement -->

<!-- Modal Detail Pengumuman -->
@foreach($pengumuman as $data)
<div class="modal fade" id="ViewModalPengumuman{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$data->judul}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="header-pengumuman">
                   <p>{{$data->konten}}</p>
                </div>
            </div>
            <div class="modal-footer">
                    <a href="{{ route('pengumuman.download', $data->id) }}" target="_blank" class="btn btn-primary w-100">
                        Download Attachments
                    </a>
                
            </div>
        </div>
    </div>
</div>
@endforeach
 <!-- End Modal -->

@if(in_array('hr_access', $dataLogin))
<!-- Request -->
<div class="row mb-3 mobile">
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
                <a href="{{route('dokumen.download', ['id' => $dataAbsen->id])}}" class="btn btn-primary mb-2 w-100 bg-custom-biru" style="border : none;">Download Attachment</a>
                @if ($dataAbsen->aprrove_status !=="Approved")
                <a class="btn btn-sm btn-success w-100 mb-2" href="{{ route('approve.request', $dataAbsen->id)}}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $dataAbsen->unik_code }}').submit();">
                    <span class="">Approve</span>
                </a>
                @endif

                @if ($dataAbsen->aprrove_status !=="Reject")
                <a class="btn btn-sm btn-danger w-100" href="{{ route('reject.request', ['id' => $dataAbsen->id])}}" onclick="event.preventDefault(); document.getElementById('reject-usulan-form-{{ $dataAbsen->unik_code }}').submit();">
                    <span class="">Reject</span>
                </a>
                @endif
                <!-- Form Approved -->
                <form id="setujui-usulan-form-{{ $dataAbsen->unik_code }}" action="{{ route('approve.request', ['id' => $dataAbsen->id]) }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <!-- Form Reject -->
                <form id="reject-usulan-form-{{ $dataAbsen->unik_code }}" action="{{ route('reject.request', ['id' => $dataAbsen->id]) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
<!-- News -->
<div class="row mb-3 mobile">
    <h5 class="mb-2">News</h5>
        <div class="berita-wrap">
        <div class="owl-carousel owl-theme news-owl">
            @foreach($news as $datanews)
            <div class="item">
                <a href="{{route('news.show', $datanews->id)}}">
                    <div class="card custom-card2">
                        <div class="card-body">
                            <div class="fetured-image">
                                <img src="{{ asset('images/featuredimage/' . $datanews->featuredimage) }}" alt="{{$datanews->judul}}">
                            </div>
                            <div class="title-news">
                                <h4 class="text-dark mb-2">{{$datanews->judul}}</h5>
                            </div>
                            <div class="excerpt-post mb-4">
                                <p class="text-muted">{{$datanews->excerpt}}</p>
                            </div>
                            <div class="meta-desc">
                                <p class="text-muted">Post By : {{$datanews->author}}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- End -->
<div class="row mb-4 desktop">
    <div class="col-md-12">
        <h4>{{$greeting}}, {{$employee->nama}}!</h4>
        <p class="text-muted">It's {{$hariini2->format('D')}}, {{$hariini2->format('d M Y')}}</p>
    </div>
</div>
<!-- Desktop Wrap -->
<div class="header-wrap">
    <div class="row mb-4 desktop d-flex">
        <div class="col-md-4">
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="title-data mb-3">
                        <h6>Birthday Employee</h6>
                    </div>
                    <div class="employee-birthday-wrap">
                    @if($upcomingBirthdays->isEmpty())
                        <h5 class="text-center mb-2">No Data</h5>
                        <p class="text-center text-muted">Your data Will Show Here</p>
                        @else
                        @foreach($upcomingBirthdays as $birthdaydata)
                        @php 
                            $usiaBaru = $birthdaydata->usia + 1;
                        @endphp
                        <div class="employee-item d-flex mb-2">
                            <div class="photo-profile me-2">
                                <img src="{{ asset('images/' . $birthdaydata->gambar) }}" alt="">
                            </div>
                            <div class="detail-item-employee-wrap align-self-center">
                                <div class="detail-employee ">
                                    <h6>{{$birthdaydata->nama}}</h6>
                                </div>
                                <div class="tanggal-lahir align-self-center">
                                    <p class="text-muted" style="font-size: 12px;">{{$birthdaydata->tanggal_lahir}} || {{$usiaBaru}} Tahun</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card2">
                <div class="card-body">
                    <h6 class="mb-3">Daftar Kontrak Yang Akan Segera Berakhir</h6>
                    @if($kontrakKaryawan->isEmpty())
                        <h5 class="text-center mb-2">No Data</h5>
                        <p class="text-center text-muted">Your data Will Show Here</p>
                        @else
                    <div class="employee-birthday-wrap">
                        @foreach($kontrakKaryawan as $DataKontrak)
                        <div class="employee-item d-flex mb-2">
                            <div class="photo-profile me-2">
                                <img src="{{ asset('images/' . $DataKontrak->gambar) }}" alt="">
                            </div>
                            <div class="detail-item-employee-wrap align-self-center">
                                <div class="detail-employee ">
                                    <h6>{{$DataKontrak->nama}}</h6>
                                </div>
                                <div class="tanggal-lahir align-self-center">
                                    <p class="text-muted" style="font-size: 12px;">{{ now()->diffInDays($DataKontrak->berakhirkontrak, false) }} Hari</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card2">
                <div class="card-body">
                    @if($pengumuman->isEmpty())
                        <h5 class="text-center mb-2">No announcement</h5>
                        <p class="text-center text-muted">Your Announcement Will Show Here</p>
                    @else
                        @foreach($pengumuman as $item)
                        <div class="content-pengumuman mb-3">
                            <a href="" class="text-muted d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#ViewModalPengumuman{{$item->id}}">
                                <div class="judul-isi">
                                    <h5 class="">{{ $item->judul }}</h5>
                                    <p class="textmuted">{{ $item->konten }}</p>
                                </div>
                                <p class="card-text align-self-center"><small class="text-muted">{{ $item->publish_date }}</small></p>
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="chart-wrap mb-4 desktop">
    @if($user->project_id==NULL)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card custom-card2">
                <div class="card-body">
                    <h6 class="mb-2">Daftar karyawan tanpa keterangan hari ini.</h6>
                    <table class="table" id="dataTableExample">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Organisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawanTidakAbsenHariIni as $alpha)
                            <tr>
                                <td>{{$alpha->nama}}</td>
                                <td>{{$alpha->organisasi}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="card custom-card2 mb-4">
                <div class="card-body">
                    <h6 class="mb-2">Total Absensi Day to Day.</h6>
                    <canvas id="ChartAbsen"></canvas>    
                </div>
            </div>
            <div class="card custom-card2">
                <div class="card-body">
                    <h6 class="mb-2">Total Absensi Periode.</h6>
                    <canvas id="PersentaseHadir" style="max-height:294px;"></canvas>      
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card custom-card2">
                <div class="card-body">
                <h6 class="mb-2">Total Karyawan.</h6>
                    <canvas id="ChartKaryawan"></canvas>    
                </div>
            </div>
        </div>
        @if($user->project_id ==NULL)
        <div class="col-md-6">
            <div class="card custom-card2">
                <div class="card-body">
                    <h6 class="mb-2">Daftar karyawan yang akan segera berakhir kontrak.</h6>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal Berakhir</th>
                                <th>Sisa Kontrak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kontrakKaryawan as $dataKontrak)
                            <tr>
                                <td>{{$dataKontrak->nama}}</td>
                                <td>{{$dataKontrak->berakhirkontrak}}</td>
                                <td>{{ now()->diffInDays($dataKontrak->berakhirkontrak, false) }} Hari</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
        <h6 class="mb-2">Payrol Statistik</h6>
            <div class="card custom-card2">
                <div class="card-body">
                    <canvas id="salaryChart"></canvas>    
                </div>
            </div>
        </div>
        <div class="col-md-6">
        <h6 class="mb-2">Payrol Statistik Years</h6>
            <div class="card custom-card2">
                <div class="card-body">
                    <canvas id="salaryChartYears"></canvas>    
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- End Chart Section -->
<!-- Task -->
<div class="row mb-6 mobile">
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

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/chartjs/chart.umd.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/carousel.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/chartjs.js') }}"></script>
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
    $('#maps-update').on('click', function() {
        Swal.fire({
            title: "Apakah Anda Berada Di Lokasi Tempat Tinggal Anda ?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        // Data to be sent in the POST request
                        var data_field = {
                            longitude: longitude, // Replace with actual data
                            latitude:latitude, // Replace with actual data
                            user_id:<?php echo $user->id ?>
                        };

                        $.ajax({
                            url: "/api/v1/map-domisili", // URL of the API endpoint
                            method: "POST", // Change the method to POST
                            data: data_field,
                            dataType: "json", // Expected data type of the response (JSON in this case)
                            headers: {
                                "X-CSRF-Token": '{{ csrf_token() }}' // CSRF token header
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: "middle",
                                    icon: "success",
                                    title: "Your work has been saved",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    });
                }
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    });
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
$(function() {
    'use strict';

    if ($('.news-owl').length) {
        $('.news-owl').owlCarousel({
            loop: true,
            margin: 25,
            nav: false,
            responsive: {
                0: {
                    items: 1.5
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
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
<script>
    var data = {!! json_encode($dataAbsenByDay) !!};
    
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('ChartAbsen').getContext('2d');
        var ChartAbsen = new Chart(ctx, {
            type: 'line',
            data: data,
        });
    });

    var dataKehadiran = {!! json_encode($DataTotalKehadiran) !!};
    
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('PersentaseHadir').getContext('2d');
        var PersentaseHadir = new Chart(ctx, {
            type: 'doughnut',
            data: dataKehadiran,
        });
    });
    
    var dataKaryawan = {!! json_encode($ChartKaryawan) !!};

document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('ChartKaryawan').getContext('2d');
    var ChartKaryawan = new Chart(ctx, {
        type: 'bar',
        data: dataKaryawan,
        options: {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
});
  </script>
  <script>
        var ctx = document.getElementById('salaryChart').getContext('2d');
        var salaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($managementData->keys()) !!},
                datasets: [{
                    label: 'Management Leaders',
                    data: {!! json_encode($managementData->values()) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Frontline',
                    data: {!! json_encode($frontlineData->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById('salaryChartYears').getContext('2d');
        var salaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($managementData2->keys()) !!},
                datasets: [{
                    label: 'Management Leaders',
                    data: {!! json_encode($managementData2->values()) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Frontline',
                    data: {!! json_encode($frontlineData2->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endpush