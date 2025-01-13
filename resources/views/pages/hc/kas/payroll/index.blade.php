@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@php 
    $user = Auth::user(); 
    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first(); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header ">
                <h5 class="mb-0 align-self-center">Data Payroll</h5>
                <a href="{{route('payroll-kas.create')}}" class="btn btn-sm btn-outline-primary" style="float:right;margin-left:3px;">Create Payroll</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportPayrollModal" style="float:right;">Export Payroll</a>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Periode</th>
                                <th>THP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataPayroll as $data)
                            <tr>
                                @php 
                                    // Mengambil nama karyawan berdasarkan employee_code
                                    $employee = \App\Employee::where('nik', $data->employee_code)->first();
                                    $employeeName = $employee ? $employee->nama : 'Unknown';
                                @endphp
                                <td> <a href="{{route('payroll-kas.show', $data->id)}}">{{ $employeeName }} ({{ $data->employee_code }}) </a></td>
                                <td> {{ $data->periode }} </td>
                                <td>{{ 'Rp ' . number_format($data->thp, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exportPayrollModal" tabindex="-1" aria-labelledby="exportPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportPayrollModalLabel">Export Payroll Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="GET">
                    <div class="form-group">
                        <label for="" class="form-label">Payroll Periode</label>
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
                const deleteUrl = "{{ route('shift.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Shift Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Shift Failed to Delete',
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
<script>
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

    axios.post("/api/v1/export-payroll", {
        month: month,
        employee: employee
    })
    .then(function (response) {
        Swal.close();
        Swal.fire({
            title: 'Export Success',
            text: 'Your payroll data export is ready!',
            icon: 'success',
        }).then(() => {
            const url = response.data.url;  // URL returned from the server
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'payroll.xlsx');  // Adjust file name if needed
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