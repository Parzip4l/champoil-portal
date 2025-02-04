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
    $employeeDetails = \App\Employee::where('nik', $user->employee_code)->first(); 
    $employee = \App\Employee::where('nama', $user->nama)->first(); 
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
                                <option value="{{ $dataorg->name }}">{{ $dataorg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="project" class="form-label">Project</label>
                            <select name="project" id="project" class="form-control select2">
                                <option value="ALL">ALL</option>
                                @foreach($project as $dataproject)
                                <option value="{{ $dataproject->id }}" {{ request()->segment(3) == $dataproject->id ? 'selected' : '' }} >{{ $dataproject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="periode" class="form-label">Periode</label>
                            <select id="periode" class="form-control">
                                <option value="">Select Periode</option>
                                @foreach($months as $key => $label)
                                <option value="{{ $key }}" {{ request()->segment(4) == $label ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
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
                <h6>Employees Attendance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample1" class="table table-striped table-bordered">
                        <thead id="table-header">
                            <!-- Dynamic headers -->
                        </thead>
                        <tbody>
                            <!-- DataTable automatically populates this -->
                        </tbody>
                    </table>
                </div>
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
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script>
$(document).ready(function () {
    let table;

    function generateDates(startDate, endDate) {
        const dates = [];
        let current = moment(startDate);
        while (current.isSameOrBefore(endDate)) {
            dates.push(current.format('YYYY-MM-DD'));
            current.add(1, 'days');
        }
        return dates;
    }

    function generateTableHeader(startDate, endDate) {
        const dates = generateDates(startDate, endDate);
        let headerHtml = '<tr><th>Nama</th>';
        const columns = [{ data: 'nama', name: 'name' }];

        dates.forEach(date => {
            const formattedDate = moment(date).format('DD MMM YYYY');
            headerHtml += `<th>${formattedDate}</th>`;
            columns.push({
                data: `attendance.absens_${moment(date).format('YYYYMMDD')}`,
                name: `attendance.absens_${moment(date).format('YYYYMMDD')}`,
                render: data => {
                    // console.log(data);
                    if (data && Object.keys(data).length > 0) { 
                        if (data.clock_in !== '-') {
                            return `<span class="text-success">${data.clock_in}</span> - <span class="text-danger">${data.clock_out}</span>`;
                        } else {
                            return `<span class="text-primary">${data.schedule}</span>`;
                        }
                    }
                    return '';
                }
            });
        });

        headerHtml += '</tr>';
        $('#table-header').html(headerHtml);
        return columns;
    }

    function initDataTable(startDate, endDate) {
        const columns = generateTableHeader(startDate, endDate);
        const urlSegments = window.location.pathname.split('/');
        const segment3 = urlSegments[3]; // Adjust index based on your route
        const segment4 = urlSegments[4]; // Adjust index based on your route


        if (table) {
            table.destroy();
        }

        table = $('#dataTableExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `/kas/report-detail/${segment3}/${segment4}`,
                data: d => {
                    d.periode = $('#periode').val();
                    d.organisasi = $('#organisasi').val();
                    d.project = $('#project').val();
                },
            },
            columns,
            drawCallback() {
                this.api().columns.adjust();
            }
        });
    }

    const defaultStart = moment().startOf('month').date(21);
    const defaultEnd = moment().startOf('month').add(1, 'month').date(19);

    initDataTable(defaultStart, defaultEnd);

    $('#periode').change(function () {
        const [startDate, endDate] = $(this).val().split(' - ').map(date => moment(date));
        initDataTable(startDate, endDate);
    });

    $('#organisasi, #project').change(function () {
        table.ajax.reload();
    });
});
</script>
@endpush
