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

    $steps = \App\Company\CompanySetupChecklist::where('company_code', auth()->user()->company)->get()->keyBy('key');
    $labels = \App\Company\CompanySetupChecklist::defaultSteps();
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
                <a href="{{route('emergency.user')}}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="alert-circle"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Emergency</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('task-management.index')}}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="calendar"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Task Management</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{route('koperasi-page.index')}}">
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
                <a href="{{ route('scan-project') }}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Patroli Project</p>
                    </div>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('scan-lapsit') }}">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="book"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Lapsit</p>
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
            <div class="item">
                <a href="javascript:void(0)" id="voice">
                    <div class="icon text-center">
                        <i class="icon-lg text-white" data-feather="message-square"></i>
                    </div>
                    <div class="menu-name text-center">
                        <p class="text-muted">Voice</p>
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

@if ($progress < 100)
<div id="checklist-card" class="card p-4 mb-4 custom-card2 desktop">
    <div class="flex justify-between items-center mb-2">
        <h4 class="text-lg font-semibold">Checklist Setup Perusahaan</h4>
    </div>

    <div class="progress mb-3" style="height: 10px;">
        <div id="checklist-progress-bar" class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <p id="checklist-progress-text" class="text-sm text-muted mb-3">Progress: {{ $progress }}%</p>

    <ul id="checklist-list" class="list-unstyled space-y-2">
        @foreach ($labels as $key => $label)
            @php
                $step = $steps->get($key);
                $completed = $step ? $step->is_completed : false;
            @endphp
            <li class="d-flex align-items-center justify-content-between">
                <div>
                    <input type="checkbox" class="form-check-input checklist-toggle" data-key="{{ $key }}" {{ $completed ? 'checked' : '' }}>
                    <label class="form-check-label {{ $completed ? 'text-success text-decoration-line-through' : '' }}">
                        {{ $label }}
                    </label>
                </div>
            </li>
        @endforeach
    </ul>
</div>

{{-- Alert jika selesai --}}
<div id="checklist-success-alert" class="alert alert-success d-none mt-3" role="alert">
    ✅ Perusahaan Siap Digunakan! Semua setup awal telah diselesaikan.
</div>

@else
<div id="checklist-success-alert" class="alert alert-success mt-3" role="alert">
    ✅ Perusahaan Siap Digunakan! Semua setup awal telah diselesaikan.
</div>
@endif




