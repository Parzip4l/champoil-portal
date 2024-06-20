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
                <table id="dataTableExample" class="table table-striped nowrap">
                    <thead>
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
                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#DetailPengajuan{{ $data->id}}">{{ $employeename->nama }}</a></td>
                            <td> {{ $data->created_at }} </td>
                            <td> {{ $data->tanggal }} </td>
                            <td> {{ $data->status }} </td>
                            <td>    {{$data->aprrove_status}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($data->aprrove_status !=="Approved")
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('approve.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $data->id }}').submit();">
                                            <i data-feather="check" class="icon-sm me-2"></i>
                                            <span class="">Approve</span>
                                        </a>
                                        @endif

                                        @if ($data->aprrove_status !=="Reject")
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('reject.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('reject-usulan-form-{{ $data->id }}').submit();">
                                            <i data-feather="x" class="icon-sm me-2"></i>
                                            <span class="">Reject</span>
                                        </a>
                                        @endif

                                        <!-- Form Approved -->
                                        <form id="setujui-usulan-form-{{ $data->id }}" action="{{ route('approve.request', $data->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <!-- Form Reject -->
                                        <form id="reject-usulan-form-{{ $data->id }}" action="{{ route('reject.request', $data->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>

                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#DetailPengajuan{{ $data->id}}">
                                            <i data-feather="eye" class="icon-sm me-2"></i>
                                            <span class="">Details</span>
                                        </a>
                                        <form action="#" method="POST" id="delete_contact" class="contactdelete"> 
                                            @csrf @method('DELETE') 
                                            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                <i data-feather="trash" class="icon-sm me-2"></i>
                                                <span class="">Delete</span>
                                            </a>
                                        </form>
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
    if ($.fn.DataTable.isDataTable('#dataTableExample')) {
        $('#dataTableExample').DataTable().destroy();
    }
    
    $('#dataTableExample').DataTable({
        "order": [[0, 'desc']], // Mengurutkan berdasarkan kolom pertama (tanggal) secara menurun
        "columnDefs": [
            { "type": "date", "targets": 0 } // Mengatur tipe kolom pertama sebagai tanggal
        ]
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