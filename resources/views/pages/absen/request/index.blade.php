@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Data Pengajuan Attendence</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <form method="GET" id="filterForm">
                            <label for="tanggal" class="form-label">Tanggal Range</label>
                            <input type="text" id="tanggal" name="tanggal" class="form-control flatpickr">
                        </form>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" form="filterForm" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
                <table id="dataTableExample" class="table table-striped table-bordered table-hover table-primary nowrap">
                    <thead class="table-primary">
                        <tr>
                            <th>Employee</th>
                            <th>Tanggal Diajukan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataRequest as $data)
                        <tr>
                            @php 
                                $employeename = \App\Employee::where('nik', $data->employee)->first();
                            @endphp
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#DetailPengajuan{{ $data->id}}" class="text-primary" data-bs-toggle="tooltip" title="View Details">
                                    {{ $employeename->nama }}
                                </a>
                            </td>
                            <td> {{ $data->tanggal }} </td>
                            <td> {{ $data->created_at }} </td>
                            <td> {{ $data->status }} </td>
                            <td>
                                <span class="badge {{ $data->aprrove_status === 'Approved' ? 'bg-success' : ($data->aprrove_status === 'Reject' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $data->aprrove_status }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($data->aprrove_status !== "Approved")
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('approve.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $data->id }}').submit();" data-bs-toggle="tooltip" title="Approve Request">
                                            <i data-feather="check" class="icon-sm me-2"></i>
                                            Approve
                                        </a>
                                        @endif

                                        @if ($data->aprrove_status !== "Reject")
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('reject.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('reject-usulan-form-{{ $data->id }}').submit();" data-bs-toggle="tooltip" title="Reject Request">
                                            <i data-feather="x" class="icon-sm me-2"></i>
                                            Reject
                                        </a>
                                        @endif

                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#DetailPengajuan{{ $data->id}}" data-bs-toggle="tooltip" title="View Details">
                                            <i data-feather="eye" class="icon-sm me-2"></i>
                                            Details
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')" data-bs-toggle="tooltip" title="Delete Request">
                                            <i data-feather="trash" class="icon-sm me-2"></i>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <style>
                    #dataTableExample {
                        font-size: 0.9rem;
                    }
                    #dataTableExample thead th {
                        text-align: center;
                        background-color: #007bff; /* Primary color */
                        color: white;
                    }
                    #dataTableExample tbody td {
                        vertical-align: middle;
                    }
                    #dataTableExample tbody tr:hover {
                        background-color: #cce5ff; /* Light primary color for hover */
                    }
                    .dropdown-menu a:hover {
                        background-color: #007bff;
                        color: white;
                    }
                    .btn-outline-primary {
                        border-color: #007bff;
                        color: #007bff;
                    }
                    .btn-outline-primary:hover {
                        background-color: #007bff;
                        color: white;
                    }
                </style>
                <script>
                    $(document).ready(function() {
                        // Initialize Flatpickr for date range
                        $('#tanggal').flatpickr({
                            mode: 'range',
                            dateFormat: 'Y-m-d'
                        });

                        // Auto-fill "tanggal" field if the URL contains a "tanggal" query parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const tanggalParam = urlParams.get('tanggal');
                        if (tanggalParam) {
                            $('#tanggal').val(tanggalParam);
                        }

                        // Initialize DataTables with search functionality
                        $('#dataTableExample').DataTable({
                            responsive: true,
                            language: {
                                search: "Search:",
                                lengthMenu: "Show _MENU_ entries",
                                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                paginate: {
                                    previous: "Previous",
                                    next: "Next"
                                }
                            },
                            dom: '<"d-flex justify-content-between"lf>t<"d-flex justify-content-between"ip>'
                        });

                        // Initialize tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Modal Details -->
@foreach ($dataRequest as $data)
@php 
    $employeename = \App\Employee::where('nik', $data->employee)->first();
@endphp
<div class="modal fade" id="DetailPengajuan{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Details Pengajuan {{$employeename->nama}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="" class="form-label">Employee</label>
                    <input type="text" class="form-control" value="{{$employeename->nama}}">
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Request Type</label>
                    <input type="text" class="form-control" value="{{$data->status}}">
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Tanggal</label>
                    <input type="text" class="form-control" value="{{$data->tanggal}}">
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Reason</label>
                    <textarea name="" id="" class="form-control">{{$data->alasan}}</textarea>
                </div>
                <div class="form-group mb-2">
                    <a href="{{ route('dokumen.download', $data->id) }}" class="btn btn-primary w-100">Download Attachment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- End Modal -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    $(document).ready(function() {
        // Initialize Flatpickr for date range
        $('#tanggal').flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d'
        });

        // Auto-fill "tanggal" field if the URL contains a "tanggal" query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tanggalParam = urlParams.get('tanggal');
        if (tanggalParam) {
            $('#tanggal').val(tanggalParam);
        }

        // Initialize DataTables with search functionality
        $('#dataTableExample').DataTable({
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            },
            dom: '<"d-flex justify-content-between"lf>t<"d-flex justify-content-between"ip>'
        });
    });
  </script>
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
                const deleteUrl = "#".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Data Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Data Failed to Delete',
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
@endpush