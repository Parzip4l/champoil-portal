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
                <h5 class="align-self-center">Profile</h5>
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

<!-- Attendence Record -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="data-attendence-wrap d-flex justify-content-between">
                    <div class="data-item-attendence text-center">
                        <p class="mb-2 text-muted">On Time</p>
                        <h4>{{$daysWithAttendance}}</h4>
                    </div>
                    <div class="data-item-attendence text-center">
                        <p class="mb-2 text-muted">Time Off</p>
                        <h4>{{$daysWithoutAttendance}}</h4>
                    </div>
                    <div class="data-item-attendence text-center">
                        <p class="mb-2 text-muted">Leave</p>
                        <h4>{{$sakit}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Our Menus -->
<div class="row">
    <div class="col-md-12">
        <div class="menu-profile-wrap">
            <div class="menu-item-profile">
                <a href="#" class="d-flex justify-content-between menu-profile mb-3" data-bs-toggle="modal" data-bs-target=".PersonalInfo">
                    <div class="d-flex">
                        <div class="icon-profile-menu bg-custom-biru p-3 me-3">
                            <i class="icon-lg text-white" data-feather="user"></i>
                        </div>
                        <h5 class="align-self-center color-custom">Personal Info</h5>
                    </div>
                    <div class="icon-right align-self-center">
                        <i class="icon-lg color-custom align-self-center" data-feather="chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="d-flex justify-content-between menu-profile mb-3" data-bs-toggle="modal" data-bs-target=".EmployementInfo">
                    <div class="d-flex">
                        <div class="icon-profile-menu p-3 me-3" style="background: #FFB2A6;">
                            <i class="icon-lg text-white" data-feather="user-plus"></i>
                        </div>
                        <h5 class="align-self-center color-custom">Employement Info</h5>
                    </div>
                    <div class="icon-right align-self-center">
                        <i class="icon-lg color-custom align-self-center" data-feather="chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="d-flex justify-content-between menu-profile mb-3" data-bs-toggle="modal" data-bs-target=".PayrollInfo">
                    <div class="d-flex">
                        <div class="icon-profile-menu p-3 me-3" style="background: #9ADCFF;">
                            <i class="icon-lg text-white" data-feather="dollar-sign"></i>
                        </div>
                        <h5 class="align-self-center color-custom">Payroll Info</h5>
                    </div>
                    <div class="icon-right align-self-center">
                        <i class="icon-lg color-custom align-self-center" data-feather="chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="d-flex justify-content-between menu-profile mb-3" data-bs-toggle="modal" data-bs-target=".Education">
                    <div class="d-flex">
                        <div class="icon-profile-menu p-3 me-3" style="background: #FF87CA;">
                            <i class="icon-lg text-white" data-feather="book-open"></i>
                        </div>
                        <h5 class="align-self-center color-custom">Education & Experience</h5>
                    </div>
                    <div class="icon-right align-self-center">
                        <i class="icon-lg color-custom align-self-center" data-feather="chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="d-flex justify-content-between menu-profile mb-6" data-bs-toggle="modal" data-bs-target=".ChangePassword">
                    <div class="d-flex">
                        <div class="icon-profile-menu p-3 me-3" style="background: #FF7171;">
                            <i class="icon-lg text-white" data-feather="lock"></i>
                        </div>
                        <h5 class="align-self-center color-custom">Change Password</h5>
                    </div>
                    <div class="icon-right align-self-center">
                        <i class="icon-lg color-custom align-self-center" data-feather="chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Personal Info -->
<div class="modal fade bd-example-modal-lg PersonalInfo" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Personal Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="input-custom mb-2">
                    <p>NIK</p>
                    <h5>{{ $employee->ktp }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Full Name</p>
                    <h5>{{ $employee->nama }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Gender</p>
                    <h5>{{ $employee->jenis_kelamin }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Place of Birth</p>
                    <h5>{{ $employee->tempat_lahir }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Birtday</p>
                    <h5>{{ $employee->tanggal_lahir }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Address</p>
                    <h5>{{ $employee->alamat }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Change Password -->
<div class="modal fade bd-example-modal-lg ChangePassword" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#ChangePassword">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" action="{{ route('pass.update', ['id' => $employee->nik]) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Sebelumnya</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Password Sebelumnya" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password Baru" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password Baru" required>
                                <div class="text-danger" id="password-confirmation-error" style="display: none;"></div>
                                <div class="text-success" id="password-confirmation-success" style="display: none;"></div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="authCheck">
                                <label class="form-check-label" for="authCheck">
                                    Show Password
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 bg-custom-biru" style="border-radius:10px; border-color: #424874;">Change Password</button>    
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Employement Info -->
<div class="modal fade bd-example-modal-lg EmployementInfo" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Employement Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="input-custom mb-2">
                    <p>NIK</p>
                    <h5>{{ $employee->nik }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Full Name</p>
                    <h5>{{ $employee->nama }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Position</p>
                    <h5>{{ $employee->jabatan }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>Join Date</p>
                    <h5>{{ $employee->joindate }}</h5>
                </div>
                <div class="input-custom mb-2">
                    <p>End Date</p>
                    @if($employee->status_kontrak === 'Permanent')
                    <h5>-</h5>
                    @else
                    <h5>{{ $employee->berakhirkontrak }}</h5>
                    @endif
                </div>
                <div class="input-custom mb-2">
                    <p>Employement Status</p>
                    <h5>{{ $employee->status_kontrak }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Payroll Info -->
<div class="modal fade bd-example-modal-lg PayrollInfo" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Payroll Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <h5 class="text-center mb-1">Your info will show here</h5>
                <p class="text-center text-muted">Please Contact Your HR To Update Info</p>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Educational -->
<div class="modal fade bd-example-modal-lg Education" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="#PersonalInfo">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="text-center">Education & Experience Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <h5 class="text-center mb-1">Your info will show here</h5>
                <p class="text-center text-muted">Please Contact Your HR To Update Info</p>
            </div>
        </div>
    </div>
</div>
<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
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
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('password-error');
    const passwordConfirmationError = document.getElementById('password-confirmation-error');
    const passwordSuccess = document.getElementById('password-success');
    const passwordConfirmationSuccess = document.getElementById('password-confirmation-success');

    passwordInput.addEventListener('input', () => {
        if (passwordInput.value !== passwordConfirmationInput.value) {
            passwordError.style.display = 'block';
            passwordError.textContent = 'Password tidak cocok.';
        } else {
            passwordError.style.display = 'none';
            passwordError.textContent = '';
        }
    });

    passwordConfirmationInput.addEventListener('input', () => {
        if (passwordInput.value !== passwordConfirmationInput.value) {
            passwordConfirmationError.style.display = 'block';
            passwordConfirmationError.textContent = 'Password tidak cocok.';
            passwordConfirmationSuccess.style.display = 'none';
            passwordConfirmationSuccess.textContent = 'Password Cocok.';
        } else {
            passwordConfirmationError.style.display = 'none';
            passwordConfirmationError.textContent = '';
            passwordConfirmationSuccess.style.display = 'block';
            passwordConfirmationSuccess.textContent = 'Password Cocok.';
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const authCheck = document.getElementById("authCheck");
        const passwordInput = document.getElementById("password");
        const CurrentPass = document.getElementById("current_password");
        const passwordConfirmationInput = document.getElementById("password_confirmation");

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
@endpush