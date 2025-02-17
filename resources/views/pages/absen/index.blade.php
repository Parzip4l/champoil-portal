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
                <h6 class="">Employees Attendance <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#export-attendance" class="btn btn-success btn xs" style="float:right">Export</a></h6>
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
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="export-attendance" tabindex="-1" aria-labelledby="exportPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title" id="exportPayrollModalLabel">Export Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
            <div class="modal-body">
                <form action="" method="GET">
                    <div class="form-group">
                        <label for="" class="form-label">Absen Periode</label>
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
            <button type="button" class="btn btn-success btn-sm" id="exportButton" style="float:right">Export</button>
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
    var table;

    // Fungsi untuk menghasilkan daftar tanggal
    function generateDates(startDate, endDate) {
        var dates = [];
        var current = moment(startDate);

        // Menambahkan tanggal satu per satu dalam rentang
        while (current.isSameOrBefore(endDate)) {
            dates.push(current.format('YYYY-MM-DD'));
            current.add(1, 'days');
        }

        return dates;
    }

    // Fungsi untuk menghasilkan header tabel
    function generateTableHeader(startDate, endDate) {
        var headerHtml = '<tr><th>Nama</th>';
        var columns = [{ data: 'nama', name: 'name' }];

        // Dapatkan semua tanggal dalam rentang startDate sampai endDate
        var dates = generateDates(startDate, endDate);

        // Menambahkan kolom untuk setiap tanggal
        dates.forEach(function (date) {
            var dateFormatted = moment(date).format('DD MMM YYYY');
            headerHtml += `<th>${dateFormatted}</th>`;
            columns.push({
                data: `attendance.absens_${moment(date).format('YYYYMMDD')}`,
                name: `attendance.absens_${moment(date).format('YYYYMMDD')}`,
                render: function (data) {
                    if (data) {
                        return '<span class="text-success">' + data.clock_in + '</span> - <span class="text-danger">' + data.clock_out + '</span>';
                    } else {
                        return '-';
                    }
                }
            });
        });

        headerHtml += '</tr>';
        $('#table-header').html(headerHtml);
        return columns;
    }

    // Fungsi untuk menginisialisasi DataTable
    function initDataTable(startDate, endDate) {
        var columns = generateTableHeader(startDate, endDate);

        if (table) {
            table.destroy(); // Destroy the previous table instance
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
            drawCallback: function(settings) {
                var api = this.api();
                var columnsCount = api.columns().header().length;
                var headerCells = $(api.table().header()).find('th');

                // Pastikan bahwa lebar kolom ditetapkan sesuai dengan kolom yang tersedia
                for (var i = 1; i < columnsCount; i++) {
                    var column = api.column(i);
                    headerCells[i].style.width = column.sWidthOrig !== null && column.sWidthOrig !== '' ? 
                        column.sWidthOrig :
                        ''; // Langsung menggunakan nilai sWidthOrig tanpa fungsi _fnStringToCss
                }

                // Force adjust columns to fix width calculation issue
                api.columns.adjust();
            }
        });
    }

    // Periode default
    var defaultStart = moment().startOf('month').date(21); 
    var defaultEnd = moment().startOf('month').add(1, 'month').date(19); 

    // Inisialisasi DataTable dengan periode default
    setTimeout(function () {
        initDataTable(defaultStart, defaultEnd);
    }, 1000); // Timeout 100ms untuk memastikan elemen DOM sudah ter-render

    // Handle perubahan periode
    $('#periode').change(function () {
        var periode = $(this).val();
        var [startDate, endDate] = periode.split(' - ');
        console.log('Start:', startDate, 'End:', endDate);
        initDataTable(moment(startDate), moment(endDate));
    });

    // Handle perubahan filter organisasi dan project
    $('#organisasi, #project').change(function () {
        table.ajax.reload(); // Reload data pada DataTable
    });
});

</script>


<script>
    document.getElementById('exportButton').addEventListener('click', function () {
        let selectedMonth = $("#month").val(); // Ambil value dari dropdown
        let company = "{{ $employee->unit_bisnis }}"; // Ambil company dari Blade

        // Pastikan pengguna telah memilih bulan
        if (!selectedMonth) {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Silakan pilih periode absen!",
            });
            return;
        }
        
        Swal.fire({
            title: 'Processing Export',
            text: 'Please wait while the export is being generated...',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        axios.post("/api/v1/exportAbsens", {
            month: selectedMonth,
            company: company
        })
        .then(function (response) {
            Swal.close();
            Swal.fire({
                title: 'Export Success',
                text: 'Absensi data export is ready!',
                icon: 'success',
            }).then(() => {
                const url = response.data.url;  // URL returned from the server
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'absensi_frontline.xlsx');  // Adjust file name if needed
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