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
            <a href="{{url('koperasi-page')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Koperasi</h5>
            </a>
        </div>
        <div class="logout-button">
            <form action="{{ route('logout') }}" method="POST" id="logout_admin">
                @csrf
                <a href="#" class="text-body ms-0 d-flex" onClick="submitForm()">
                    <h5 class="text-danger me-2 align-self-center">Log Out</h5>
                    <i class="me-2 icon-md text-danger" data-feather="log-out"></i>
                </a>
            </form>
        </div>
    </div>
</div>
<!-- Profile Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                        <div class="content-left align-self-center">
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nama }}</h5>
                            </div>
                            <div class="employee-title-job">
                                <p class="color-custom-secondary">{{ $employee->jabatan }}</p>
                            </div>
                        </div>
                        <div class="content-right">
                            <div class="gambar">
                                <img src="{{ asset('images/' . $employee->gambar) }}" alt="" class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="content-wrap-employee-card d-flex justify-content-between">
                        <div class="content-left align-self-center">
                            <div class="employee-title-job">
                                <p class="color-custom">Employee ID</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nik }}</h5>
                            </div>
                        </div>
                        <div class="content-right">
                            <div class="employee-title-job text-right color-custom">
                                <p class="color-custom">Division</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="text-uppercase color-custom-secondary">{{ $employee->organisasi }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Koperasi Page -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-body">
                <div class="title-saving">
                    <h4>Bill History</h4>
                </div>
                <hr>
                @foreach ($datasaya as $data)
                <div class="history-wrap d-flex justify-content-between mb-4">
                    <div class="item-saving">
                        <h4 class="mb-1">{{ \Carbon\Carbon::parse($data->tanggal_simpan)->translatedFormat('F') }}</h4>
                        <p class="text-muted" style="font-size: 8px;">Has been deducted automatically on the date</p>
                        <h6>{{ \Carbon\Carbon::parse($data->tanggal_simpan)->translatedFormat('d F Y') }}</h6>
                    </div>
                    <div class="nominal-data align-self-center">
                        <h3 class="text-primary">Rp {{ number_format($data->jumlah_simpanan, 0, ',', '.') }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
            @if(isset($saldosaya) && $saldosaya->sisahutang == 0)
            <div class="footer-saving p-4">
                <div class="content-footer-saving d-flex justify-content-between">
                    <div class="left-item">
                        <h5>Remaining Bill</h5>
                        @if(isset($saldosaya) && $saldosaya->sisahutang == 0)
                        <p style="font-size: 8px;">Your bill is paid, you can reapply for the loan</p>
                        @else 
                        <p style="font-size: 8px;">The bill will be deducted automatically in payroll</p>
                        @endif
                    </div>
                    <div class="rignt-item">
                        @if(isset($saldosaya))
                        <h4>Rp {{ number_format($saldosaya->sisahutang, 0, ',', '.') }}</h4>
                        @else
                        <h4>Rp 0</h4>
                        @endif
                    </div>
                </div>
            </div>
            @else 
            <div class="footer-saving bg-danger p-4">
                <div class="content-footer-saving d-flex justify-content-between">
                    <div class="left-item">
                        <h5>Remaining Bill</h5>
                        @if(isset($saldosaya) && $saldosaya->sisahutang == 0)
                        <p style="font-size: 8px;">Your bill is paid, you can reapply for the loan</p>
                        @else 
                        <p style="font-size: 8px;">The bill will be deducted automatically in payroll</p>
                        @endif
                    </div>
                    <div class="rignt-item">
                        @if(isset($saldosaya))
                        <h4>Rp {{ number_format($saldosaya->sisahutang, 0, ',', '.') }}</h4>
                        @else
                        <h4>Rp 0</h4>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/password.js') }}"></script>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const authCheck = document.getElementById("authCheck");
        const passwordInput = document.getElementById("passwordInput");
        const CurrentPass = document.getElementById("current_password");
        const passwordConfirmationInput = document.getElementById("passwordConfirmationInput");

        authCheck.addEventListener("change", function() {
            if (authCheck.checked) {
                passwordInput.type = "text";
                passwordConfirmationInput.type = "text";
                CurrentPass.type = "text";
            } else {
                passwordInput.type = "password";
                passwordConfirmationInput.type = "password";
                CurrentPass.type = "password";
            }
        });
    });
</script>
<script>
    // Fungsi untuk memeriksa apakah kotak centang telah dicentang
    function checkAgreement() {
        var agreementCheckbox = document.getElementById('agreeCheckbox');
        var registrationButton = document.getElementById('registrationBtn');
        
        // Jika kotak centang dicentang, aktifkan tombol pendaftaran
        if (agreementCheckbox.checked === true) {
            registrationButton.disabled = false;
        } else {
            // Jika kotak centang tidak dicentang, nonaktifkan tombol pendaftaran
            registrationButton.disabled = true;
        }
    }
    
    // Tambahkan event listener untuk memanggil fungsi checkAgreement saat kotak centang diubah
    document.getElementById('agreeCheckbox').addEventListener('change', checkAgreement);
</script>
<!-- Clear Cache -->
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

    document.getElementById('clear-cache-link').addEventListener('click', function(event) {
        event.preventDefault();
        fetch('/clear-cache')
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    icon: 'success', // Ganti menjadi 'success' atau 'error' berdasarkan hasil permintaan
                    title: 'Clear Cache',
                    text: data, // Menampilkan pesan hasil dalam SweetAlert
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
@endpush