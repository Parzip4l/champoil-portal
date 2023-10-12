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
            <h6 class="card-title align-self-center mb-0">Payroll Frontline Officer {{$start_date2}} - {{$end_date2}}</h6>
        </div>
        <hr>
        <form action="{{route('payroll-kas.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
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
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="{{ date('Y') }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Unit Bisnis</label>
                        <select name="" id="UnitBisnis" class="form-control">
                            <option value="Champoil">Champoil</option>
                            <option value="Kas">Kas</option>
                        </select>
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
<!-- Payroll -->
<script>
    function addProductRow() {
        const newRow = `
            <tr>
                <td>
                    <select class="form-control employeeSelect" id="employeeSelect" name="employee_code[]">
                        @foreach ($employee as $data)
                            <option value="{{$data->employee_code}}">{{$data->nama}}</option>
                        @endforeach
                    </select>
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
<script>
    $(document).ready(function() {
    // Event listener for changes in the UnitBisnis select
    $('#UnitBisnis').change(function() {
        // Get the selected unit bisnis
        const selectedUnitBisnis = $(this).val();

        // Update the list of employees based on the selected unit bisnis
        updateEmployeeOptions(selectedUnitBisnis);
    });

    // Function to update the list of employees based on unit bisnis
    function updateEmployeeOptions(unitBisnis) {
        // Get all selects with the employeeSelect class
        const employeeSelects = $('.employeeSelect');

        // Send an AJAX request to get the list of employees based on unit bisnis
        $.ajax({
            url: '/get-employees', // Adjust the URL accordingly
            method: 'GET',
            data: { unit_bisnis: unitBisnis },
            success: function(response) {
            console.log('Response from server:', response);

            const employees = response.employees;

            if (Array.isArray(employees)) {
                // Update each select with the appropriate employee options
                employeeSelects.each(function(index) {
                    // Rest of the code remains the same
                });
            } else {
                console.error('Invalid response format: employees is not an array.');
            }
        },
            error: function(xhr, status, error) {
                console.error('Error fetching employees:', error);
            }
        });
    }
});
</script>
@endpush