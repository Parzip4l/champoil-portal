@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@php 
    $user = Auth::user(); 
    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first(); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
@endphp

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

<div class="row mb-3">
    <form method="GET" action="" class="d-flex">
        <div class="col-md-6">
            <label for="daterange_picker" class="form-label">Filter by Date Range</label>
            <input type="text" id="daterange_picker" name="daterange" class="form-control" placeholder="Select date range" value="{{ request('daterange') }}" />
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-danger text-white">Dibawah 50 %</div>
            <div class="card-body text-center" style="font-size:50px;">{{ round(($percent[0] / count($project)) * 100, 2) }} %</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-warning text-dark">Absensi 50 s/d 80 %</div>
            <div class="card-body text-center" style="font-size:50px;">{{ round(($percent[1] / count($project)) * 100, 2) }} %</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-info text-white">Absensi 80 s/d 99 %</div>
            <div class="card-body text-center" style="font-size:50px;">{{ round(($percent[2] / count($project)) * 100, 2) }} %</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-success text-white">Absensi 100 %</div>
            <div class="card-body text-center" style="font-size:50px;">{{ round(($percent[3] / count($project)) * 100, 2) }} %</div>
        </div>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2 shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Report Absensi</h6>
                <a href="{{ route('rekap-report') }}" class="btn btn-sm btn-outline-light">Rekap</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table table-striped table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Total Schedule</th>
                            <th>Total Absensi</th>
                            <th>Persentase</th>
                            <th>Tanpa Clockout</th>
                            <th>Persentase</th>
                            <th>Total Schedule Backup</th>
                            <th>Total Absensi</th>
                            <th>Persentase</th>
                            <th>Leader PIC</th>
                            <th>Need Approval</th>
                            <th>Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($project)
                            @php 
                                $no=1;
                                $total_schedule=0;
                                $total_absen=0;
                                $schedule_backup=0;
                                $absen_backup=0;
                                $percent_absen=0;
                                $percent_backup=0;
                                $total_tidak_clockout= 0;
                                $persentase_tidak_clockout = 0;
                                $color="";
                            @endphp
                            @foreach($project as $row)
                                @php 
                                    if($row->persentase_absen <= 50 ){
                                        $color="table-danger";
                                    }else if($row->persentase_absen >50 && $row->persentase_absen <= 80 ){
                                        $color="table-warning";
                                    }else if($row->persentase_absen >80 && $row->persentase_absen < 100){
                                        $color="table-info";
                                    }else{
                                        $color="table-success";
                                    }
                                @endphp
                                <tr class="{{ $color }}">
                                    <td>{{ $no }}</td>
                                    <td>
                                        <a href="{{ route('report-detail', [
                                            'id' => $row->id,
                                            'periode' => isset($_GET['periode']) && strtotime($_GET['periode']) ? date('M Y', strtotime($_GET['periode'])) : date('m-Y')
                                        ]) }}" class="text-decoration-none text-dark">
                                            {{ $row->name }} <br/>
                                            <small class="text-muted">DEPLOYMENT DATE : {{ $row->tanggal_deploy ?? '-' }}</small>
                                        </a>
                                    </td>
                                    <td>{{ $row->schedule }}</td>
                                    <td>{{ $row->absen }}</td>
                                    <td>{{ $row->persentase_absen }} %</td>
                                    <td>{{ $row->tanpa_clockout }}</td>
                                    <td>{{ $row->persentase_tanpa_clockout }} %</td>
                                    <td>{{ $row->schedule_backup }}</td>
                                    <td>{{ $row->absen_backup }}</td>
                                    <td>{{ $row->persentase_backup }} %</td>
                                    <td>{{ @karyawan_bynik($row->leader_pic)->nama }}</td>
                                    <td>{{ $row->need_approval }}</td>
                                    <td>{{ $row->approved }}</td>
                                </tr>
                                @php
                                    $total_schedule +=$row->schedule;
                                    $total_absen +=$row->absen;
                                    $schedule_backup +=$row->schedule_backup;
                                    $absen_backup +=$row->absen_backup;
                                    $total_tidak_clockout +=$row->tanpa_clockout;
                                    $no++;
                                @endphp
                            @endforeach
                            @php 
                                if($total_absen > 0 && $total_schedule> 0){
                                    $percent_absen = round(($total_absen / $total_schedule) * 100,2);
                                }
                                if($total_absen > 0 && $total_tidak_clockout> 0){
                                    $persentase_tidak_clockout = round(($total_tidak_clockout / $total_absen) * 100, 2);
                                }
                                if($absen_backup > 0 && $schedule_backup> 0){
                                    $percent_backup = round(($absen_backup / $schedule_backup) * 100,2);
                                }
                            @endphp
                            <tr class="table-secondary">
                                <td colspan=2><strong>Total</strong></td>
                                <td>{{ $total_schedule }}</td>
                                <td>{{ $total_absen }}</td>
                                <td>{{ $percent_absen }} %</td>
                                <td>{{ $total_tidak_clockout }}</td>
                                <td>{{ $persentase_tidak_clockout }} %</td>
                                <td>{{ $schedule_backup }}</td>
                                <td>{{ $absen_backup }}</td>
                                <td>{{ $percent_backup }} %</td>
                                <td colspan=3></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exportPayrollModal" tabindex="-1" aria-labelledby="exportPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exportPayrollModalLabel">Export Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="GET">
                    <div class="form-group">
                        <label for="month" class="form-label">Absen Periode</label>
                        <select name="month" id="month" class="form-control" required>
                            <option value="nov-2024">November - 2024</option>
                            <option value="dec-2024">Desember - 2024</option>
                            <option value="jan-2025">Januari - 2025</option>
                            <option value="feb-2025">Februari - 2025</option>
                            <option value="mar-2025">Maret - 2025</option>
                            <option value="apr-2025">April - 2025</option>
                            <option value="may-2025">Mei - 2025</option>
                            <option value="jun-2025">Juni - 2025</option>
                            <option value="jul-2025">Juli - 2025</option>
                            <option value="aug-2025">Agustus - 2025</option>
                            <option value="sep-2025">September - 2025</option>
                            <option value="oct-2025">Oktober - 2025</option>
                            <option value="nov-2025">November - 2025</option>
                            <option value="dec-2025">Desember - 2025</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="exportButton">Export</button>
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
    
  <script>
   
    function formatDateToYMD(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }

    var urlParams = new URLSearchParams(window.location.search);
    var dateRangeParam = urlParams.get('periode');
    let startDate=new Date();
    let endDate=new Date();

    if(dateRangeParam){
        var dateRangeArray = dateRangeParam.split('to');
    
        // Now dateRangeArray contains the start and end date values
        startDate = dateRangeArray[0]; // Assuming dates are separated by 'to'
        endDate = dateRangeArray[1];
    }
    
    flatpickr('#daterange_picker', {
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: [startDate,endDate]
        
    });
    // Button click event
    document.getElementById('search').addEventListener('click', function () {
        // Add your button click logic here
        var periode = $('#periode').val();
        var tanggal = $('#daterange_picker').val();
        window.location.href = '?periode=' +periode+'&tanggal='+tanggal ;
    });
    
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
    document.addEventListener('DOMContentLoaded', function () {
        const dateRangePicker = document.getElementById('daterange_picker');

        if (dateRangePicker) {
            flatpickr(dateRangePicker, {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('daterange') }}".split(' to '),
                onClose: function(selectedDates, dateStr, instance) {
                    console.log(dateStr); // Date range in 'Y-m-d to Y-m-d' format
                }
            });
        }
    });

    document.getElementById('exportButton').addEventListener('click', function () {
    const month = document.getElementById('month').value;
    const employee = "{{ $employee->ktp }}";
    
    Swal.fire({
        title: 'Processing Export',
        text: 'Please wait while the export is being generated...',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post("/api/v1/export-absen", {
        month: month,
        employee: employee
    })
    .then(function (response) {
        Swal.close();
        Swal.fire({
            title: 'Export Success',
            text: 'Your absen data export is ready!',
            icon: 'success',
        }).then(() => {
            const url = response.data.url;  // URL returned from the server
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'absen.xlsx');  // Adjust file name if needed
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    })
    .catch(function (error) {
        Swal.close();
        Swal.fire({
            title: 'Export Failed',
            text: 'An error occurred during export.',
            icon: 'error',
        });
    });
});

</script>
@endpush