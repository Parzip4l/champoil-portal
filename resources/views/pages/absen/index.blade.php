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
    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first(); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
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
                        @if($employee->unit_bisnis === 'Kas')
                        <div class="col-md-4">
                            <label for="project" class="form-label">Project</label>
                            <select name="project" id="project" class="form-control select2">
                                <option value="ALL">ALL</option>
                                @foreach($project as $dataproject)
                                <option value="{{ $dataproject->id }}">{{ $dataproject->name }}</option>
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
                <h6 class="">Employees Attendance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="dataTableExample1" class="table table-striped table-bordered">
    <thead id="table-header">
        <!-- Header dinamis akan diisi oleh JavaScript -->
    </thead>
    <tbody>
        <!-- DataTable akan secara otomatis mengisi data di sini -->
    </tbody>
</table>

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
        var table;

        function generateTableHeader(startDate, endDate) {
            var headerHtml = '<tr><th>Nama</th>'; 
            var columns = [{ data: 'nama', name: 'name' }];
            
            for (var date = moment(startDate); date.isSameOrBefore(endDate); date.add(1, 'days')) {
                var dateFormatted = date.format('DD MMM YYYY');
                headerHtml += `<th>${dateFormatted}</th>`;
                columns.push({
                    data: `attendance.absens_${date.format('YYYYMMDD')}`,
                    name: `attendance.absens_${date.format('YYYYMMDD')}`,
                    render: function (data) {
                        if (data) {
                            return '<span class="text-success">' + data.clock_in + '</span> - <span class="text-danger">' + data.clock_out + '</span>';
                        } else {
                            return '-';
                        }
                    }
                });
            }

            headerHtml += '</tr>';
            $('#table-header').html(headerHtml);
            return columns;
        }

        function initDataTable(startDate, endDate) {
            var columns = generateTableHeader(startDate, endDate);

            if (table) {
                table.destroy();
            }
            table = $('#dataTableExample1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("absen.index") }}',
                    data: function (d) {
                        d.periode = $('#periode').val();
                        d.organisasi = $('#organisasi').val(); // Tambahkan filter organisasi
                        d.project = $('#project').val(); // Tambahkan filter project
                    },
                },
                columns: columns,
            });
        }

        // Periode default
        var defaultStart = moment().startOf('month').date(21); 
        var defaultEnd = moment().startOf('month').add(1, 'month').date(20); 

        // Inisialisasi DataTable dengan periode default
        initDataTable(defaultStart, defaultEnd);

        // Handle perubahan periode
        $('#periode').change(function () {
            var periode = $(this).val();
            var [startDate, endDate] = periode.split(' - ');
            initDataTable(moment(startDate), moment(endDate));
        });

        // Handle perubahan filter organisasi dan project
        $('#organisasi, #project').change(function () {
            table.ajax.reload(); // Reload data pada DataTable
        });
    });
</script>

@endpush