@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.min.css" rel="stylesheet"/>
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Additional Information</a></li>
    <li class="breadcrumb-item active" aria-current="page">Absensi</li>
  </ol>
</nav>

@php 
    $user = Auth::user(); 
    $employee = \App\Employee::where('nik', $user->employee_code)->first(); 
@endphp

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

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Filter</h5>
            </div>
            <div class="card-body">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organisasi" class="form-label">Organisasi</label>
                            <select name="organisasi" id="organisasi" class="form-control select2">
                                <option value="ALL">ALL</option>
                                @foreach($organisasi as $dataorg)
                                    @if($user->project_id == null || $dataorg->name == "FRONTLINE OFFICER")
                                        <option value="{{ $dataorg->id }}">{{ $dataorg->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @if(optional($employee)->unit_bisnis === 'Kas')
                        <div class="col-md-4">
                            <label for="project" class="form-label">Project</label>
                            <select name="project" id="project" class="form-control select2">
                                <option value="ALL">ALL</option>
                                @foreach($project as $dataproject)
                                    @if($user->project_id == null || $user->project_id == $dataproject->id)
                                        <option value="{{ $dataproject->id }}">{{ $dataproject->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <label for="periode" class="form-label">Periode</label>
                            <select id="periode" class="form-control">
                                <option value="">Select Periode</option>
                                @foreach($months as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h6 class="">Employees Attendance 
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#export-attendance" class="btn btn-success btn-xs" style="float:right">Export</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample1" class="table table-striped table-bordered">
                        <thead id="table-header">
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>PROJECT NAME</th>
                                <th>Total Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable akan secara otomatis mengisi data di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="export-attendance" tabindex="-1" aria-labelledby="exportPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" method="GET">
                    <div class="form-group">
                        <label for="month" class="form-label">Absen Periode</label>
                        <select name="month" id="month" class="form-control" required>
                            @foreach(['nov-2024' => 'November - 2024', 'dec-2024' => 'Desember - 2024', 
                                      'jan-2025' => 'Januari - 2025', 'feb-2025' => 'Februari - 2025'] as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-scripts')
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script>
$(document).ready(function () {
    function fetchData() {
        let organisasi = $('#organisasi').val();
        let project = $('#project').val();
        let periode = $('#periode').val();

        // Pastikan periode valid sebelum melakukan request
        let periodeSplit = periode ? periode.split(' - ') : ['', ''];
        let start = periodeSplit[0] || '';
        let end = periodeSplit[1] || '';

        $.ajax({
            url: '/api/v1/attendance-records',
            type: 'GET', // Gunakan GET agar bisa digunakan untuk export juga
            data: {
                organisasi: organisasi,
                project_id: project,
                start: start,
                end: end
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data.length > 0) {
                    updateTable(response.data);
                } else {
                    Swal.fire('Info', 'No data available', 'info');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to fetch data', 'error');
            }
        });
    }

    function updateTable(employees) {
        let dates = Object.keys(employees[0]?.schedules || {});

        // Generate table headers
        let headerHTML = `<tr>
            <th>Employee Name</th>
            <th>Employee NIK</th>
            <th>Employee Project</th>
            <th>Total Schedules</th>
            ${dates.map(date => `<th>${date}</th>`).join('')}
        </tr>`;
        $("#table-header").html(headerHTML);

        // Generate table rows
        let rowsHTML = employees.map(emp => {
            return `<tr>
                <td>${emp.employee_name}</td>
                <td>${emp.employee}</td>
                <td>${emp.project_name}</td>
                <td>${emp.total_schedules}</td>
                ${dates.map(date => `<td>${emp.schedules[date]?.shift || '-'} ( ${emp.schedules[date]?.clock_in || '-'} : ${emp.schedules[date]?.clock_out || '-'} )</td>`).join('')}
            </tr>`;
        }).join('');
        
        $("#dataTableExample1 tbody").html(rowsHTML);

        // Inisialisasi ulang DataTable
        if ($.fn.DataTable.isDataTable("#dataTableExample1")) {
            $("#dataTableExample1").DataTable().clear().destroy();
        }
        $("#dataTableExample1").DataTable();
    }

    // Panggil fetchData saat halaman dimuat
    fetchData();

    // Reload data saat filter diubah
    $('#organisasi, #project, #periode').on('change', function () {
        fetchData();
    });

    // Tombol export
    $('#exportButton').on('click', function () {
        let organisasi = $('#organisasi').val();
        let project = $('#project').val();
        let periode = $('#periode').val();
        let periodeSplit = periode ? periode.split(' - ') : ['', ''];
        let start = periodeSplit[0] || '';
        let end = periodeSplit[1] || '';

        let url = `/api/v1/attendance-records/export?organisasi=${organisasi}&project_id=${project}&start=${start}&end=${end}`;
        window.location.href = url;
    });
});
</script>

@endpush
