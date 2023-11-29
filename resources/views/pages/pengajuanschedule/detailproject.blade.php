@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php 
    $projectname = \App\ModelCG\Project::find($project)->name;
    $picProject = \App\Employee::where('nik', $namapengaju)->first();
    $datastatus = \App\PengajuanSchedule\PengajuanSchedule::where('project',$project)->where('periode',$periode)->pluck('status')->first();
    $dataApprove = \App\PengajuanSchedule\PengajuanSchedule::where('project',$project)->where('periode',$periode)->pluck('disetujui_oleh')->first();
    $namaaprroval = \App\Employee::where('nik', $dataApprove)->first();
@endphp
<div class="row mb-3 desktop">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('kas/pengajuan-schedule')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row mb-3 mobile">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('dashboard')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6 grid-margin stretch-card mb-3 desktop">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Details Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="detail-wrap d-flex justify-content-between">
                    <div class="detail-project-data">
                        <h6 class="mb-1">Project Name</h6>
                        <p class="text-muted">{{$projectname}}</p>
                    </div>
                    <div class="detail-project-data">
                        <h6 class="mb-1">Schedule Periode</h6>
                        <p class="text-muted">{{$periode}}</p>
                    </div>
                    <div class="detail-project-data">
                        <h6 class="mb-1">PIC Project</h6>
                        <p class="text-muted">{{$picProject->nama}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card mb-3 desktop">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Action</h5>
            </div>
            <div class="card-body">
                <div class="detail-wrap grid-margin stretch-card mb-0">
                    @if ($datastatus === 'Ditinjau')
                    <div class="col-md-6">
                        <div class="detail-project-data me-2">
                            <a href="{{ route('approve.requestschedule', ['project' => $project, 'periode' => $periode]) }}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $project }}').submit();" class="btn btn-success w-100 me-2">Setujui Pengajuan</a>
                        </div>
                        <form id="setujui-usulan-form-{{ $project }}" action="{{ route('approve.requestschedule', ['project' => $project, 'periode' => $periode]) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @elseif($datastatus === 'Rejected' )
                    <div class="col-md-12">
                        <div class="detail-project-data">
                            <button class="btn btn-danger w-100" disabled>Pengajuan Sudah Ditolak</button>
                        </div>
                    </div>
                    @else 
                    <div class="col-md-12">
                        <div class="detail-project-data me-2">
                            <button class="btn btn-success w-100" disabled>Sudah Disetujui Oleh {{$namaaprroval->nama}}  </button>
                        </div>
                    </div>
                    @endif
                    @if ($datastatus === 'Ditinjau' )
                    <div class="col-md-6">
                        <div class="detail-project-data">
                            <a href="#" class="btn btn-danger w-100" onclick="event.preventDefault(); document.getElementById('tolak-usulan-form-{{ $project }}').submit();">Tolak Pengajuan</a>
                        </div>
                        <form id="tolak-usulan-form-{{ $project }}" action="{{ route('reject.requestschedule', ['project' => $project, 'periode' => $periode]) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @elseif($datastatus === 'Rejected' )
                    <div class="col-md-6 d-none">
                        <div class="detail-project-data">
                            <button class="btn btn-danger w-100" disabled>Pengajuan Sudah Ditolak Oleh {{$namaaprroval->nama}}</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Version -->
<div class="row mobile">
    <div class="col-md-12 mb-3">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Details Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="detail-wrap">
                    <div class="detail-project-data mb-2">
                        <h6 class="mb-1">Project Name</h6>
                        <p class="text-muted">{{$projectname}}</p>
                    </div>
                    <div class="detail-project-data mb-2">
                        <h6 class="mb-1">Schedule Periode</h6>
                        <p class="text-muted">{{$periode}}</p>
                    </div>
                    <div class="detail-project-data mb-2">
                        <h6 class="mb-1">PIC Project</h6>
                        <p class="text-muted">{{$picProject->nama}}</p>
                    </div>
                </div>
                <div class="detail-wrap grid-margin stretch-card mb-0">
                    @if ($datastatus === 'Ditinjau')
                    <div class="col-md-6">
                        <div class="detail-project-data me-2">
                            <a href="{{ route('approve.requestschedule', ['project' => $project, 'periode' => $periode]) }}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $project }}').submit();" class="btn btn-success w-100 me-2">Setujui Pengajuan</a>
                        </div>
                        <form id="setujui-usulan-form-{{ $project }}" action="{{ route('approve.requestschedule', ['project' => $project, 'periode' => $periode]) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @elseif($datastatus === 'Rejected' )
                    <div class="col-md-12">
                        <div class="detail-project-data">
                            <button class="btn btn-danger w-100" disabled>Pengajuan Sudah Ditolak</button>
                        </div>
                    </div>
                    @else 
                    <div class="col-md-12">
                        <div class="detail-project-data me-2">
                            <button class="btn btn-success w-100" disabled>Sudah Disetujui Oleh {{$namaaprroval->nama}}</button>
                        </div>
                    </div>
                    @endif
                    @if ($datastatus === 'Ditinjau' )
                    <div class="col-md-6">
                        <div class="detail-project-data">
                            <a href="#" class="btn btn-danger w-100" onclick="event.preventDefault(); document.getElementById('tolak-usulan-form-{{ $project }}').submit();">Tolak Pengajuan</a>
                        </div>
                        <form id="tolak-usulan-form-{{ $project }}" action="{{ route('reject.requestschedule', ['project' => $project, 'periode' => $periode]) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @elseif($datastatus === 'Rejected' )
                    <div class="col-md-6 d-none">
                        <div class="detail-project-data">
                            <button class="btn btn-danger w-100" disabled>Pengajuan Ditolak Oleh {{$namaaprroval->nama}}</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">List Anggota</h5>
            </div>
            <div class="accordion" id="accordionExample">
                    @foreach ($schedules  as $schedule)
                    @php 
                        $projectname = \App\ModelCG\Project::find($schedule->project)->name;
                        $employee = \App\Employee::where('nik', $schedule->employee)->first();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Data{{$schedule->id}}" aria-expanded="false" aria-controls="Data{{$schedule->id}}">
                                    @if($employee && $employee->nama)
                                        {{ $employee->nama }}
                                    @else
                                        Tidak ada
                                    @endif
                            </button>
                        </h2>
                        <div id="Data{{$schedule->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="card custom-card2 mb-2 desktop">
                                    <div class="card-body">
                                        <div class="detail-wrap d-flex justify-content-between">
                                            <div class="detail-project-data">
                                                <h6 class="mb-1">Total Schedule</h6>
                                                <p class="text-muted">{{$totalshift}}</p>
                                            </div>
                                            <div class="detail-project-data">
                                                <h6 class="mb-1">Total Off</h6>
                                                <p class="text-muted">{{$libur}}</p>
                                            </div>
                                            <div class="detail-project-data">
                                                <h6 class="mb-1">Total Shift Pagi</h6>
                                                <p class="text-muted">{{$pagi}}</p>
                                            </div>
                                            <div class="detail-project-data">
                                                <h6 class="mb-1">Total Shift Middle</h6>
                                                <p class="text-muted">{{$middle}}</p>
                                            </div>
                                            <div class="detail-project-data">
                                                <h6 class="mb-1">Total Shift Malam</h6>
                                                <p class="text-muted">{{$malam}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card custom-card2 mb-2 mobile">
                                    <div class="card-body">
                                        <div class="detail-wrap">
                                            <div class="detail-project-data d-flex justify-content-between mb-2 ">
                                                <h6 class="mb-1">Total Schedule</h6>
                                                <p class="text-muted">{{$totalshift}} Hari</p>
                                            </div>
                                            <div class="detail-project-data d-flex justify-content-between mb-2">
                                                <h6 class="mb-1">Total Off</h6>
                                                <p class="text-muted">{{$libur}} Hari</p>
                                            </div>
                                            <div class="detail-project-data d-flex justify-content-between mb-2">
                                                <h6 class="mb-1">Total Shift Pagi</h6>
                                                <p class="text-muted">{{$pagi}} Hari</p>
                                            </div>
                                            <div class="detail-project-data d-flex justify-content-between mb-2">
                                                <h6 class="mb-1">Total Shift Middle</h6>
                                                <p class="text-muted">{{$middle}} Hari</p>
                                            </div>
                                            <div class="detail-project-data d-flex justify-content-between mb-2">
                                                <h6 class="mb-1">Total Shift Malam</h6>
                                                <p class="text-muted">{{$malam}} Hari</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Shift</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schedulekaryawan as $karyawanSchedule)
                                        @php
                                            $scheduleData = json_decode($karyawanSchedule->tanggal);
                                            $shiftData = json_decode($karyawanSchedule->shift);
                                        @endphp

                                        @for ($i = 0; $i < count($scheduleData); $i++)
                                            <tr>
                                                <td>{{ $scheduleData[$i]->tanggal }}</td>
                                                <td>{{ $shiftData[$i]->shift }}</td>
                                            </tr>
                                        @endfor
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('schedule.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Schedule Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Schedule Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Shift Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
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
@endpush