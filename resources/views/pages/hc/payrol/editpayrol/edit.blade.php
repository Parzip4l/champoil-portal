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
        <form method="POST" action="{{ route('payslip.update', $payrolComponent->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php 
                $employee = \App\Employee::where('nik', $payrolComponent->employee_code)->first();
                $allowences = json_decode($payrolComponent->allowances);
                $deductions = json_decode($payrolComponent->deductions);
            @endphp
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Karyawan</label>
                    <input type="hidden" name="employee_code" value="{{$payrolComponent->employee_code}}">
                    <input type="text" class="form-control" value="{{$employee->nama}}" readonly>
                    <input type="hidden" name="month" value="{{$payrolComponent->month}}">
                    <input type="hidden" name="year" value="{{$payrolComponent->year}}">
                </div>
                <div class="col-md-6">
                    <label for="Ktp" class="form-label">Basic Sallary</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Rp." value="{{$payrolComponent->basic_salary}}">
                </div>
            </div>
            <h5>Allowance</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                @if($karyawanLogin->unit_bisnis === 'CHAMPOIL')
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Struktural</label>
                    <input type="number" class="form-control allowance" name="allowances[t_struktural][]" placeholder="Rp." required value="{{$allowences->t_struktural[0]}}">
                </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Kinerja</label>
                    <input type="number" class="form-control allowance" name="allowances[t_kinerja][]" placeholder="Rp." required value="{{$allowences->t_kinerja[0]}}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Tunjangan Alat Kerja</label>
                    <input type="number" id="t_alatkerja" class="form-control allowance" name="allowances[t_alatkerja][]" placeholder="Rp. " required value="{{$allowences->t_alatkerja[0]}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Total Allowance</label>
                    <input type="number" id="t_allowance" class="form-control" name="allowances[t_allowance][]" placeholder="Rp. " required readonly value="{{$allowences->t_allowance[0]}}">
                </div>
            </div>
            <h5>Deductions</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">BPJS Kesehatan</label>
                    <input type="number" id="bpjs_ks" class="form-control deduction" name="deductions[bpjs_ks][]" placeholder="Rp. " required value="{{$deductions->bpjs_ks[0]}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">BPJS Ketenagakerjaan</label>
                    <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[bpsj_tk][]" placeholder="Rp. " required value="{{$deductions->bpsj_tk[0]}}">
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">PPH 21</label>
                    <input type="number" id="pph21" class="form-control deduction" name="deductions[pph21][]" placeholder="Rp. " required value="{{$deductions->pph21[0]}}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                    <input type="number" id="p_hutang" class="form-control deduction" name="deductions[p_hutang][]" placeholder="Rp. " required value="{{$deductions->p_hutang[0]}}">
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Total Deductions</label>
                    <input type="number" id="t_deduction" class="form-control" name="deductions[t_deduction][]" placeholder="Rp. " required readonly value="{{$deductions->t_deduction[0]}}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <label for="kode_karyawan" class="form-label">THP</label>
                    <input type="number" id="thp" class="form-control totalthp" name="thp" placeholder="Rp. " required readonly value="{{$payrolComponent->net_salary}}">
                </div>
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