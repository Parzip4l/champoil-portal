@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row mb-4">
            <div class="topbar-wrap d-flex justify-content-between">
                <div class="arrow-back">
                    <a href="{{url('payslip')}}" class="d-flex color-custom">
                        <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                        <h5 class="align-self-center">Kembali</h5>
                    </a>
                </div>
            </div>
        </div>
        <div class="card custom-card2">
            <div class="card-header mb-3">
                <h5>Payroll Periode {{$month}}</h5>
            </div>
            <div class="card-body">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Thp</th>
                            <th>Payroll Period</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $no = 1;
                        @endphp
                        @foreach ($data as $payslip)
                            <tr>
                                <td>{{ $no++ }}</td>
                                @php
                                    $employee = \App\Employee::where('nik', $payslip->employee_code)->first();
                                @endphp
                                <td>
                                    @if ($employee)
                                        <a href="{{ route('payslip.show', $payslip->id) }}">{{ $employee->nama }}</a>
                                    @else
                                        Employee not found
                                    @endif
                                </td>
                                <td>Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                                <td>{{ $payslip->year }} - {{ $payslip->month }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('payslip.edit', $payslip->id) }}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                                            @if ($employee)
                                                <a href="{{ route('send-email', $payslip->id) }}" class="dropdown-item d-flex align-items-center" onclick="return showSweetAlert()"><i data-feather="mail" class="icon-sm me-2"></i> <span class="">Send Email</span></a>
                                            @else
                                                Employee not found
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
<script>
    $(function() {
    'use strict';

    $(function() {
        $('#dataPayslipNS').DataTable({
        "aLengthMenu": [
            [10, 30, 50, -1],
            [10, 30, 50, "All"]
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        }
        });
        $('#dataTableExample').each(function() {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.removeClass('form-control-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
        });
    });

    });
</script>
<script>
    // Function to show SweetAlert confirmation
    function showSweetAlert() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to send an email?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user clicks "Yes," continue with the default link behavior
                return true;
            } else {
                // If user clicks "No" or closes the dialog, prevent the default link behavior
                return false;
            }
        });
    }
</script>
@endpush