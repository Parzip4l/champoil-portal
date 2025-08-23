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
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $row['nama'] }}<br>
                                        <button class="btn btn-sm btn-outline-secondary mt-1" >
                                            Check Backup
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary mt-1" >
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
                left: 1  // Pinned kolom pertama
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