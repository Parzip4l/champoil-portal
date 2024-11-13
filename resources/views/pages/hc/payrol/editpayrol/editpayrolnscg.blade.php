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
    <div class="card custom-card2">
      <div class="card-body">
        @php 
            $employee = \App\Employee::where('nik', $payrolComponent->employee_code)->first();
            $dateParts = explode(" - ", $payrolComponent->periode);
            $startDate = \Carbon\Carbon::parse($dateParts[0])->format('j F Y');
            $endDate = \Carbon\Carbon::parse($dateParts[1])->format('j F Y');
            $allowences = json_decode($payrolComponent->allowences);
            $deductions = json_decode($payrolComponent->deductions);
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
                    <label for="Ktp" class="form-label">Monthly Salary</label>
                    <input type="number" class="form-control datainput" id="basic_salary" name="daily_salary" placeholder="Rp." value="{{$payrolComponent->basic_salary}}">
                </div>
            </div>
            <div class="row mb-3 allowance-group">
                <div class="col-md-6">
                    <label class="form-label">Total Schedule</label>
                    <input type="number" class="form-control datainput totalabsen" name="totalHariSchedule" placeholder="Rp." required value="{{ $allowences->totalHariSchedule ?? '0' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Masuk</label>
                    <input type="number" class="form-control datainput allowance" id="TotalMasuk" name="totalHari" placeholder="Rp." required value="{{$allowences->totalHari ?? '0' }}" readonly>
                </div>
            </div>
            <h5>Allowance</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                <div class="col-md-6">
                    <label class="form-label">Total Hari Backup</label>
                    <input type="number" class="form-control datainput" name="totalHariBackup" id="totalHariBackup" placeholder="Rp." required value="{{$allowences->totalHariBackup ?? '0' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Gaji Backup</label>
                    <input type="number" class="form-control datainput jamLembur" id="totalGajiBackup" name="totalGajiBackup" placeholder="Rp." required value="{{$allowences->totalGajiBackup ?? '0' }}">
                </div>
            </div>
            <h5>Deductions</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                    <input type="number" id="mess" class="form-control datainput deduction" name="potongan_hutang" placeholder="Rp. " required value="{{$deductions->hutang_koperasi ?? '0' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Garda Pratama</label>
                    <input type="number" id="potongan_gp" class="form-control datainput deduction" name="potongan_gp" placeholder="Rp. " required value="{{$deductions->potongan_Gp ?? '0' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="kode_karyawan" class="form-label">Jumlah Hari Tidak Absen</label>
                    <input type="number" id="tidak_absen" class="form-control datainput deduction" name="tidak_absen" placeholder="Rp. " required value="{{$deductions->tidak_absen ?? '0' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="kode_karyawan" class="form-label">Potongan Tidak Absen / Hari</label>
                    <input type="number" id="rate_harian" class="form-control deduction" name="rate_harian" placeholder="Rp. " required value="{{$deductions->rate_harian ?? '0' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="kode_karyawan" class="form-label">Total Potongan Absensi</label>
                    <input type="number" id="potongan_absen" class="form-control deduction" name="potongan_absen" placeholder="Rp. " required value="{{$deductions->potongan_absen ?? '0' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="kode_karyawan" class="form-label">Iuran Koperasi</label>
                    <input type="number" id="potongan_absen" class="form-control deduction" name="potongan_absen" placeholder="Rp. " required value="{{$deductions->iuran_koperasi ?? '0' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">PPH 21</label>
                    <input type="number" id="PPH21" class="form-control datainput deduction" name="PPH21" placeholder="Rp. " required value="{{ round($deductions->PPH21 ?? 0) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Take Home Pay</label>
                    <input type="number" id="thp" class="form-control datainput" name="thp" placeholder="Rp. " required value="{{$payrolComponent->thp ?? '0' }}">
                </div>
            </div>
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