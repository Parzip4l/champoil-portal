@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@php
    $user = Auth::user();
    $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
        ->select('unit_bisnis','organisasi')
        ->first();
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
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
                @php
                    $employee = \App\Employee::where('nik', $data->employee_code)->first();
                    $allowances = json_decode($data->allowances, true);
                    $deductions = json_decode($data->deductions, true);
                @endphp
            <div class="card-header">
                <h6 class="card-title align-self-center mb-0">Payrol Component {{$employee->nama}}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payrol-component.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Karyawan</label>
                            <input type="text" class="form-control" value="{{ \App\Employee::where('nik', $data->employee_code)->value('nama') ?? 'Nama tidak ditemukan' }}">
                            <input type="hidden" class="form-control" name="employee_code" value="{{$data->employee_code}}">
                        </div>
                        <div class="title mt-2 mb-2">
                            <h5>Allowence</h5>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Gaji Pokok</label>
                            <input type="number" name="basic_salary" class="form-control allowance" value="{{$data->basic_salary}}" required>   
                        </div>
                        @php
                            $dataArray = json_decode($data->allowances, true);
                            $datadeduction = json_decode($data->deductions, true);
                        @endphp
                        @foreach($dataArray['data'] as $id => $value)
                            <div class="col-md-6 mb-2">
                                <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                                <input type="number" name="allowance[{{$id}}][]" class="form-control allowance" value="{{$value[0]}}" required>   
                            </div>
                        @endforeach
                        <div class="title mt-2 mb-2">
                            <h5>Deduction</h5>
                            <hr>
                        </div>
                        @foreach($datadeduction['data'] as $id => $value)
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                            <input type="number" name="deduction[{{$id}}][]" class="form-control deduction" value="{{$value[0]}}" id="t_deduction" required>   
                        </div>
                        @endforeach
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
    // Fungsi untuk menghitung total allowances
    function calculateTotalAllowance() {
        let totalAllowance = 0;
        $('.allowance').each(function() {
            const allowanceValue = parseFloat($(this).val()) || 0;
            totalAllowance += allowanceValue;
        });
        $('#t_allowance').val(totalAllowance);
    }

    // Fungsi untuk menghitung total deductions
    function calculateTotalDeduction() {
        let totalDeduction = 0;
        $('.deduction').each(function() {
            const deductionValue = parseFloat($(this).val()) || 0;
            totalDeduction += deductionValue;
        });
        $('#t_deduction').val(totalDeduction);
    }

    // Menghitung total allowances dan deductions ketika ada perubahan nilai
    $('.allowance, .deduction').on('input', function() {
        calculateTotalAllowance();
        calculateTotalDeduction();

        // Menghitung THP
        const totalAllowance = parseFloat($('#t_allowance').val()) || 0;
        const totalDeduction = parseFloat($('#t_deduction').val()) || 0;
        const BasiSalary = parseFloat($('#basic_salary').val());
        const thp = BasiSalary + totalAllowance - totalDeduction;
        $('#thp').val(thp);
    });
});
</script>
@endpush