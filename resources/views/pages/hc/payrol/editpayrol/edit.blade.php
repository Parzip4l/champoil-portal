@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush
@php
    $user = Auth::user();
    $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
        ->select('unit_bisnis','organisasi')
        ->first();
@endphp
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="row mb-4">
        <div class="topbar-wrap d-flex justify-content-between">
            <div class="arrow-back">
                <a href="#" onclick="goBack()" class="d-flex color-custom">
                    <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                    <h5 class="align-self-center">Kembali</h5>
                </a>
            </div>
        </div>
    </div>
    <div class="card custom-card2">
      <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h4 class="card-title">Payrol Component</h4>
        <form method="POST" action="{{ route('payslip.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php 
                $employee = \App\Employee::where('nik', $data->employee_code)->first();
                $allowences = json_decode($data->allowances);
                $deductions = json_decode($data->deductions);
            @endphp
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Karyawan</label>
                    <input type="hidden" name="employee_code" value="{{$data->employee_code}}">
                    <input type="text" class="form-control" value="{{$employee->nama}}" readonly>
                    <input type="hidden" name="month" value="{{$data->month}}">
                    <input type="hidden" name="year" value="{{$data->year}}">
                </div>
                <div class="col-md-6">
                    <label for="Ktp" class="form-label">Basic Sallary</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Rp." value="{{$data->basic_salary}}">
                </div>
            </div>
            <div class="title mt-2 mb-2">
                <h5>Allowence</h5>
                <hr>
            </div>
            @php
                $dataArray = json_decode($data->allowances, true);
                $datadeduction = json_decode($data->deductions, true);
            @endphp
            <div class="row">
            @foreach($dataArray['data'] as $id => $value)
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                    <input type="number" name="allowances[{{$id}}][]" class="form-control allowance" value="{{$value[0]}}" required>   
                </div>
            @endforeach
            </div>
                <div class="title mt-2 mb-2">
                    <h5>Deduction</h5>
                    <hr>
                </div>
            <div class="row">
            @foreach($datadeduction['data'] as $id => $value)
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                    <input type="number" name="deductions[{{$id}}][]" class="form-control deduction" value="{{$value[0]}}" id="t_deduction" required>   
                </div>
            @endforeach
            </div>
          <button class="btn btn-primary w-100" type="submit">Update Payroll</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script>
function goBack() {
    window.history.back();
}
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

    function calculateTHP() {
        // Menghitung THP
        const totalAllowance = parseFloat($('#t_allowance').val()) || 0;
        const totalDeduction = parseFloat($('#t_deduction').val()) || 0;
        const BasicSalary = parseFloat($('#basic_salary').val()) || 0;
        const thp = Math.round(BasicSalary + totalAllowance - totalDeduction);
        $('#thp').val(thp);
    }

    // Menghitung total allowances, deductions, dan THP ketika ada perubahan nilai
    $('.allowance, .deduction, .totalthp, #basic_salary').on('input', function() {
        calculateTotalAllowance();
        calculateTotalDeduction();
        calculateTHP();
    });
});
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
@endpush