@if(in_array('superadmin_access', $dataLogin))
@if($user->company != 'NOTARIS_ITR')
<div class="row mb-4 d-flex desktop">
    <div class="col-md-3 desktop">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-card">
                    <h6>Total Karyawan</h6>
                </div>
                @php  $totalKaryawan = $DataManagement + $DataFrontline @endphp
                <div class="count mt-2">
                    <h2>{{$totalKaryawan}}</h2>
                </div>
            </div>
            <div class="card-footer d-flex">
                @if ($percentageChangeAll > 0)
                    <span class="badge rounded-pill me-2 bg-success">
                        <i class="link-icon icon-sm" data-feather="chevron-up"></i> {{ number_format($percentageChangeAll, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @elseif ($percentageChangeAll < 0)
                    <span class="badge rounded-pill me-2 bg-danger">
                        <i class="link-icon icon-sm" data-feather="chevron-down"></i> {{ number_format($percentageChangeAll, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @else
                    <span class="badge rounded-pill me-2 bg-secondary">
                        <i class="link-icon icon-sm" data-feather="minus"></i> {{ number_format($percentageChangeAll, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3 desktop">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-card">
                    <h6>Management Leaders</h6>
                </div>
                <div class="count mt-2">
                    <h2>{{$DataManagement}}</h2>
                </div>
            </div>
            <div class="card-footer d-flex">
                @if ($percentageChangeManagement > 0)
                    <span class="badge rounded-pill me-2 bg-success">
                        <i class="link-icon icon-sm" data-feather="chevron-up"></i> {{ number_format($percentageChangeManagement, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @elseif ($percentageChangeManagement < 0)
                    <span class="badge rounded-pill me-2 bg-danger">
                        <i class="link-icon icon-sm" data-feather="chevron-down"></i> {{ number_format($percentageChangeManagement, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @else
                    <span class="badge rounded-pill me-2 bg-secondary">
                        <i class="link-icon icon-sm" data-feather="minus"></i> {{ number_format($percentageChangeManagement, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3 desktop">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-card">
                    <h6>Frontline Officer</h6>
                </div>
                <div class="count mt-2">
                    <h2>{{$DataFrontline}}</h2>
                </div>
            </div>
            <div class="card-footer d-flex">
                @if ($percentageChangeFrontline > 0)
                    <span class="badge rounded-pill me-2 bg-success">
                        <i class="link-icon icon-sm" data-feather="chevron-up"></i> {{ number_format($percentageChangeFrontline, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @elseif ($percentageChangeFrontline < 0)
                    <span class="badge rounded-pill me-2 bg-danger">
                        <i class="link-icon icon-sm" data-feather="chevron-down"></i> {{ number_format($percentageChangeFrontline, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @else
                    <span class="badge rounded-pill me-2 bg-secondary">
                        <i class="link-icon icon-sm" data-feather="minus"></i> {{ number_format($percentageChangeFrontline, 2) }}%
                    </span>
                    <p class="text-muted">Periode sebelumnya</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3 desktop">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-card">
                    <h6>Payrol Bulan Ini</h6>
                </div>
                <div class="count mt-2">
                    <h2>Rp {{ number_format($totalValue) }}</h2>
                </div>
            </div>
            <div class="card-footer d-flex">
                @if ($percentageChange > 0)
                    <span class="badge rounded-pill me-2 bg-success"><i class="link-icon icon-sm" data-feather="chevron-up"></i> {{ number_format($percentageChange, 2) }}%</span> <p class="text-muted "> Periode sebelumnya</p>
                @else
                    <span class="badge rounded-pill me-2 bg-danger"><i class="link-icon icon-sm" data-feather="chevron-down"></i> {{ number_format($percentageChange, 2) }}%</span>  <p class="text-muted "> periode sebelumnya</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row mb-4 d-flex desktop">
    
    <div class="col-md-3 desktop">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-card">
                    <h6>Karyawan Tanpa BPJS</h6>
                </div>
                <div class="count mt-2">
                    <h2>{{$data_bpjs['bpjs']}}</h2>
                </div>
            </div>
            <div class="card-footer d-flex">
                
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!--  -->
@if($user->company == 'NOTARIS_ITR')
<div class="row">
    <div class="col-md-3 col-6 mb-2">
        <div class="card custom-card2" style="background:#777CF0">
            <div class="card-body text-white">
                <div class="task-overview-data d-flex">
                    <i class="icon-xl me-2 mt-1" data-feather="file-text"></i>
                    <div class="left-item-overview">
                        <h1>{{$totalTasks}}</h1>
                        <p>Total Task</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card custom-card2" style="background:#44BBF9">
            <div class="card-body text-white">
                <div class="task-overview-data d-flex">
                    <i class="icon-xl me-2 mt-1" data-feather="check-circle"></i>
                    <div class="left-item-overview">
                        <h1>{{$completedTasks}}</h1>
                        <p>Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card custom-card2" style="background:#FBB855">
            <div class="card-body text-white">
                <div class="task-overview-data d-flex">
                    <i class="icon-xl me-2 mt-1" data-feather="clock"></i>
                    <div class="left-item-overview">
                        <h1>{{$inProgressTasks}}</h1>
                        <p>In Progress</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card custom-card2" style="background:#C10000">
            <div class="card-body text-white">
                <div class="task-overview-data d-flex">
                    <i class="icon-xl me-2 mt-1" data-feather="x-circle"></i>
                    <div class="left-item-overview">
                        <h1>{{$overdueTasks}}</h1>
                        <p>Over Due</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6 mb-4">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Task On Progress</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Progress</th>
                            <th>Prioritas</th>
                            <th>Due Date</th>
                            <th>User</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                            @endphp
                            @foreach ($TaskOnprogress as $data)
                                @if ($data->status == "In Progress")
                                <tr>
                                    <td><a href="{{route('task-management.index')}}">{{$data->title}}</a></td>
                                    <td>
                                        @if($data->progress > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->progress }}%;" aria-valuenow="{{ $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($data->progress) }}%
                                            </div>
                                        </div>
                                        @else 
                                            0%
                                        @endif 
                                    </td>
                                    <td>
                                        @if($data->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                                        @endif
                                    </td>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($data->due_date);
                                    @endphp
                                    <td>{{$formattedDate->format('D, d M Y')}}</td>
                                    <td class="d-flex">
                                        @foreach($data->assignedUsers as $data2)
                                        <div class="data-user d-flex">
                                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $data2->gambar) }}" alt="{{ $data2->nik }}">
                                            <div class="tooltips-name">
                                                <p class="text-muted">{{ $data2->nama }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Task Over Due</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Progress</th>
                            <th>Prioritas</th>
                            <th>Due Date</th>
                            <th>User</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                            @endphp
                            @foreach ($TaskOnprogress as $data)
                            @php 
                                $date = \Carbon\Carbon::parse($data->due_date);
                            @endphp
                                @if(\Carbon\Carbon::parse($data->due_date)->isPast())
                                <tr>
                                    <td><a href="{{route('task-management.index')}}">{{$data->title}}</a></td>
                                    <td>
                                        @if($data->progress > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->progress }}%;" aria-valuenow="{{ $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($data->progress) }}%
                                            </div>
                                        </div>
                                        @else 
                                            0%
                                        @endif 
                                    </td>
                                    <td>
                                        @if($data->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                                        @endif
                                    </td>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($data->due_date);
                                    @endphp
                                    <td>{{$formattedDate->format('D, d M Y')}}</td>
                                    <td class="d-flex">
                                        @foreach($data->assignedUsers as $data2)
                                        <div class="data-user d-flex">
                                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $data2->gambar) }}" alt="{{ $data2->nik }}">
                                            <div class="tooltips-name">
                                                <p class="text-muted">{{ $data2->nama }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Task Completed</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Progress</th>
                            <th>Prioritas</th>
                            <th>Due Date</th>
                            <th>User</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                            @endphp
                            @foreach ($TaskOnprogress as $data)
                                @if ($data->status == "Completed")
                                <tr>
                                    <td><a href="{{route('task-management.index')}}">{{$data->title}}</a></td>
                                    <td>
                                        @if($data->progress > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->progress }}%;" aria-valuenow="{{ $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($data->progress) }}%
                                            </div>
                                        </div>
                                        @else 
                                            0%
                                        @endif 
                                    </td>
                                    <td>
                                        @if($data->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                                        @endif
                                    </td>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($data->due_date);
                                    @endphp
                                    <td>{{$formattedDate->format('D, d M Y')}}</td>
                                    <td class="d-flex">
                                        @foreach($data->assignedUsers as $data2)
                                        <div class="data-user d-flex">
                                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $data2->gambar) }}" alt="{{ $data2->nik }}">
                                            <div class="tooltips-name">
                                                <p class="text-muted">{{ $data2->nama }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Task To Do</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Progress</th>
                            <th>Prioritas</th>
                            <th>Due Date</th>
                            <th>User</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                            @endphp
                            @foreach ($TaskOnprogress as $data)
                                @if ($data->status == "TO DO")
                                <tr>
                                    <td><a href="{{route('task-management.index')}}">{{$data->title}}</a></td>
                                    <td>
                                        @if($data->progress > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->progress }}%;" aria-valuenow="{{ $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($data->progress) }}%
                                            </div>
                                        </div>
                                        @else 
                                            0%
                                        @endif 
                                    </td>
                                    <td>
                                        @if($data->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                                        @endif
                                    </td>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($data->due_date);
                                    @endphp
                                    <td>{{$formattedDate->format('D, d M Y')}}</td>
                                    <td class="d-flex">
                                        @foreach($data->assignedUsers as $data2)
                                        <div class="data-user d-flex">
                                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $data2->gambar) }}" alt="{{ $data2->nik }}">
                                            <div class="tooltips-name">
                                                <p class="text-muted">{{ $data2->nama }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!--  -->


<!-- Desktop Wrap -->
@if($user->company != 'NOTARIS_ITR')
<div class="header-wrap desktop">
    <div class="row mb-4 desktop d-flex">
        <div class="col-md-4">
            <div class="card custom-card2">
                <div class="card-header">
                    <h6>Birthday Employee</h6>
                </div>
                <div class="card-body">
                    <div class="employee-birthday-wrap">
                    @if($upcomingBirthdays->isEmpty())
                        <h5 class="text-center mb-2">No Data</h5>
                        <p class="text-center text-muted">Your data Will Show Here</p>
                        @else
                        @foreach($upcomingBirthdays as $birthdaydata)
                        @php 
                            $usiaBaru = $birthdaydata->usia;
                        @endphp
                        <div class="employee-item d-flex mb-2">
                            <div class="photo-profile2 me-2 align-self-center">
                                <img src="{{ asset('images/' . ($birthdaydata->gambar ?? '3135715.png')) }}" alt="">
                            </div>
                            <div class="detail-item-employee-wrap align-self-center">
                                <div class="detail-employee ">
                                    <h6>{{$birthdaydata->nama}}</h6>
                                </div>
                                <div class="tanggal-lahir align-self-center">
                                    <p class="text-muted" style="font-size: 12px;">{{$birthdaydata->tanggal_lahir}}</p><span class="badge rounded-pill me-2 bg-primary">{{$usiaBaru}} Tahun</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($user->project_id==NULL)
        <div class="col-md-4">
            <div class="card custom-card2">
                <div class="card-header">
                    <h6>Kontrak Reminder</h6>
                </div>
                <div class="card-body">
                    @if($kontrakKaryawan->isEmpty())
                        <h5 class="text-center mb-2">No Data</h5>
                        <p class="text-center text-muted">Your data Will Show Here</p>
                        @else
                    <div class="employee-birthday-wrap">
                        @foreach($kontrakKaryawan as $DataKontrak)
                        <div class="employee-item d-flex mb-2">
                            <div class="photo-profile me-2">
                                <img src="{{ asset('images/' . ($DataKontrak->gambar ?? '3135715.png')) }}" alt="">
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
                <div class="card-header">
                    <h6>Pengumuman</h6>
                </div>
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
        @endif
    </div>
</div>

<!-- Chart Section -->
<div class="chart-wrap mb-4 desktop">
    @if($user->project_id==NULL)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card custom-card2 mb-4">
                <div class="card-header">
                    <h6>Daftar karyawan tanpa keterangan hari ini.</h6>
                </div>
                <div class="card-body">
                    @if($karyawanTidakAbsenHariIni->isEmpty())
                        <h5 class="text-center mb-2">No Data</h5>
                        <p class="text-center text-muted">Your data Will Show Here</p>
                        @else
                    <div class="employee-birthday-wrap">
                        @foreach($karyawanTidakAbsenHariIni as $alpha)
                        <div class="employee-item d-flex mb-2">
                            <div class="photo-profile me-2">
                                <img src="{{ asset('images/' . ($alpha->gambar ?? '3135715.png' )) }}" alt="">
                            </div>
                            <div class="detail-item-employee-wrap align-self-center">
                                <div class="detail-employee ">
                                    <h6>{{$alpha->nama}}</h6>
                                </div>
                                <div class="tanggal-lahir align-self-center">
                                    <p class="text-muted" style="font-size: 12px;">{{$alpha->organisasi}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @if ($karyawanTidakAbsenHariIni->hasPages())
                        <ul class="pagination-custom">
                            <!-- Previous Page Link -->
                            @if ($karyawanTidakAbsenHariIni->onFirstPage())
                                <li class="disabled"><span>&laquo;</span></li>
                            @else
                                <li><a href="{{ $karyawanTidakAbsenHariIni->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                            @endif

                            <!-- Pagination Elements -->
                            @php
                                $start = max($karyawanTidakAbsenHariIni->currentPage() - 2, 1);
                                $end = min($karyawanTidakAbsenHariIni->currentPage() + 2, $karyawanTidakAbsenHariIni->lastPage());
                            @endphp

                            @if ($start > 1)
                                <li><a href="{{ $karyawanTidakAbsenHariIni->url(1) }}">1</a></li>
                                @if ($start > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $karyawanTidakAbsenHariIni->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $karyawanTidakAbsenHariIni->url($page) }}">{{ $page }}</a></li>
                                @endif
                            @endfor

                            @if ($end < $karyawanTidakAbsenHariIni->lastPage())
                                @if ($end < $karyawanTidakAbsenHariIni->lastPage() - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li><a href="{{ $karyawanTidakAbsenHariIni->url($karyawanTidakAbsenHariIni->lastPage()) }}">{{ $karyawanTidakAbsenHariIni->lastPage() }}</a></li>
                            @endif

                            <!-- Next Page Link -->
                            @if ($karyawanTidakAbsenHariIni->hasMorePages())
                                <li><a href="{{ $karyawanTidakAbsenHariIni->nextPageUrl() }}" rel="next"> &raquo;</a></li>
                            @else
                                <li class="disabled"><span> &raquo;</span></li>
                            @endif
                        </ul>
                        <div class="pagination-summary">
                            Showing {{ $karyawanTidakAbsenHariIni->firstItem() }} to {{ $karyawanTidakAbsenHariIni->lastItem() }} of {{ $karyawanTidakAbsenHariIni->total() }} results
                        </div>
                    @endif
                    
                </div>
            </div>
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('client_access', $dataLogin))
            <div class="card custom-card2">
                <div class="card-header">
                    <h6>Total Absensi Periode.</h6>
                </div>
                <div class="card-body">
                    <canvas id="PersentaseHadir" style="max-height:294px;"></canvas>      
                </div>
            </div>
            @endif
            <div class="card custom-card2 mt-3">
                <div class="card-header">
                    <h6>Grafik User Slack</h6>
                </div>
                <div class="card-body">
                    <canvas id="user_slack" style="max-height:294px;"></canvas>      
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card custom-card2 mb-4">
                <div class="card-header">
                    <h6>Statistik Absensi Harian.</h6>
                </div>
                <div class="card-body">
                    <canvas id="ChartAbsen"></canvas>    
                </div>
            </div>
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('client_access', $dataLogin))
            <div class="card custom-card2">
                <div class="card-header">
                    <h6>Payrol Statistik.</h6>
                </div>
                <div class="card-body">
                    <canvas id="salaryChart"></canvas>   
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row mt-3 d-none">
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
                     
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif
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


  

    var userSlack = {!! json_encode($UserSlack) !!};
    
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('user_slack').getContext('2d');
        var slack = new Chart(ctx, {
            type: 'doughnut',
            data: userSlack,
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
    <style>
        .modal-bottom {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            margin: 0;
            border-radius: 25px 25px 0 0;
            max-width: 100%;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-body {
            text-align: center;
        }

        a.btn.btn-custom {
            background: red;
            padding: 100px 75px;
            border-radius: 50%;
            color: #fff;
        }
    </style>
    <script>
        document.querySelectorAll('.checklist-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const key = this.dataset.key;
                const formData = new FormData();
                formData.append('key', key);

                fetch("{{ route('checklist.toggle', ['key' => 'dummy']) }}".replace('dummy', key), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data); 
                    if (data.success) {
                        const progress = data.progress;
                        document.getElementById('checklist-progress-bar').style.width = progress + '%';
                        document.getElementById('checklist-progress-text').textContent = 'Progress: ' + progress + '%';

                        document.querySelectorAll('.checklist-toggle').forEach(cb => {
                            const label = cb.nextElementSibling;
                            if (cb.checked) {
                                label.classList.add('text-success', 'text-decoration-line-through');
                            } else {
                                label.classList.remove('text-success', 'text-decoration-line-through');
                            }
                        });

                        if (progress === 100) {
                            document.getElementById('checklist-card')?.remove();
                            document.getElementById('checklist-success-alert')?.classList.remove('d-none');
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        });

    </script>
    <script>
        // Sembunyikan alert setelah 10 detik
        setTimeout(() => {
            const alert = document.getElementById('checklist-success-alert');
            if (alert) {
                alert.classList.add('d-none');
            }
        }, 10000);
    </script>

@endpush