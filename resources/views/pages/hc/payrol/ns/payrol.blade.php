@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="head-card d-flex justify-content-between mb-3">
            <h6 class="card-title align-self-center mb-0">Payroll Frontline Officer</h6>
        </div>
        <hr>
        <form action="{{route('payrollns.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="" class="form-label">Payroll Periode</label>
                        <select name="month" id="month" class="form-control" required>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Week</label>
                        <select name="week" id="week" class="form-control" required>
                            <!-- Opsi week akan diisi otomatis menggunakan JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="{{ date('Y') }}" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Select Employee</label>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="EmployeeTable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Jam Lembur</th>
                                    <th>Uang Makan</th>
                                    <th>Uang Kerajinan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                        
                            </tbody>
                        </table>
                        <button type="button" id="addProduct" class="btn btn-primary mt-1 mb-3">Tambah Employee</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Run Payroll</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
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
                const deleteUrl = "{{ route('employee.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Employee Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Contact Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Contact Failed to Delete',
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
   $(document).ready(function() {
    // Memuat daftar minggu saat halaman pertama kali dimuat
    loadWeeks();

    // Memuat daftar minggu saat bulan dipilih
    $('#month').change(function() {
        loadWeeks();
    });

    function loadWeeks() {
        const selectedMonth = $('#month').val();

        // Kirim permintaan AJAX untuk mendapatkan daftar minggu berdasarkan bulan yang dipilih
        $.ajax({
            url: '/get-weeks',
            method: 'GET',
            data: { month: selectedMonth },
            success: function(response) {
                const weeks = response.weeks;
                const weekSelect = $('#week');
                weekSelect.empty(); // Hapus opsi sebelumnya

                // Tambahkan opsi week yang baru
                for (const week of weeks) {
                    const matches = week.match(/Week \d+ \((\d{4}-\d{2}-\d{2}) - (\d{4}-\d{2}-\d{2})\)/);
                    if (matches && matches.length === 3) {
                        const weekStart = matches[1];
                        const weekEnd = matches[2];
                        weekSelect.append(`<option value="${weekStart} - ${weekEnd}">${week}</option>`);
                    } else {
                        console.error('Invalid week format:', week);
                    }
                }
            }
        });
    }
});

</script>
<!-- Payroll -->
<script>
    function addProductRow() {
        const newRow = `
            <tr>
                <td>
                    <select class="form-control" id="employeeSelect" name="employee_code[]">
                        @foreach ($payrol as $data)
                        @php
                            $employee = \App\Employee::where('nik', $data->employee_code)
                                    ->where('unit_bisnis', 'CHAMPOIL') 
                                    ->first();
                        @endphp
                            @if ($employee)
                                <option value="{{$data->employee_code}}">{{$employee->nama}}</option>
                            @else
                                <option value="{{$data->employee_code}}">Karyawan Tidak Ditemukan</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="lembur_jam[]" placeholder="1" class="form-control">
                </td>
                <td>
                    <input type="number" name="uang_makan[]" placeholder="1" class="form-control">  
                </td> 
                <td class="purchase-uom-td">
                    <input type="number" name="uang_kerajinan[]" placeholder="1" class="form-control">  
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">Hapus</button>
                </td>
            </tr>
        `;
        document.querySelector('#EmployeeTable tbody').insertAdjacentHTML('beforeend', newRow);

        updateProductCategory(document.querySelector('#EmployeeTable tbody').lastElementChild.querySelector('.form-select'));
    }

    function removeProductRow(button) {
        const row = button.closest('tr');
        row.remove();
    }

    document.getElementById('addProduct').addEventListener('click', addProductRow);
</script>
@endpush