<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="TRUEST HRIS Apps">
	<meta name="author" content="Rinable Creative">
	<meta name="keywords" content="superapp, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, laravel, theme, front-end, ui kit, web">

    <title>TRUEST - HRIS Apps</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>

<body>
<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-6 ps-0">
                        <a href="#" class="noble-ui-logo d-block mt-3">CHAMPOIL<span> LOGO</span></a>                 
                        <p class="mt-1 mb-1"><b>CHAMPOIL INDONESIA</b></p>
                        <p>Jl. Kapuk Kencana No.36A, RT.2/RW.3, Kapuk Muara, <br>Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14460</p>
                        <h5 class="mt-5 mb-2 text-muted">Payslip Details</h5>
                        @php
                            $employee = \App\Employee::where('nik', $dataPayslip[0]['employee_code'])->first();
                            $dataEarnings = json_decode($dataPayslip[0]['allowances'], true);
                            $datadeductions = json_decode($dataPayslip[0]['deductions'], true);
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
</body>
</html>