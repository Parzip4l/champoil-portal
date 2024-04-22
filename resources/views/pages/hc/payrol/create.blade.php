@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@php
    $user = Auth::user();
    $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
        ->select('unit_bisnis','organisasi')
        ->first();
@endphp
@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <h4 class="card-title mb-0">Payrol Component</h4>
        </div>
      <div class="card-body">
        <form method="POST" action="{{ route('payrol-component.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Pilih Karyawan</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="employee_code">
                        @foreach($employee as $data)
                            <option value="{{$data->nik}}">{{$data->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="Ktp" class="form-label">Basic Sallary</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Rp.">
                </div>
            </div>
            <h5>Allowance</h5>
            <hr>
            <div class="row mb-3 allowance-group">
                @if($karyawanLogin->unit_bisnis === 'CHAMPOIL')
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Struktural</label>
                    <input type="number" class="form-control allowance" name="allowances[Tunjangan Strukturan][]" placeholder="Rp." required>
                </div>
                @endif
                @if($karyawanLogin->unit_bisnis === 'RUN')
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Fasilitas</label>
                    <input type="number" class="form-control allowance" name="allowances[Tunjangan Fasilitas][]" placeholder="Rp." required>
                </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Kinerja</label>
                    <input type="number" class="form-control allowance" name="allowances[Tunjangan Kinerja][]" placeholder="Rp." required>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kode_karyawan" class="form-label">Tunjangan Makan</label>
                    <input type="number" id="t_makan" class="form-control allowance" name="allowances[Tunjangan Makan][]" placeholder="Rp. " required>
                </div>
            </div>
            @if($karyawanLogin->unit_bisnis === 'Kas')
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Tunjangan Transportasi</label>
                    <input type="number" id="t_transportasi" class="form-control allowance" name="allowances[Tunjangan Transportasi][]" placeholder="Rp. " required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Fasilitas</label>
                    <input type="number" class="form-control allowance" name="allowances[Tunjangan Fasilitas][]" placeholder="Rp." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tunjangan Jabatan</label>
                    <input type="number" class="form-control allowance" name="allowances[Tunjangan Jabatan][]" placeholder="Rp." required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Gaji Rapel</label>
                    <input type="number" id="t_makan" class="form-control allowance" name="allowances[Gaji Rapel][]" placeholder="Rp. " required>
                </div>
            </div>
            @endif
            <div class="row mb-3">
                @if($karyawanLogin->unit_bisnis === 'CHAMPOIL')
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Tunjangan Alat Kerja</label>
                    <input type="number" id="t_alatkerja" class="form-control allowance" name="allowances[Tunjangan Alat Kerja][]" placeholder="Rp. " required>
                </div>
                @endif
                @if($karyawanLogin->unit_bisnis === 'RUN')
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Tunjangan Makan</label>
                    <input type="number" id="t_makan" class="form-control allowance" name="allowances[Tunjangan Makan][]" placeholder="Rp. " required>
                </div>
                @endif
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Total Allowance</label>
                    <input type="number" id="t_allowance" class="form-control" name="allowances[t_allowance][]" placeholder="Rp. " required readonly>
                </div>
            </div>
            <h5>Deductions</h5>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">BPJS Kesehatan</label>
                    <input type="number" id="bpjs_ks" class="form-control deduction" name="deductions[BPJS Kesehatan][]" placeholder="Rp. " required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">BPJS Ketenagakerjaan</label>
                    <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[BPJS Ketenagakerjaan][]" placeholder="Rp. " required>
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Jaminan Pensiun</label>
                    <input type="number" id="bpsj_tk" class="form-control deduction" name="deductions[j_pensiun][]" placeholder="Rp. " required disabled>
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">PPH 21</label>
                    <input type="number" id="pph21" class="form-control deduction" name="deductions[PPH 21][]" placeholder="Rp. " required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Potongan Hutang</label>
                    <input type="number" id="p_hutang" class="form-control deduction" name="deductions[Potongan Hutang][]" placeholder="Rp. " required>
                </div>
                <div class="col-md-6">
                    <label for="kode_karyawan" class="form-label">Total Deductions</label>
                    <input type="number" id="t_deduction" class="form-control" name="deductions[t_deduction][]" placeholder="Rp. " required readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <label for="kode_karyawan" class="form-label">THP</label>
                    <input type="number" id="thp" class="form-control" name="thp" placeholder="Rp. " required readonly>
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
            const BasicSalary = parseFloat($('#basic_salary').val());
            const thp = Math.round(BasicSalary + totalAllowance - totalDeduction);
            $('#thp').val(thp);
            console.log(thp);
        });
    });
</script>
@endpush