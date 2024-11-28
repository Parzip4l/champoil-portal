@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 align-self-center">Details Payroll</h5>
            </div>
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-6 ps-0">
                        <a href="#" class="noble-ui-logo d-block mt-3">CITYGUARD<span> LOGO</span></a>                 
                        <p class="mt-1 mb-1"><b>KHARISMA ADHI SEJAHTERA</b></p>
                        <p>Jl. Kapuk Kencana No.36A, RT.2/RW.3, Kapuk Muara, <br>Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14460</p>
                        <h5 class="mt-5 mb-2 text-muted">Employee Details</h5>
                        @php
                                $employee = \App\Employee::where('nik', $payroll->employee_code)->first();
                        @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <div class="payslip-details">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            Employee Name
                                        </div>
                                        <div class="col-md-9">
                                           : {{$employee->nama}}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
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
                    
                    <div class="col-lg-6 pe-0">
                        <h5 class="fw-bold text-uppercase text-end mt-4 mb-2"> 
                            {{ \Carbon\Carbon::createFromFormat('d-m-Y', explode(' - ', $payroll->periode)[0])->locale('id')->isoFormat('D MMMM') }} 
                            - 
                            {{ \Carbon\Carbon::createFromFormat('d-m-Y', explode(' - ', $payroll->periode)[1])->locale('id')->isoFormat('D MMMM YYYY') }}
                        </h5>
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
                            @php
                                $allowencesData = json_decode($payroll->allowences);
                                $deductionData = json_decode($payroll->deductions);
                                $HutangData = isset($deductionData->potongan_hutang) ? $deductionData->potongan_hutang : 0;
                                $projectAllowances = isset($allowencesData->projectAllowances) ? $allowencesData->projectAllowances : 0;
                                $projectDeductions = isset($deductionData->projectDeductions) ? $deductionData->projectDeductions : 0;
                            @endphp
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Basic Salary</span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($payroll->basic_salary ?? 0, 0, ',', '.') }} </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Backup</span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right text-muted mb-4"> {{$allowencesData->totalHariBackup ?? 0}} Hari</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($allowencesData->totalGajiBackup ?? 0, 0, ',', '.') }} </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Lembur</span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right text-muted mb-4"> {{$allowencesData->totalJamLembur ?? 0}} Jam</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($allowencesData->totalrateLembur ?? 0, 0, ',', '.') }} </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Tunjangan Lain Lain</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($allowencesData->additional_allowances ?? 0, 0, ',', '.') }} </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Potongan Absensi</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right mb-4">Rp. {{ number_format($deductionData->potongan_absen ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right text-muted"> {{$deductionData->tidak_masuk_kerja ?? 0}} Hari</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Potongan Diksar</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($deductionData->potongan_lain ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Potongan Hutang</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($deductionData->hutang_koperasi ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Iuran Anggota Koperasi</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($deductionData->iuran_koperasi ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Taxes Income</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right text-danger">Rp. {{ number_format($deductionData->PPH21 ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>Potangan Lain Lain</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($deductionData->additional_deductions ?? 0, 0, ',', '.') }}</span>
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
                                        <tr style="font-size: 18px; font-weight: 800;">
                                            <td class="text-bold-800">Take Home Pay</td>
                                            <td class="text-bold-800 text-end"> Rp. {{ number_format($payroll->thp ?? 0, 0, ',', '.') }} </td>
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
<div class="payslip-mobile mobile">
    <div class="row">
        <div class="col-md-12"> 
            <div class="card mb-3">
                <div class="card-header text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Payroll Periode </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="text-danger text-center">*CONFIDENTIAL</h5>
                    <hr>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <h5>{{$employee->nama}}</h5>
                        <span>
                            {{$employee->jabatan}}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lembur -->
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h4>Backup</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Working Days
                        </span>
                        <span>
                            {{$allowencesData->totalHariBackup ?? 0 }} Days
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Total Backup Salary
                        </span>
                        <span>
                            Rp. {{ number_format($allowencesData->totalGajiBackup ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <!-- Earnings -->
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h4>Earnings</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Gaji Pokok
                        </span>
                        <span>
                        Rp. {{ number_format($payroll->basic_salary ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Gaji Backup
                        </span>
                        <span>
                            Rp. {{ number_format($allowencesData->totalGajiBackup ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Bpjs Kesehatan
                        </span>
                        <span>
                            Rp. {{ number_format($allowencesData->bpjs_mandiri ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Jabatan
                        </span>
                        <span>
                            Rp. 0
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tunjangan Lain-Lain
                        </span>
                        <span>
                            Rp. {{ number_format($allowencesData->additional_allowances ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h4>Deductions</h4>
                </div>
                <div class="card-body">
                <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Tidak Absen
                        </span>
                        <span class="text-muted">
                            {{$deductionData->tidak_masuk_kerja ?? 0}} Hari
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Absensi
                        </span>
                        <span>
                            Rp. {{ number_format($deductionData->potongan_absen ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Diksar
                        </span>
                        <span>
                            Rp. {{ number_format($deductionData->potongan_gp ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Iuran Anggota Koperasi
                        </span>
                        <span>Rp. {{ number_format($deductionData->iuran_koperasi ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Hutang
                        </span>
                        <span>Rp. {{ number_format($deductionData->hutang_koperasi ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            PPH 21
                        </span>
                        <span>
                            Rp. {{ number_format($deductionData->PPH21 ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Lain Lain
                        </span>
                        <span>
                            Rp. {{ number_format($deductionData->additional_deductions ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- THP -->
            <div class="thp-wrap text-center mb-3">
                <h5 class="text-danger mb-2">TAKE HOME PAY</h5>
                <h2 style="font-weight:900;"> Rp. {{ number_format($payroll->thp ?? 0, 0, ',', '.') }} </h2>
            </div>
            <div class="button-download-slip mb-2">
                <a href="#" class="btn btn-primary w-100">Download Payslip </a>
            </div>
            <p class="text-muted text-center mb-6">*This is a computer generated payslip and no signature is required.</p>
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
  <script>
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('shift.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Shift Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Shift Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Shift Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
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