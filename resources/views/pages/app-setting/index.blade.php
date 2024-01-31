@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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
                    <div class="col">
                        <div class="card">
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