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
                    <h6 class="card-title align-self-center mb-0">Payroll</h6>
                </div>
                <hr>
                <form action="{{route('urbanica-payroll.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="" class="form-label">Payroll Periode</label>
                                <select name="month" id="" class="form-control" required>
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
                                <label for="" class="form-label">Select Employee</label>
                                <select class="form-control select2" id="employeeSelect" name="employee_code[]" multiple="multiple" data-width="100%">
                                    @foreach ($payrol as $data)
                                        @php
                                            $user = Auth::user();
                                            $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
                                                ->select('unit_bisnis','organisasi')
                                                ->first();
                                            $employee = \App\Employee::where('nik', $data->employee_code)
                                                            ->where('unit_bisnis', $karyawanLogin->unit_bisnis)
                                                            ->first();
                                        @endphp
                                        @if ($employee)
                                            <option value="{{$data->employee_code}}">{{$employee->nama}}</option>
                                        @else
                                            <option value="{{$data->employee_code}}">Karyawan Tidak Ditemukan</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="selectAllBtn" class="form-label">Select All Employee</label><br>
                            <button type="button" class="btn btn-primary btn-sm w-100" id="selectAllBtn">Select All</button>
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
    $(document).ready(function() {
        // Event listener for the "Select All" button
        $('#selectAllBtn').click(function() {
            // Select all options in the multiple select dropdown
            $('#employeeSelect option').prop('selected', true);
            // Trigger the change event to update Select2
            $('#employeeSelect').trigger('change');
        });

        // Initialize Select2
        $('.js-example-basic-multiple').select2();
    });
</script>
@endpush