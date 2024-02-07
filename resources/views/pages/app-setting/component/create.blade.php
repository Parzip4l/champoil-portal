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
    <div class="card custom-card2">
      <div class="card-body">
        <div class="head-card d-flex justify-content-between mb-3">
            <h6 class="card-title align-self-center mb-0">Assign Additional Component</h6>
            <div class="tombol-pembantu d-flex">
                <div class="dropdown"> 
                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-lg text-muted pb-3px align-self-center" data-feather="align-justify"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i data-feather="upload" class="icon-sm me-2"></i> Import</a>
                        <a class="dropdown-item d-flex align-items-center me-2"  href="https://truest.co.id/wp-content/uploads/2024/02/Tamplate-Karyawan-1.xlsx"><i data-feather="file-text" class="icon-sm me-2"></i> Download Template</a>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{route('additional-component.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="">Type</label>
                                <select name="type" id="" class="form-control">
                                    <option value="Allowences">Allowences</option>
                                    <option value="Deductions">Deductions</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="">Effective Date</label>
                                <input type="date" class="form-control" name="effective_date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="EmployeeTable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Component</th>
                                    <th>Nominal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <button type="button" id="addProduct" class="btn btn-primary mt-1 mb-3">Tambah Employee</button>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Save Data</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control mb-2" name="csv_file" required>
                    <button type="submit" class="btn btn-primary w-100">Import Excel</button>
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
        function addProductRow() {
            const employeeTableBody = document.querySelector('#EmployeeTable tbody');
                const newRow = `
                    <tr>
                        <td>
                            <select class="js-example-basic-single form-control" name="employee_code[]">
                                @foreach($employees as $karyawan)
                                    <option value="{{ $karyawan->nik ?? 'Tidak Terdaftar' }}">{{ $karyawan->nama ?? 'Tidak Terdaftar' }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control js-example-basic-single" name="component_code[]" id="componentData">
                                @foreach($component as $componentdata)
                                    <option value="{{ $componentdata->id ?? 'Tidak Terdaftar' }}">{{ $componentdata->name ?? 'Tidak Terdaftar' }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="purchase-uom-td">
                            <input type="number" name="nominal[]" placeholder="1" class="form-control">  
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">Hapus</button>
                        </td>
                    </tr>
                `;

                employeeTableBody.insertAdjacentHTML('beforeend', newRow);
        }

        function removeProductRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function selectAll() {
            const allRows = document.querySelectorAll('#EmployeeTable tbody tr');
            allRows.forEach(row => {
                const inputFields = row.querySelectorAll('input');
                inputFields.forEach(input => {
                    input.value = 0;
                });
            });
        }

        document.getElementById('addProduct').addEventListener('click', addProductRow);
        document.getElementById('addProduct').addEventListener('click', selectAll);
    </script>


    <style>
        .select2-container {
            width : 100%!important;
        }
    </style>
    <script>
            $(document).ready(function () {
                $("#kota").select2({
                    theme: 'bootstrap4',
                    placeholder: "Please Select"
                });
    
                $("#componentData").select2({
                    theme: 'bootstrap4',
                    placeholder: "Please Select"
                });
            });
        </script>
@endpush
