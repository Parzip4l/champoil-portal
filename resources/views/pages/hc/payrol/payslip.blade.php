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
            <div class="card-header mb-3">
                <h5>Payrol History</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Management Leaders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Frontline Officer</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="lineTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Total</th>
                                        <th>Payroll Status</th>
                                        <th>Payslip Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $dataBulan)
                                    <tr>
                                        <td><a href="{{ route('payslip.showByMonth', ['month' => $dataBulan->month, 'year' => $dataBulan->year]) }}">{{$dataBulan->month}} / {{$dataBulan->year}}</a></td>
                                        <td>Rp {{ number_format($dataBulan->total_net_salary, 0, ',', '.') }}</td>
                                        <td>
                                        @if($dataBulan->payrol_status === 'Locked')
                                            <span class="text-danger">{{$dataBulan->payrol_status}}</span>
                                        @else
                                            <span class="text-success">{{$dataBulan->payrol_status}}</span>
                                        @endif
                                        </td>
                                        <td>
                                        @if($dataBulan->payslip_status === 'Published')
                                            <span class="text-success">{{$dataBulan->payslip_status}}</span>
                                        @else
                                            <span class="text-danger">{{$dataBulan->payslip_status}}</span>
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if($dataBulan->payrol_status === 'Locked')
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('unlockPayroll', ['month' => $dataBulan->month, 'year' => $dataBulan->year]) }}">
                                                            <i data-feather="unlock" class="icon-sm me-2"></i> <span class="">Unlock Payrol</span>
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('lockPayroll', ['month' => $dataBulan->month, 'year' => $dataBulan->year]) }}">
                                                            <i data-feather="lock" class="icon-sm me-2"></i> <span class="">Lock Payrol</span>
                                                        </a>
                                                    @endif

                                                    @if($dataBulan->payslip_status === 'Published')
                                                        <a href="{{ route('UnpublishPayslip', ['month' => $dataBulan->month, 'year' => $dataBulan->year]) }}" class="dropdown-item d-flex align-items-center" aria-disabled="true">
                                                            <i data-feather="slash" class="icon-sm me-2"></i> <span class="">Unpublish Payslip</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('PublishPayslipData', ['month' => $dataBulan->month, 'year' => $dataBulan->year]) }}" class="dropdown-item d-flex align-items-center">
                                                            <i data-feather="send" class="icon-sm me-2"></i> <span class="">Publish Payslip</span>
                                                        </a>
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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">
                        <div class="table-responsive">
                            <table id="dataPayslipNS" class="table">
                                <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Total Payrol</th>
                                    <th>Payroll Status</th>
                                    <th>Payslip Status</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datans as $data)
                                    @php 
                                        $dateParts = explode(" - ", $data->periode);
                                        $startDate = \Carbon\Carbon::parse($dateParts[0])->format('j F Y');
                                        $endDate = \Carbon\Carbon::parse($dateParts[1])->format('j F Y');
                                    @endphp
                                    <tr>
                                        <td><a href="{{ route('payslip.showbyperiode', ['periode' => $data->periode]) }}">{{ \Carbon\Carbon::parse($dateParts[1])->format('j F Y') }}</a></td>
                                        <td>Rp {{ number_format($data->total_payroll, 0, ',', '.') }}</td>
                                        <td>
                                        @if($data->payrol_status === 'Locked')
                                            <span class="text-danger">{{$data->payrol_status}}</span>
                                        @else
                                            <span class="text-success">{{$data->payrol_status}}</span>
                                        @endif
                                        </td>
                                        <td>
                                        @if($data->payslip_status === 'Published')
                                            <span class="text-success">{{$data->payslip_status}}</span>
                                        @else
                                            <span class="text-danger">{{$data->payslip_status}}</span>
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if($data->payrol_status === 'Locked')
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('unlockPayrollns', ['periode' => $data->periode]) }}">
                                                            <i data-feather="unlock" class="icon-sm me-2"></i> <span class="">Unlock Payrol</span>
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('lockPayrollns', ['periode' => $data->periode]) }}">
                                                            <i data-feather="lock" class="icon-sm me-2"></i> <span class="">Lock Payrol</span>
                                                        </a>
                                                    @endif

                                                    @if($data->payslip_status === 'Published')
                                                        <a href="{{ route('UnpublishPayslipns', ['periode' => $data->periode]) }}" class="dropdown-item d-flex align-items-center" aria-disabled="true">
                                                            <i data-feather="slash" class="icon-sm me-2"></i> <span class="">Unpublish Payslip</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('PublishPayslipDataNS', ['periode' => $data->periode]) }}" class="dropdown-item d-flex align-items-center">
                                                            <i data-feather="send" class="icon-sm me-2"></i> <span class="">Publish Payslip</span>
                                                        </a>
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
                const deleteUrl = "{{ route('employee.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Employee Successfully Deleted',
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