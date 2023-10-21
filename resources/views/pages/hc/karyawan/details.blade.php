@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php 
    $dataLogin = json_decode(Auth::user()->permission);
@endphp 
<div class="title mb-3">
    <h4>Employee Details</h4>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Attendence</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-line-tab" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Emergency Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="document-line-tab" data-bs-toggle="tab" data-bs-target="#document" role="tab" aria-controls="document" aria-selected="false">Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-line-tab" data-bs-toggle="tab" data-bs-target="#Payslip" role="tab" aria-controls="Payslip" aria-selected="false">Payslip</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="lineTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                        <!-- Personal Info -->
                        <div class="row">
                            <div class="col-md-3 mt-4">
                                <div class="profile-photo">
                                    <h5>Profile Image</h5>
                                    <div class="image-wrap mt-4">
                                        <img src="{{ asset('images/' . $employee->gambar) }}" alt="{{$employee->nama}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 mt-4">
                                <div class="personal-info-wrap">
                                    <h5>Personal Info</h5>
                                    <div class="item-details-wrap">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Full Name</p>
                                                    <h5>{{$employee->nama}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Email Address</p>
                                                    <h5>{{$employee->email}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Phone Number</p>
                                                    <h5>0{{$employee->telepon}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Employee Code</p>
                                                    <h5>{{$employee->nik}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Position</p>
                                                    <h5>{{$employee->jabatan}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Contact Status</p>
                                                    <h5>{{$employee->status_kontrak}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                        use Carbon\Carbon;
                                            $joinDate = \Carbon\Carbon::parse($employee->joindate);
                                            $now = \Carbon\Carbon::now();
                                            $masaKerja = $joinDate->diff($now)->format('%y tahun, %m bulan, %d hari');
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>Join Date</p>
                                                    <h5>{{$employee->joindate}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item-details mt-4">
                                                    <p>length of working</p>
                                                    <h5>{{$masaKerja}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">
                        <!-- Attendence Info -->
                        <h5 class="mt-4">Attendece Record {{ \Carbon\Carbon::now()->format('F Y') }}</h5>
                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Present</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="data-present d-flex justify-content-between">
                                            <div class="data mb-3">
                                                <h5>{{$daysWithAttendance}}</h5>
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
                            <div class="col-md-8 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Not Present</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="data-present d-flex justify-content-between">
                                            <div class="data mb-3">
                                                <h5>{{$daysWithoutAttendance}}</h5>
                                                <p>Absent</p>
                                            </div>
                                            <div class="data mb-3">
                                                <h5>{{$daysWithClockInNoClockOut}}</h5>
                                                <p>No Clock Out</p>
                                            </div>
                                            <div class="data mb-3">
                                                <h5>{{ $CountRequest }}</h5>
                                                <p>Attendence Request</p>
                                            </div>
                                            <div class="data mb-3">
                                                <h5>{{ $izin }}</h5>
                                                <p>Leave</p>
                                            </div>
                                            <div class="data mb-3">
                                                <h5>{{$sakit}}</h5>
                                                <p>Sick</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Table Attendece -->
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Filter Periode</label>
                            <select id="monthSelect" class="form-control">
                                <option Disabled selected> Pilih Periode </option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">July</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table id="attendanceTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Clock In</th>
                                                <th>Clock Out</th>
                                                <th>Attendence Code</th>
                                                @if(in_array('superadmin_access', $dataLogin) || in_array('hc_access', $dataLogin))
                                                <th></th>
                                                @endif
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
                                            foreach ($attendanceData as $absendata) {
                                                $attendanceDataByDate[$absendata->tanggal] = $absendata;
                                            }
                                            @endphp

                                            @while ($currentDate->lte($end_date))
                                            <tr>
                                            <td class="{{ $currentDate->isWeekend() ? 'text-danger' : '' }}">{{ $currentDate->translatedFormat('D, j M Y') }}</td>
                                                @if (isset($attendanceDataByDate[$currentDate->format('Y-m-d')]))
                                                    <td class="text-success">{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_in }}</td>
                                                    <td class="text-danger">{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_out }}</td>
                                                    <td>{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->status }}</td>
                                                    @if(in_array('superadmin_access', $dataLogin) || in_array('hc_access', $dataLogin))
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-warning"
                                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                                            data-date="{{ $currentDate->format('Y-m-d') }}"
                                                            data-clock-in="{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_in }}"
                                                            data-clock-out="{{ $attendanceDataByDate[$currentDate->format('Y-m-d')]->clock_out }}"
                                                        >Edit</a>
                                                    </td>
                                                    @endif
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    @if(in_array('superadmin_access', $dataLogin) || in_array('hc_access', $dataLogin))
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-warning"
                                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                                            data-date="{{ $currentDate->format('Y-m-d') }}"
                                                        >Edit</a>
                                                    </td>
                                                    @endif
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
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Request Attendence History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="item-wrap">
                                            @foreach($requestAbsen as $history)
                                            <div class="item mb-3">
                                                <div class="no mb-2">{{ \Carbon\Carbon::parse($history->tanggal)->locale('id_ID')->isoFormat('dddd, D MMMM YYYY') }}</div>
                                                <div class="status mb-2">
                                                    {{ $history->status }}
                                                </div>
                                                <div class="status-approved mb-2">
                                                    <span class="badge 
                                                        @if($history->aprrove_status === 'Pending') 
                                                            bg-warning 
                                                        @elseif($history->aprrove_status === 'Reject') 
                                                            bg-danger 
                                                        @elseif($history->aprrove_status === 'Approved') 
                                                            bg-success 
                                                        @endif">
                                                        {{ $history->aprrove_status }}
                                                    </span>
                                                </div>
                                            </div>
                                            <hr>
                                            @endforeach
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
                                        <!-- Formulir untuk edit atau tambah data -->
                                        <form id="editForm" method="POST" >
                                            @csrf
                                            <input type="hidden" id="editDate" name="tanggal" value="">
                                            <input type="hidden" name="user" value="{{$employee->nik}}">
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
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-line-tab">
                        <!-- Emergency Contact Info -->
                    </div>
                    <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-line-tab">
                        <!-- Document Info -->
                    </div>
                    <div class="tab-pane fade" id="Payslip" role="tabpanel" aria-labelledby="Payslip-line-tab">
                        <!-- Payslip Info -->
                    </div>
                </div>
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
<style>
    .nav.nav-tabs.nav-tabs-vertical .nav-link{
        border : 0;
        background-color : transparent;
        padding: 10px 15px;
        margin-bottom: 5px;
    }

    .nav.nav-tabs.nav-tabs-vertical .nav-link.active {
        background-color: #6571ff;
        color: #fff;
        border-radius: 50px;
        font-weight: 500;
    }

    .nav.nav-tabs.nav-tabs-vertical {
        width : 50%;
    }

    .image-wrap {
        background: #c0c0c0;
        border-radius: 10px;
        height : 300px;
    }

    .image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-details {
        padding: 10px 25px;
        background-color: #eee;
        border-radius: 10px;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .item-details p {
        font-size: 10px;
        font-weight: 400;
        margin-bottom: 0px;
        color: #555;
    }
    /* Responsive */
    @media(max-width: 675px){
        .data-present.d-flex.justify-content-between {
            display : block!important;
        }
    }
    
</style>
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
    document.getElementById('monthSelect').addEventListener('change', function() {
    var selectedMonth = this.value; // Dapatkan bulan yang dipilih
    var selectedYear = new Date().getFullYear(); // Dapatkan tahun saat ini

    // Lakukan permintaan ke server untuk mendapatkan data berdasarkan bulan yang dipilih
    var requestUrl = '{{ route('absen.getDataDetails') }}' + '?month=' + selectedMonth + '&year=' + selectedYear;

    fetch(requestUrl)
        .then(response => response.json())
        .then(data => {
            // Panggil fungsi untuk memperbarui tabel dengan data yang diterima
            updateTable(data);
        })
        .catch(error => {
            console.error('Gagal memuat data: ' + error);
        });
});

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
$(document).ready(function() {
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var date = button.data('date');
        var clockIn = button.data('clock-in');
        var clockOut = button.data('clock-out');
        var modal = $(this);

        // Mengisi nilai tanggal pada formulir
        modal.find('#editDate').val(date);
        modal.find('#clockIn').val(clockIn);
        modal.find('#clockOut').val(clockOut);

        // Set the action URL based on whether data exists for the date
        var actionUrl = "";

        if (clockIn) {
            // Data sudah ada, atur URL edit
            actionUrl = "{{ route('attendance.editData', ':date') }}";
        } else {
            // Data belum ada, atur URL create
            actionUrl = "{{ route('attendance.createData') }}";
        }

        actionUrl = actionUrl.replace(':date', date);

        // Mengganti aksi formulir
        modal.find('form').attr('action', actionUrl);
    });
});

</script>
@endpush