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
                        <h6 class="card-title mb-0">Performance Appraisal Settings</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{route('faktor-pa.setting')}}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Faktor Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('kategori-pa.setting') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Kategori Settings</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('predikat-pa.setting') }}" class="text-center link-settings">
                                    <div class="icon-menu-settings mb-2">
                                        <i data-feather="settings"></i> 
                                    </div>
                                    <div class="title-menu">
                                        <h6>Predikat Settings</h6>
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