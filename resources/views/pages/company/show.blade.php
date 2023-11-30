@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Top Bar -->
<div class="row mb-3">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('company')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
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
                        <img src="{{ asset('images/company_logo/' . $company->logo) }}" alt="" style="width:150px; object-fit:cover;">                 
                        <h5 class="mt-5 mb-2 text-muted">{{$company->company_name}}</h5>
                        <p>{{$company->company_address}}</p>
                    </div>
                    <div class="col-lg-4 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2"></h4>
                        <h6 class="text-end text-danger mb-5 pb-4">*Company Data</h6>
                    </div>
                </div>
                <hr>
                <div class="data-company p-3">
                    <div class="col-lg-4 ps-0">
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Employee Total</h6>
                            <p>{{$employeeTotal}} Employee</p>
                        </div>
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Use Schedule For Attendence</h6>
                            <p>{{$company->use_scedule}}</p>
                        </div>
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Schedule Type</h6>
                            <p>{{$company->schedule_type}} Schedule</p>
                        </div>
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Latitude</h6>
                            <p>{{$company->latitude}}</p>
                        </div>
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Longitude</h6>
                            <p>{{$company->longitude}}</p>
                        </div>
                        <div class="data-company-details d-flex justify-content-between mb-2">
                            <h6 class="mb-2 text-muted">Allowence Radius</h6>
                            <p>{{$company->radius}} KM</p>
                        </div>
                    </div>
                </div>
                <!-- Company Statistic -->
            </div>
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