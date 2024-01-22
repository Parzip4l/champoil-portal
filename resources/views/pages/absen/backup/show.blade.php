@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@php
    use Carbon\Carbon;
    $currentDate = Carbon::now();
    $attendanceDataByDate = [];
    foreach ($absensi as $absendata) {
        $attendanceDataByDate[$absendata->tanggal] = $absendata;
    }
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="arrow-back mb-3">
            <a href="{{url('absen')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="content-data d-flex justify-content-between">
                    <div class="employee-name">
                        <h5>{{$namaKaryawan->nama}}</h5>
                    </div>
                    <div class="range-periode">
                        <h5>{{$startDate->format('d F Y')}} - {{$endDate->format('d F Y')}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Detail Count -->
<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Backup Logs</h5>
            </div>
            <div class="card-body">
                <div class="data-present d-flex justify-content-between">
                    <div class="data mb-3">
                        <h5>{{$ontime}}</h5>
                        <p>On Time</p>
                    </div>
                    <div class="data mb-3">
                        <h5>0</h5>
                        <p>Late Clock In</p>
                    </div>
                    <div class="data mb-3">
                        <h5>0</h5>
                        <p>Early Clock Out</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="attendanceTable" class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Project</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Attendence Code</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // Get the current month and year
                            $currentDate = Carbon::now();
                            $currentMonth = $currentDate->month;
                            $currentYear = $currentDate->year;

                            $today = now();
                            $start_date = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
                            $end_date = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                            // Set the start date to 21st of the previous month
                            $startDate = Carbon::create($currentYear, $currentMonth, 21, 0, 0, 0)->subMonth();

                            // Set the end date to 20th of the current month
                            $endDate = Carbon::create($currentYear, $currentMonth, 20, 0, 0, 0);

                            $currentDate = $start_date->copy();

                            // Create an array to store attendance data for each date
                            $attendanceDataByDate = [];
                            foreach ($absensi as $absendata) {
                                $attendanceDataByDate[$absendata->tanggal] = $absendata;
                            }
                            @endphp

                            @while ($currentDate->lte($end_date))
                            <tr>
                            <td class="{{ $currentDate->isWeekend() ? 'text-danger' : '' }}">{{ $currentDate->translatedFormat('D, j M Y') }}</td>
                            <td>
                                @if (isset($attendanceDataByDate[$currentDate->format('Y-m-d')]))
                                    @php
                                        $projectCode = $attendanceDataByDate[$currentDate->format('Y-m-d')]->project_backup;
                                        $projectName = \App\ModelCG\Project::where('id', $projectCode)->value('name');
                                    @endphp
                                    {{ $projectName ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                                @if (isset($attendanceDataByDate[$currentDate->format('Y-m-d')]))
                                    <td class="text-success">{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_in }}</td>
                                    <td class="text-danger">{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_out }}</td>
                                    <td>{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->status }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="#" class="btn btn-sm btn-warning dropdown-item"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-date="{{ $currentDate->format('Y-m-d') }}"
                                                    data-clock-in="{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_in }}"
                                                    data-clock-out="{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_out }}"
                                                    data-nik="{{$namaKaryawan->nik}}"
                                                ><i data-feather="edit" class="icon-sm me-2"></i>Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger dropdown-item"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-date="{{ $currentDate->format('Y-m-d') }}"
                                                    data-nik="{{$namaKaryawan->nik}}"
                                                ><i data-feather="trash" class="icon-sm me-2"></i>Hapus</a>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="#" class="btn btn-sm btn-warning dropdown-item"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-date="{{ $currentDate->format('Y-m-d') }}"
                                                ><i data-feather="edit" class="icon-sm me-2"></i>Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger dropdown-item"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-date="{{ $currentDate->format('Y-m-d') }}"
                                                    data-nik="{{$namaKaryawan->nik}}"
                                                ><i data-feather="trash" class="icon-sm me-2"></i>Hapus</a>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            @php
                            $currentDate->addDay();
                            @endphp
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit / Tambah Data</h5>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" >
                    @csrf
                    <input type="hidden" id="editDate" name="tanggal" value="">
                    <input type="hidden" name="user" value="{{$namaKaryawan->nik}}">
                    <div class="form-group mb-3">
                        <label for="clockIn">Clock In</label>
                        <input type="time" class="form-control" id="clockIn" name="clock_in" value="{{ isset($attendanceDataByDate[$currentDate->format('Y-m-d')]) ? $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_in : '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="clockOut">Clock Out</label>
                        <input type="time" class="form-control" id="clockOut" name="clock_out" value="{{ isset($attendanceDataByDate[$currentDate->format('Y-m-d')]) ? $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_out : '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" class="form-control" id="status" name="status" value="H">
                        <input type="hidden" class="form-control" id="latitude" name="latitude" value="-6.1366045">
                        <input type="hidden" class="form-control" id="longtitude" name="longtitude" value="106.7601449">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus absen untuk tanggal ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteAttendanceBtn" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
function updateTable(data) {
    var table = document.getElementById('attendanceTable');
    var tbody = table.querySelector('tbody');

    // Hapus semua baris yang ada dalam tbody
    tbody.innerHTML = '';

    // Loop melalui data yang diterima dan tambahkan baris-baris baru ke tbody
    data.forEach(item => {
        var row = document.createElement('tr');
        var columns = ['tanggal', 'clock_in', 'clock_out', 'status'];
        
        columns.forEach(column => {
            var cell = document.createElement('td');
            cell.textContent = item[column];
            row.appendChild(cell);
        });

        if (item['edit_button']) {
            var editCell = document.createElement('td');
            var editButton = document.createElement('a');
            editButton.href = '#';
            editButton.className = 'btn btn-sm btn-warning';
            editButton.setAttribute('data-bs-toggle', 'modal');
            editButton.setAttribute('data-bs-target', '#editModal');
            editButton.setAttribute('data-date', item['tanggal']);
            editButton.setAttribute('data-clock-in', item['clock_in']);
            editButton.setAttribute('data-clock-out', item['clock_out']);
            editButton.textContent = 'Edit';
            editCell.appendChild(editButton);
            row.appendChild(editCell);
        }

        tbody.appendChild(row);
    });
}
</script>
<script>
    function goBack() {
        window.history.back();
    }
    $(document).ready(function() {
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var date = button.data('date');
        var clockIn = button.data('clock-in');
        var clockOut = button.data('clock-out');
        var nik = button.data('nik');
        var modal = $(this);

        // Mengisi nilai tanggal pada formulir
        modal.find('#editDate').val(date);
        modal.find('#clockIn').val(clockIn);
        modal.find('#clockOut').val(clockOut);
        modal.find('#nik').val(nik);

        // Set the action URL based on whether data exists for the date
        var actionUrl = "";

        if (clockIn) {
            // Data sudah ada, atur URL edit
            actionUrl = "{{ route('attendance.editData', [':date', ':nik']) }}";
        } else {
            // Data belum ada, atur URL create
            actionUrl = "{{ route('attendance.createData') }}";
        }

        actionUrl = actionUrl.replace(':date', date);
        actionUrl = actionUrl.replace(':nik', nik);

        // Mengganti aksi formulir
        modal.find('form').attr('action', actionUrl);
    });
});
</script>
<!-- Hapus Data Absen -->
<script>
    $(document).ready(function () {
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var date = button.data('date');
                var nik = button.data('nik');
                var modal = $(this);
                modal.find('#deleteAttendanceBtn').attr('href', '/delete-attendance/' + date + '/' + nik);
            });
        });
</script>
<style>
    td {
        vertical-align : middle;
    }
</style>
@endpush