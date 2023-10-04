@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-6 ps-0">
                        <a href="#" class="noble-ui-logo d-block mt-3">CHAMPOIL<span> LOGO</span></a>                 
                        <p class="mt-1 mb-1"><b>CHAMPOIL INDONESIA</b></p>
                        <p>Jl. Kapuk Kencana No.36A, RT.2/RW.3, Kapuk Muara, <br>Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14460</p>
                        <h5 class="mt-5 mb-2 text-muted">Employee Details</h5>
                        @php
                                $dates = explode(' - ', $dataPayslip[0]['periode']);
                                $startDate = date('j M Y', strtotime($dates[0]));
                                $endDate = date('j M Y', strtotime($dates[1]));
                            $employee = \App\Employee::where('nik', $dataPayslip[0]['employee_code'])->first();
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
                    <div class="col-lg-6 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">Payslip {{$endDate}}</h4>
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
                                                <span>Daily Salary</span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($dataPayslip[0]['daily_salary'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right text-muted mb-4">{{ $dataPayslip[0]['total_absen'] }} Hari</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($dataPayslip[0]['total_daily'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Lembur</span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($dataPayslip[0]['lembur_salary'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right text-muted mb-4">{{ $dataPayslip[0]['jam_lembur'] }} Jam</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span></span>
                                            </div>
                                            <div class="col-md-6 text-right mb-2">
                                                <span class="text-right mb-4">Rp. {{ number_format($dataPayslip[0]['total_lembur'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Uang Makan</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($dataPayslip[0]['uang_makan'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Uang Kerajinan</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($dataPayslip[0]['uang_kerajinan'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Potongan Hutang</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($dataPayslip[0]['potongan_hutang'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Potongan Mess</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($dataPayslip[0]['potongan_mess'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <span>Potongan Lain Lain</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span class="text-right">Rp. {{ number_format($dataPayslip[0]['potongan_lain'], 0, ',', '.') }}</span>
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
                                            <td class="text-bold-800">Total Allowences</td>
                                            <td class="text-bold-800 text-end text-success"> Rp. {{ number_format($totalallowence, 0, ',', '.') }} </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800">Total Deductions</td>
                                            <td class="text-bold-800 text-end text-danger"> Rp. {{ number_format($totalDeductions, 0, ',', '.') }} </td>
                                        </tr>
                                        <tr style="font-size: 18px; font-weight: 800;">
                                            <td class="text-bold-800">Take Home Pay</td>
                                            <td class="text-bold-800 text-end"> Rp. {{ number_format($dataPayslip[0]['thp'], 0, ',', '.') }}</td>
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

            <!-- Basic salary -->
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h4>Daily Salary</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Daily Salary
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['daily_salary'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Works Days
                        </span>
                        <span>
                            {{$dataPayslip[0]['total_absen']}} Days
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lembur -->
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h4>Overtime</h4>
                </div>
                <div class="card-body">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Overtime / Hours
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['lembur_salary'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Overtime
                        </span>
                        <span>
                            {{$dataPayslip[0]['jam_lembur']}} Hours
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
                            Daily Salary
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['total_daily'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Overtime
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['total_lembur'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Uang Makan
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['uang_makan'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Uang Kerajinan
                        </span>
                        <span>
                        Rp. {{ number_format($dataPayslip[0]['uang_kerajinan'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="card-header text-center">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <h4>Total Earnings</h4>
                        <h4 id="totalAmount">Rp. {{ number_format($totalallowence, 0, ',', '.') }}</h4>
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
                            Potongan Mess
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['potongan_mess'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Hutang
                        </span>
                        <span>
                        Rp. {{ number_format($dataPayslip[0]['potongan_hutang'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <span>
                            Potongan Lain-Lain
                        </span>
                        <span>
                            Rp. {{ number_format($dataPayslip[0]['potongan_lain'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="card-header text-center">
                    <div class="details-earning d-flex justify-content-between mb-2">
                        <h4>Total Deductions</h4>
                        <h4>Rp. {{ number_format($totalDeductions, 0, ',', '.') }} </h4>
                    </div>
                </div>
            </div>

            <!-- THP -->
            <div class="thp-wrap text-center mb-3">
                <h5 class="text-danger mb-2">TAKE HOME PAY</h5>
                <h2 style="font-weight:900;"> Rp. {{ number_format($dataPayslip[0]['thp'], 0, ',', '.') }} </h2>
            </div>
            <div class="button-download-slip mb-2">
                <a href="#" class="btn btn-primary w-100">Download Payslip </a>
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
                const deleteUrl = "{{ route('contact.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Contact Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Contact Failed to Delete',
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