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
            <div class="card-header">
                <h6 class="card-title align-self-center mb-0">Payrol Component {{$payrolComponent->employee_code}}</h6>
            </div>
                @php
                    $employee = \App\Employee::where('nik', $payrolComponent->employee_code)->first();
                    $allowances = json_decode($payrolComponent->allowances, true);
                    $deductions = json_decode($payrolComponent->deductions, true);
                @endphp
            <div class="card-body">
                <form method="POST" action="{{ route('payrol-component.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" value="{{$employee->nama}}" class="form-control" disabled>
                            <input type="hidden" name="employee_code" value="{{$payrolComponent->employee_code}}">
                        </div>
                        <div class="col-md-6">
                            <label for="Ktp" class="form-label">Basic Sallary</label>
                            <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Rp." value="{{ number_format($payrolComponent->basic_salary, 0, ',', '.') }}">
                        </div>
                    </div>
                    <h5>Allowance</h5>
                    <hr>
                    <div class="row mb-3 allowance-group">
                        <div class="col-md-6">
                            <label class="form-label">Tunjangan Struktural</label>
                            <input type="number" class="form-control allowance" name="allowances[t_struktural][]" placeholder="Rp." required value="{{ number_format($allowances['t_struktural'][0], 0, ',', '.') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tunjangan Kinerja</label>
                            <input type="number" class="form-control allowance" name="allowances[t_kinerja][]" placeholder="Rp." required value="{{ number_format($allowances['t_kinerja'][0], 0, ',', '.') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">Tunjangan Alat Kerja</label>
                            <input type="number" id="t_alatkerja" class="form-control allowance" name="allowances[t_alatkerja][]" placeholder="Rp. " required value="{{ number_format($allowances['t_alatkerja'][0], 0, ',', '.') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">Total Allowance</label>
                            <input type="number" id="t_allowance" class="form-control" name="allowances[t_allowance][]" placeholder="Rp. " required readonly value="{{ number_format($allowances['t_allowance'][0], 0, ',', '.') }}">
                        </div>
                    </div>
                    <h5>Deductions</h5>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode_karyawan" class="form-label">Bpjs Kesehatan</label>
                            <input type="number" id="bpjs_ks" class="form-control deduction" name="deductions[bpjs_ks][]" placeholder="Rp. " required value="{{ number_format($deductions['bpjs_ks'][0], 0, ',', '.') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="kode_karyawan" class="form-label">Bpjs Ketenagakerjaan</label>
                            <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[bpsj_tk][]" placeholder="Rp. " required value="{{ number_format($deductions['bpsj_tk'][0], 0, ',', '.') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">PPH 21</label>
                            <input type="number" id="pph21" class="form-control deduction" name="deductions[pph21][]" placeholder="Rp. " required value="{{ number_format($deductions['pph21'][0], 0, ',', '.') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                            <input type="number" id="p_hutang" class="form-control deduction" name="deductions[p_hutang][]" placeholder="Rp. " required value="{{ number_format($deductions['p_hutang'][0], 0, ',', '.') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">Total Deductions</label>
                            <input type="number" id="t_deduction" class="form-control" name="deductions[t_deduction][]" placeholder="Rp. " required readonly value="{{ number_format($deductions['t_deduction'][0], 0, ',', '.') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">THP</label>
                            <input type="number" id="thp" class="form-control" name="thp" placeholder="Rp. " required readonly value="{{ number_format($payrolComponent->thp, 0, ',', '.') }}">
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