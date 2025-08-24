@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate-css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-fixedcolumns/css/fixedColumns.dataTables.min.css') }}" rel="stylesheet" />
    <style>
        .table-responsive {
            position: relative;
            overflow-x: auto;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th:nth-child(1),
        .table tbody td:nth-child(1) {
            position: sticky;
            left: 0;
            background-color: #fff;
            z-index: 1; /* Ensure the first column is above others */
            width: 150px; /* Explicit width for proper alignment */
        }
    </style>
    <style>
    .timeline {
        position: relative;
        max-width: 100%;
        margin: 0 auto;
    }
    .timeline::after {
        content: '';
        position: absolute;
        width: 4px;
        background-color: #FF9F55;
        top: 0;
        bottom: 0;
        left: 50%;
        margin-left: -2px;
    }
    .container {
        padding: 15px 40px;
        position: relative;
        background-color: inherit;
        width: 50%;
    }
    .container::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        right: -10px;
        background-color: #FF9F55;
        border: 3px solid white;
        top: 20px;
        border-radius: 50%;
        z-index: 1;
    }
    .left {
        left: 0;
    }
    .right {
        left: 50%;
    }
    .left::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 25px;
        width: 0;
        z-index: 1;
        right: 30px;
        border: medium solid #FF9F55;
        border-width: 10px 0 10px 10px;
        border-color: transparent transparent transparent #FF9F55;
    }
    .right::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 25px;
        width: 0;
        z-index: 1;
        left: 30px;
        border: medium solid #FF9F55;
        border-width: 10px 10px 10px 0;
        border-color: transparent #FF9F55 transparent transparent;
    }
    .right::after {
        left: -10px;
    }
    .content {
        padding: 20px;
        background-color: #474e5d;
        color: white;
        position: relative;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        text-align: left;
    }
    .content h5 {
        margin: 0 0 10px;
        color: #FF9F55;
        font-size: 1.2rem;
    }
    .content img {
        margin-top: 10px;
        width: 100%;
        max-width: 250px;
        height: auto;
        border-radius: 8px;
    }
    @media screen and (max-width: 600px) {
        .timeline::after {
            left: 31px;
        }
        .container {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }
        .container::before {
            left: 60px;
            border: medium solid #FF9F55;
            border-width: 10px 10px 10px 0;
            border-color: transparent #FF9F55 transparent transparent;
        }
        .left::after, .right::after {
            left: 15px;
        }
        .right {
            left: 0%;
        }
    }
</style>
@endpush

