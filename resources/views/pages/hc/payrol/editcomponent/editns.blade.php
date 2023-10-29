@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Payrol Component Frontline Officer</h4>
        <form method="POST" action="{{ route('updatecomponentNS.update', $payrolComponent->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Karyawan</label>
                    @php 
                        $employee = \App\Employee::where('nik', $payrolComponent->employee_code)->first();
                        $allowences = json_decode($payrolComponent->allowances);
                        $deductions = json_decode($payrolComponent->deductions);
                    @endphp
                    <input type="hidden" name="employee_code" value="{{$payrolComponent->employee_code}}">
                    <input type="text" class="form-control" value="{{$employee->nama}}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="Ktp" class="form-label">Daily Sallary</label>
                    <input type="number" class="form-control" id="basic_salary" name="daily_salary" placeholder="Rp." value="{{$payrolComponent->daily_salary}}">
                </div>
            </div>
            <h5>Allowance</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                <div class="col-md-6">
                    <label class="form-label">Lembur /Jam</label>
                    <input type="number" class="form-control allowance" name="allowances[lembur][]" placeholder="Rp." required value="{{$allowences->lembur[0]}}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Uang Makan</label>
                    <input type="number" class="form-control allowance" name="allowances[uang_makan][]" placeholder="Rp." required value="{{$allowences->uang_makan[0]}}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Kerajinan</label>
                    <input type="number" id="t_alatkerja" class="form-control allowance" name="allowances[kerajinan][]" placeholder="Rp. " required value="{{$allowences->kerajinan[0]}}">
                </div>
            </div>
            <h5>Deductions</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Mess</label>
                    <input type="number" id="bpjs_ks" class="form-control deduction" name="deductions[mess][]" placeholder="Rp. " required value="{{$deductions->mess[0]}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                    <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[hutang][]" placeholder="Rp. " required value="{{$deductions->hutang[0]}}">
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Potongan Lain Lain</label>
                    <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[lain_lain][]" placeholder="Rp. " required value="{{$deductions->lain_lain[0]}}">
                </div>
            </div>
          <button class="btn btn-primary" type="submit">Submit</button>
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
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
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