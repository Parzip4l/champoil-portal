@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- topbar -->
@php 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
@endphp
<div class="card custom-card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                <div class="content-left align-self-center">
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->nama }}</h5>
                    </div>
                    <div class="employee-title-job">
                        <p>{{ $employee->jabatan }}</p>
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
                        <p>Employee ID</p>
                    </div>
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->nik }}</h5>
                    </div>
                </div>
                <div class="content-right">
                    <div class="employee-title-job text-right">
                        <p>Division</p>
                    </div>
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->organisasi }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payslip Code</th>
                                <th>Payroll Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                                
                            @endphp
                            @foreach ($payslips as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                @php
                                    $employee = \App\Employee::where('nik', $data->employee_code)->first();
                                @endphp
                                @if($employee->organisasi === 'Management Leaders')
                                <td><a href="{{route('payslip.show', $data->id)}}">{{ $data->employee_code }}</a></td>
                                <td>{{ $data->month }} - {{ $data->year }}</td>
                                @endif
                                @if($employee->organisasi === 'Frontline Officer')
                                <td><a href="{{route('payslip-ns.show', $data->id)}}">{{ $data->employee_code }}</a></td>
                                <td>{{ $data->month }} - {{ $data->year }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Show -->
<div class="row mobile">
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
            @foreach ($payslips as $data)
                @php
                    $employee = \App\Employee::where('nik', $data->employee_code)->first();
                @endphp
                @if($employee->organisasi === 'Management Leaders')
                <a href="{{route('payslip.show', $data->id)}}" class="mb-3">
                    <div class="payslip-wrap d-flex">
                        <div class="icon-wrap-slip me-3">
                            <i class="icon-lg text-white" data-feather="file-text"></i>
                        </div>
                        <div class="payslip-info align-self-center">
                            <h5 class="color-custom mb-1">Periode {{ $data->month }} {{ $data->year }}</h5>
                            <p class="text-muted">{{ $data->employee_code }}</p>
                        </div>
                    </div>
                </a>
                @endif
                @if($employee->organisasi === 'Frontline Officer')
                <a href="{{route('payslip-ns.show', $data->id)}}">
                    <div class="payslip-wrap d-flex">
                        <div class="icon-wrap-slip me-2">
                            <i class="icon-lg text-white" data-feather="file-text"></i>
                        </div>
                        <div class="payslip-info align-self-center">
                            <h5 class="color-custom mb-1">Periode {{ $data->month }} {{ $data->year }}</h5>
                            <p class="text-muted">{{ $data->employee_code }}</p>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
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
        $(function() {
    'use strict'

    if ($(".js-example-basic-single").length) {
        $(".js-example-basic-single").select2();
    }
    if ($(".js-example-basic-multiple").length) {
        $(".js-example-basic-multiple").select2();
    }
    });
    </script>
@endpush