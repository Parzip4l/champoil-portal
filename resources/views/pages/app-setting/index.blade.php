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
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Apps Settings</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="" class="text-center link-settings" data-bs-target="#usersModal" data-bs-toggle="modal">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Company Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('users') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="user"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>User Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('request-type') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="calendar"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Attendence Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('project.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="briefcase"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Project Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('additional-component') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="dollar-sign"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Payroll Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('garda-pratama') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="file-text"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Certification Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('thr-component') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="sun"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>THR Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4 mb">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('koperasi') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="package"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Koperasi</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('emergency-data.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="alert-circle"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Panic Button</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('activities') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="radio"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Activities</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  mb-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('version') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="database"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Apps Versions</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('birthdays-messages') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="message-square"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Birthdays Messages</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('features-management.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Features Setting</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('roles.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Roles Setting</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('payroll.cutoff.edit') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="calendar"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Payroll Setting</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Company Modal Settings -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Company Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('company') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Company Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('organisasi') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Organisasi Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ url('divisi') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Division Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('jabatan.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Position Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('golongan.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Golongan Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('pajak.index') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>PPH Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @php
                        $user = Auth::user();
                        $nik = $user->employee_code;

                        $karyawanLogin = \App\Employee::where('nik', $nik)->select('unit_bisnis','organisasi')->first();
                        $company = \App\Company\CompanyModel::where('company_name', $karyawanLogin->unit_bisnis)->value('id');
                    @endphp
                    <div class="col-md-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                            <a href="{{ route('company.shifts.index', ['company' => $company]) }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Shift Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('company.schedules.index', ['company' => $company]) }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Schedule Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card custom-card2">
                            <div class="card-body">
                                <a href="{{ route('company.work-locations.index', ['company' => $company]) }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Multi Location Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Settings -->
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
    a.link-settings {
        color : #555!important;
    }
  </style>
@endpush