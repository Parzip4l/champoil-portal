@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Top Bar -->
<div class="row mb-5 mobile">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('myslip')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">My Payslip</h5>
            </a>
        </div>
    </div>
</div>
<!-- End -->
<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-6 ps-0">
                        <a href="#" class="noble-ui-logo d-block mt-3 mb-6">TRUEST<span> LOGO</span></a>                 
                        <h5 class="mt-5 mb-2 text-muted">Payslip Details</h5>
                        @php
                            $employee = \App\Employee::where('nik', $dataPayslip[0]['employee_code'])->first();
                            $dataEarnings = json_decode($dataPayslip[0]['allowances'], true);
                            $datadeductions = json_decode($dataPayslip[0]['deductions'], true);
                            $user = Auth::user();
                            $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
                                ->select('unit_bisnis','organisasi')
                                ->first();
                        @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <div class="payslip-details">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            Employee Name
                                        </div>
                                        <div class="col-md-9">
                                           : {{$employee->nama}}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            Job position
                                        </div>
                                        <div class="col-md-9">
                                           : {{$employee->jabatan}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">Payslip {{$dataPayslip[0]['month']}} - {{$dataPayslip[0]['year']}}</h4>
                        <h6 class="text-end text-danger mb-5 pb-4">*CONFIDENTIAL</h6>
                    </div>
                </div>
                <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Earnings</th>
                                <th>Deductions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Tunjangan Struktural</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($dataEarnings['t_struktural'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Tunjangan Kinerja</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($dataEarnings['t_kinerja'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Tunjangan Alat Kerja</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($dataEarnings['t_alatkerja'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        @if($karyawanLogin->unit_bisnis === 'Kas')
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Tunjangan Makan</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($dataEarnings['t_makan'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Tunjangan Transportasi</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($dataEarnings['t_transportasi'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>BPJS Kesehatan</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($datadeductions['bpjs_ks'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>BPJS Ketenagakerjaan</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($datadeductions['bpsj_tk'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Potongan Hutang</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($datadeductions['p_hutang'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>PPH 21</span> 
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp {{ number_format($datadeductions['pph21'][0], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="container-fluid mt-5 w-100">
                    <div class="row">
                        <div class="col-md-6 ms-auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="text-bold-800">Basic Salary</td>
                                            <td class="text-bold-800 text-end text-success"> Rp. {{ number_format($dataPayslip[0]['basic_salary'], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800">Total Allowences</td>
                                            <td class="text-bold-800 text-end text-success"> Rp. {{ number_format($dataEarnings['t_allowance'][0], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800">Total Deductions</td>
                                            <td class="text-bold-800 text-end text-danger"> Rp. {{ number_format($datadeductions['t_deduction'][0], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr style="font-size: 18px; font-weight: 800;">
                                            <td class="text-bold-800">Take Home Pay</td>
                                            <td class="text-bold-800 text-end"> Rp. {{ number_format($dataPayslip[0]['net_salary'], 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid w-100">
                    <a href="javascript:;" class="btn btn-primary float-end mt-4 ms-2"><i data-feather="download" class="me-3 icon-md"></i>Download</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile -->
<div class="payslip-mobile mobile mb-6">
    <div class="row">
        <div class="col-md-12"> 
            <div class="card mb-3 custom-card2">
                <div class="card-header text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Payroll Periode {{$dataPayslip[0]['month']}} {{$dataPayslip[0]['year']}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="text-danger">*CONFIDENTIAL</h5>
                    <hr>
                    <div class="details-employee">
                        <div class="name">
                            <h5>{{$employee->nama}}</h5>
                        </div>
                        <div class="jabatan">
                            <p class="text-muted">
                            {{$employee->jabatan}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic salary -->
            <div class="card mb-3 custom-card2">
                <div class="card-header text-center">
                    <h4>Basic Salary</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Basic Salary
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['basic_salary'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <!-- Earnings -->
            <div class="card mb-3 custom-card2">
                <div class="card-header text-center">
                    <h4>Earnings</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Struktural
                        </span>
                        <span>
                            Rp {{ number_format($dataEarnings['t_struktural'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Kinerja
                        </span>
                        <span>
                            Rp {{ number_format($dataEarnings['t_kinerja'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Alat Kerja
                        </span>
                        <span>
                            Rp {{ number_format($dataEarnings['t_alatkerja'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    @if($karyawanLogin->unit_bisnis === 'Kas')
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Makan
                        </span>
                        <span>
                            Rp {{ number_format($dataEarnings['t_makan'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Transportasi
                        </span>
                        <span>
                            Rp {{ number_format($dataEarnings['t_transportasi'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="card-header text-center">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <h4>Total Earnings</h4>
                        <h4 id="totalAmount">Rp. {{ number_format($dataEarnings['t_allowance'][0], 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="card mb-3 custom-card2">
                <div class="card-header text-center">
                    <h4>Deductions</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            BPJS Kesehatan
                        </span>
                        <span>
                            Rp {{ number_format($datadeductions['bpjs_ks'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            BPJS Ketenagakerjaan
                        </span>
                        <span>
                            Rp {{ number_format($datadeductions['bpsj_tk'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Hutang
                        </span>
                        <span>
                            Rp {{ number_format($datadeductions['p_hutang'][0], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            PPH21
                        </span>
                        <span>
                            Rp {{ number_format($datadeductions['pph21'][0], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="card-header text-center">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <h4>Total Deductions</h4>
                        <h4>Rp. {{ number_format($datadeductions['t_deduction'][0], 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- THP -->
            <div class="thp-wrap text-center mb-2">
                <h5 class="text-danger mb-2">TAKE HOME PAY</h5>
                <h2> Rp. {{ number_format($dataPayslip[0]['net_salary'], 0, ',', '.') }}</h2>
            </div>
            <div class="button-download-slip mb-2">
                <a href="#" class="btn btn-primary button-biru w-100">Download Payslip </a>
            </div>
            <p class="text-muted text-center">*This is a computer generated payslip and no signature is required.</p>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <style>
        @media(min-width: 678px){
            .mobile {
                display : none;
            }

            .desktop {
                display : block;
            }
        }

        @media(max-width: 678px){
            .mobile {
                display : block;
            }

            .desktop {
                display : none;
            }
        }
    </style>
@endpush