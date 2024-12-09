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
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card custom-card2">
      <div class="card-body">
        @php 
            $employee = \App\Employee::where('nik', $payrolComponent->employee_code)->first();
            if($employee->unit_bisnis==='Run'){
                $dailysalary = \App\PayrolComponent_NS::where('employee_code', $payrolComponent->employee_code)->select('daily_salary')->first();
                $allowance = json_decode($payrolComponent->allowances);
                $totalOvertimeHours = intval($allowance->total_overtime_hours);
                $lembur = $allowance->lembur[0];
                $lemburpay = intval($allowance->total_overtime_pay);
                $totalabsen = $allowance->total_absence;
            }
            $dateParts = explode(" - ", $payrolComponent->periode);
            $startDate = \Carbon\Carbon::parse($dateParts[0])->format('j F Y');
            $endDate = \Carbon\Carbon::parse($dateParts[1])->format('j F Y');
            
        @endphp
        <h4 class="card-title">Payrol Edit {{$employee->nama}} Periode {{ \Carbon\Carbon::parse($dateParts[1])->format('j F Y') }}</h4>
        <form method="POST" action="{{ route('updateNS.payroldata', $payrolComponent->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Karyawan</label>
                    <input type="hidden" name="employee_code" value="{{$payrolComponent->employee_code}}">
                    <input type="text" class="form-control" value="{{$employee->nama}}" readonly>
                    <input type="hidden" name="periode" value="{{$payrolComponent->periode}}">
                </div>
                <div class="col-md-6">
                    <label for="Ktp" class="form-label">Daily Sallary</label>
                    <input type="number" class="form-control datainput" id="basic_salary" name="daily_salary" placeholder="Rp." value="@if($employee->unit_bisnis === 'Run'){{ $dailysalary->daily_salary }}@else{{ $payrolComponent->daily_salary }}@endif">
                </div>
            </div>
            <h5>Daily Salary</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                <div class="col-md-6">
                    <label class="form-label">Total Absensi</label>
                    <input type="number" class="form-control datainput totalabsen" name="total_absen" placeholder="Rp." required value="@if($employee->unit_bisnis === 'Run'){{$totalabsen}}@else{{$payrolComponent->total_absen}}@endif">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Daily Salary</label>
                    <input type="number" class="form-control datainput allowance" id="TotalDaily" name="total_daily" placeholder="Rp." required value="@if($employee->unit_bisnis === 'Run'){{$payrolComponent->basic_salary}}@else{{$payrolComponent->total_daily}}@endif" readonly>
                </div>
            </div>
            <h5>Allowance</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                <div class="col-md-4">
                    <label class="form-label">Lembur Salary</label>
                    <input type="number" class="form-control datainput" name="lembur_salary" id="LemburSalary" placeholder="Rp." required value="@if($employee->unit_bisnis === 'Run'){{$lembur}}@else{{$payrolComponent->lembur_salary}}@endif">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Lembur</label>
                    <input type="number" class="form-control datainput jamLembur" id="jamLembur" name="jam_lembur" placeholder="Rp." required value="@if($employee->unit_bisnis === 'Run'){{$totalOvertimeHours}}@else{{$payrolComponent->jam_lembur}}@endif">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Lembur</label>
                    <input type="number" class="form-control datainput allowance" id="TotalLembur" name="total_lembur" placeholder="Rp." required value="@if($employee->unit_bisnis === 'Run'){{$lemburpay}}@else{{$payrolComponent->total_lembur}}@endif">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Kerajinan</label>
                    <input type="number" id="Kerajinan" class="form-control datainput allowance" name="uang_kerajinan" placeholder="Rp. " required value="{{$payrolComponent->uang_kerajinan ?? 0}}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Uang Makan</label>
                    <input type="number" id="Makan" class="form-control datainput allowance" name="uang_makan" placeholder="Rp." required value="{{$payrolComponent->uang_makan ?? 0}}">
                </div>
            </div>
            <h5>Deductions</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Mess</label>
                    <input type="number" id="mess" class="form-control datainput deduction" name="potongan_mess" placeholder="Rp. " required value="{{$payrolComponent->potongan_mess ?? 0}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                    <input type="number" id="hutang" class="form-control datainput deduction" name="potongan_hutang" placeholder="Rp. " required value="{{$payrolComponent->potongan_hutang ?? 0}}">
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Potongan Lain Lain</label>
                    <input type="number" id="lain" class="form-control datainput deduction" name="potongan_lain" placeholder="Rp. " required value="{{$payrolComponent->potongan_lain ?? 0}}">
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Take Home Pay</label>
                    <input type="number" id="thp" class="form-control deduction" name="thp" placeholder="Rp. " required value="{{$payrolComponent->thp}}">
                </div>
            </div>
          <button class="btn btn-primary button-biru w-100" type="submit">Submit</button>
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
    // Function to calculate the total daily salary
    function calculateTotalDaily() {
        // Get the values from the input elements
        const dailySalary = parseFloat($('#basic_salary').val()) || 0;
        const totalAbsen = parseFloat($('input[name="total_absen"]').val()) || 0;

        // Calculate the total daily salary
        const totalDaily = dailySalary * totalAbsen;

        // Update the total daily input field with the calculated value
        $('input[name="total_daily"]').val(isNaN(totalDaily) ? 0 : totalDaily);
    }

    function calculateTotalLembur() {
        // Get the values from the input elements
        const lemburSalary = parseFloat($('#LemburSalary').val()) || 0;
        const totalJamLembur = parseFloat($('#jamLembur').val()) || 0;

        // Calculate the total daily salary
        const totalLemburSalary = lemburSalary * totalJamLembur;

        // Update the total daily input field with the calculated value
        $('input[name="total_lembur"]').val(isNaN(totalLemburSalary) ? 0 : totalLemburSalary);
    }

    // Hitung THP
    function calculateTHP() {
        const totalLemburSalaryData = parseFloat($('#TotalLembur').val()) || 0;
        const totalDailySalary = parseFloat($('#TotalDaily').val()) || 0;
        const UangMakan = parseFloat($('#Makan').val()) || 0;
        const UangKerajinan = parseFloat($('#Kerajinan').val()) || 0;
        const PotonganHutang = parseFloat($('#hutang').val()) || 0;
        const PotonganMess = parseFloat($('#mess').val()) || 0;
        const PotonganLainnya = parseFloat($('#lain').val()) || 0;
        const thp = totalLemburSalaryData + totalDailySalary + UangMakan + UangKerajinan - PotonganHutang - PotonganMess - PotonganLainnya;
        $('#thp').val(thp);
    }

    // Menghitung total allowances dan deductions ketika ada perubahan nilai
    $('.datainput').on('input', function() {
        calculateTotalDaily();
        calculateTotalLembur();
        calculateTHP();
    });
});
</script>
@endpush