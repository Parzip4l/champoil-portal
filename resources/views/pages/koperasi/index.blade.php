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
            <a href="{{url('dashboard')}}" class="d-flex color-custom">
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
            @if(is_null($datasaya))
                <div class="persayratan-wrap">
                    <div class="title mb-4">
                        <h4>Terms & Conditions</h4>
                    </div>
                    <div class="terms-container" style="max-height: 300px; overflow-y: auto;">
                        <div class="body-terms">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.<br>

                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem<br>

                                Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?<br>

                                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.<br>
                            </p>
                        </div>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="agreeCheckbox">
                        <label class="form-check-label" for="agreeCheckbox">
                            i agree with term & conditions
                        </label>
                    </div>
                    <!-- Tombol pendaftaran -->
                    <form action="{{route('koperasi-page.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" class="d-none" name="nama" value="{{$employee->nama}}">
                        <input type="text" class="d-none" name="employee_code" value="{{$employee->nik}}">
                        <input type="text" class="d-none" name="company" value="{{$employee->unit_bisnis}}">

                        <button type="submit" id="registrationBtn" class="btn btn-primary mt-3 w-100" disabled>Register of Member </button>
                    </form>
                </div>
            </div>
            @elseif($datasaya->member_status == 'review')
                <button class="btn btn-primary w-100" disabled>On Reviewed</button>
                <p class="mt-2 color-custom-secondary">*The review process takes a maximum of 2 working days</p>
            @elseif($datasaya->member_status == 'active')
                <div class="logo-koperasi mb-4">
                    <img src="{{ url('assets/images/logo/logodesktop.png') }}" alt="" style="max-width : 40%; width : 100%;">
                </div>
                <div class="wallet-body">
                    <div class="title mb-2">
                        <h5>My Saving</h5>
                    </div>
                    <div class="saldo-saya mb-2">
                        <h3 class="color-custom-secondary">Rp. 500.000</h3>
                        <p class="color-custom-secondary" style="font-size : 8px; padding-right : 10px;">Saving will be deducted automatically in payroll</p>
                    </div>
                    <div class="bottom-data">
                        <div class="saving-data align-self-center">
                            
                        </div>
                       <div class="history-saving">
                            <a href="" class="btn btn-primary w-100">Saving History</a>
                       </div>
                    </div>
                </div>
                @if($datasaya->loan_status == 'onloan')
                <hr>
                <div class="wallet-body">
                    <div class="title mb-2">
                        <h5>Remaining Debt</h5>
                    </div>
                    <div class="saldo-saya mb-2">
                        <h3 class="color-custom-secondary">Rp. 2.500.000</h3>
                        <p class="color-custom-secondary" style="font-size : 8px; padding-right : 10px;">The bill will be deducted automatically in payroll</p>
                    </div>
                    <div class="bottom-data">
                       <div class="history-saving">
                            <a href="" class="btn btn-primary w-100">Billing History</a>
                       </div>
                    </div>
                </div>
                @else 
                <div class="persyaratan-pinjaman-wrap">
                    <h5 class="mb-3">Persyaratan Pinjaman :</h5>
                    <div class="item-pesyratan d-flex justify-content-between mb-2">
                        <p style="width: 70%">Merupakan Anggota Koperasi dengan minimal 3 Bulan.</p>
                        <div class="icon-syarat align-self-center">
                            <img src="{{ url('assets/images/logo/ceklis.png') }}" alt="" style="max-width : 100%; width : 100%;">
                        </div>
                    </div>
                    <div class="item-pesyratan d-flex justify-content-between mb-2">
                        <p style="width: 70%">Tidak Dalam Masa Cicilan Pinjaman Sebelumnya.</p>
                        <div class="icon-syarat align-self-center">
                            <img src="{{ url('assets/images/logo/ceklis.png') }}" alt="" style="max-width : 100%; width : 100%;">
                        </div>
                    </div>
                    <div class="item-pesyratan d-flex justify-content-between mb-2">
                        <p style="width: 70%">Absensi Periode Sebelumnya 100% Kehadiran.</p>
                        <div class="icon-syarat align-self-center">
                            <img src="{{ url('assets/images/logo/cakra.png') }}" alt="" style="max-width : 100%; width : 100%;">
                        </div>
                    </div>
                    <div class="button-pinjaman mt-2">
                        <button class="btn btn-primary w-100" disabled>Ajukan Pinjaman</button>
                    </div>
                </div>
                @endif
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