@section('content')
    <form method="GET" class="card p-4">
        <div class="row mb-3 align-items-end">
            <div class="col-md-6">
                <select class="form-control select2" id="project_id" name="project_id">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control select2" id="month_year" name="month_year">
                    <option value="">-- Select Month --</option>
                    @foreach(bulan() as $key => $value)
                        <option value="{{ $value }}-2025" {{ request('month_year') == "$value-2025" ? 'selected' : '' }}>
                            {{ $value }}-2025
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    @if(!empty($result['data']))
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Project</th>
                                @foreach($result['dates'] as $date)
                                    <th>{{ $date }}</th>
                                @endforeach
                                <th>Total PG</th>
                                <th>Total MD</th>
                                <th>Total ML</th>
                                <th>Total Libur</th>
                                <th>Total Tidak Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['data'] as $index => $row)
                                @php 
                                    $jumlahBackup = \DB::table('schedule_backups')
                                                    ->whereIn('tanggal', $result['dates'])
                                                    ->where('employee', $row['nik'])
                                                    ->count();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $row['nama'] }}<br>
                                        <button class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#backupModal-{{ $index }}">
                                            Check Backup ({{$jumlahBackup}})
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary mt-1">
                                            Check Pengajuan
                                        </button>
                                    </td>
                                    <td>{{ $row['project'] }}</td>
                                    @foreach($result['dates'] as $date)
                                        @php
                                            $shift = $row['schedule'][$date];
                                            $absenExists = \DB::table('absens')->where('tanggal', $date)->where('nik', $row['nik'])->exists();
                                            $backupCount = \DB::table('schedule_backups')
                                                ->where('man_backup', $row['nik'])
                                                ->where('project', $row['project'])
                                                ->where('tanggal', $date)
                                                ->count();
                                        @endphp
                                        @if($absenExists)
                                            @php
                                                $attendanceDetails = \DB::table('absens')
                                                    ->where('tanggal', $date)
                                                    ->where('nik', $row['nik'])
                                                    ->select('clock_in', 'clock_out', 'photo','status')
                                                    ->first();
                                            @endphp
                                            <td style="background-color: #d4edda; text-align: center; vertical-align: middle;">
                                                <p><small>Shift:</small> {{ $shift }}</p>
                                                <p><small>Clock In:</small> {{ $attendanceDetails->clock_in }}</p>
                                                <p><small>Clock Out:</small> {{ $attendanceDetails->clock_out }}</p>
                                                <p><small>Photo:</small></p>
                                                <img src="https://hris.truest.co.id/storage/app/public/images/absen/{{ $attendanceDetails->photo }}" alt="Photo" style="max-width: 100%; height: auto;">
                                                <p><small>Status:</small> {{ $attendanceDetails->status }}</p>
                                            </td>
                                        @elseif($backupCount > 0)
                                            @php
                                                $backupDetails = \DB::table('schedule_backups')
                                                    ->join('absen_backup', 'absen_backup.nik', '=', 'schedule_backups.employee')
                                                    ->join('karyawan', 'karyawan.nik', '=', 'absen_backup.nik')
                                                    ->where('schedule_backups.man_backup', $row['nik'])
                                                    ->where('schedule_backups.project', $row['project'])
                                                    ->where('schedule_backups.tanggal', $date)
                                                    ->select('karyawan.nama', 'absen_backup.clock_in', 'absen_backup.clock_out', 'absen_backup.photo')
                                                    ->first();
                                            @endphp
                                            @if($backupDetails)
                                                <td style="background-color: blue; color: white; text-align: center; cursor: pointer; text-align: center; vertical-align: middle;">
                                                    <p><small>Nama:</small> {{ $backupDetails->nama }}</p>
                                                    <p><small>Clock In:</small> {{ $backupDetails->clock_in }}</p>
                                                    <p><small>Clock Out:</small> {{ $backupDetails->clock_out }}</p>
                                                    <p><small>Photo:</small></p>
                                                    <img src="https://hris.truest.co.id/storage/app/public/images/absen/{{ $backupDetails->photo }}" alt="Photo" style="max-width: 100%; height: auto;">
                                                    
                                                </td>
                                            @else
                                                <td style="background-color: yellow; text-align: center; cursor: pointer;" title="Butuh Perbaikan Absen" onclick="alert('Butuh Perbaikan Absen')">
                                                    {{ $shift }}
                                                </td>
                                            @endif
                                        @else
                                            <td style="background-color: {{ $shift === 'OFF' ? '#ffcccc' : 'yellow' }}; text-align: center; cursor: pointer; text-align: center; vertical-align: middle;" title="{{ $shift === 'OFF' ? 'Hari Libur' : 'Butuh Perbaikan Absen' }}" onclick="alert('{{ $shift === 'OFF' ? 'Hari Libur' : 'Butuh Perbaikan Absen' }}')">
                                                {{ $shift }}
                                            </td>
                                        @endif
                                    @endforeach
                                    <td>{{ $row['totals']['PG'] ?? 0 }}</td>
                                    <td>{{ $row['totals']['MD'] ?? 0 }}</td>
                                    <td>{{ $row['totals']['ML'] ?? 0 }}</td>
                                    <td>{{ $row['totals']['OFF'] ?? 0 }}</td>
                                    <td>{{ $row['totals']['Tidak Masuk'] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p>No data available for the selected filters.</p>
    @endif

    @foreach($result['data'] as $index => $row)
        @php
            $backupDetails = \DB::table('schedule_backups')
                ->join('absen_backup', 'absen_backup.nik', '=', 'schedule_backups.employee')
                ->where('absen_backup.nik', $row['nik'])
                ->whereIn('schedule_backups.tanggal', $result['dates'])
                ->select('absen_backup.nik as employee',
                         'absen_backup.clock_in', 
                         'absen_backup.clock_out', 
                         'absen_backup.photo',
                         'schedule_backups.tanggal',
                         'schedule_backups.man_backup',
                         'schedule_backups.project'
                         )
                ->get();
        @endphp
        <!-- Modal for Check Backup -->
        <div class="modal fade bd-example-modal-xl" id="backupModal-{{ $index }}" tabindex="-1" aria-labelledby="backupModalLabel-{{ $index }}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="backupModalLabel-{{ $index }}">Backup Details for {{ $row['nama'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($backupDetails->isEmpty())
                            <p>No backup details available.</p>
                        @else
                            <div class="timeline">
                                @foreach($backupDetails as $index => $detail)
                                    <div class="container {{ $index % 2 == 0 ? 'left' : 'right' }}">
                                        <div class="content">
                                            <h5>{{ karyawan_bynik($detail->employee)->nama }}</h5>
                                            <p><strong>Clock In:</strong> {{ $detail->clock_in }}</p>
                                            <p><strong>Clock Out:</strong> {{ $detail->clock_out }}</p>
                                            <p><strong>Tanggal Backup:</strong> {{ $detail->tanggal }}</p>
                                            <p><strong>Project Backup:</strong> {{ project_byID($detail->project)->name }}</p>
                                            <p><strong>Backup Menggantikan:</strong> {{ karyawan_bynik($detail->man_backup)->nama }}</p>
                                            <img src="https://hris.truest.co.id/storage/app/public/images/absen/{{ $detail->photo }}" alt="Photo" style="width: 100%; max-width: 200px; height: auto; margin-top: 10px;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartjs/chart.umd.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/carousel.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/chartjs.js') }}"></script>
<style>
    .fixed-column {
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);  /* Shadow pada kolom fixed */
        background-color: #f8f9fa; /* Beri latar belakang agar lebih terlihat */
        z-index: 10; /* Pastikan kolom fixed tampil di atas kolom lainnya */
        border-right: 2px solid #dee2e6; /* Border di sebelah kanan kolom */
    }
</style>
<script>
    $(document).ready(function() {
        var table = $('.table').DataTable({
            scrollX: true,  // Aktifkan scroll horizontal
            fixedColumns: {
                left: 2  // Pinned kolom pertama
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Select --",
            allowClear: true
        });
        feather.replace(); // Initialize Feather icons
    });
</script>
@endpush
