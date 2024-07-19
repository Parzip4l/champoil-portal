@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Emergency Data</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample2" class="table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Location</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($emergency as $data)
                            <tr>
                                <td>{{$data->user}}</td>
                                <td>{{$data->latitude}},{{$data->longitude}}</td>
                                <td>{{$data->category}}</td>
                                <td id="status-{{ $data->id }}">
                                @if($data->status == 'Done')
                                    <span class="badge rounded-pill bg-success">{{ $data->status }}</span>
                                @elseif($data->status == 'Pending')
                                    <span class="badge rounded-pill bg-warning">{{ $data->status }}</span>
                                @elseif($data->status == 'On Progress')
                                    <span class="badge rounded-pill bg-primary">{{ $data->status }}</span>
                                @elseif($data->status == 'Cancel')
                                    <span class="badge rounded-pill bg-danger">{{ $data->status }}</span>
                                @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item d-flex align-items-center status-btn" data-id="{{ $data->id }}" data-status="On Progress">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span>On Progress</span>  
                                            </button>
                                            <button class="dropdown-item d-flex align-items-center status-btn" data-id="{{ $data->id }}" data-status="Done">
                                                <i data-feather="check-circle" class="icon-sm me-2"></i>
                                                <span>Done</span>  
                                            </button>
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalAssets{{ $data->id}}">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span>Edit</span>  
                                            </a>
                                            <form action="{{ route('asset.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE') 
                                                <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                    <i data-feather="trash" class="icon-sm me-2"></i>
                                                    <span>Delete</span>
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
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Emergency Category</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="#" class="btn btn-primary" data-bs-target="#ModalEmergency" data-bs-toggle="modal">Add Category</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category as $data)
                            <tr>
                                <td>{{$data->name}}</td> 
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalEmergency{{ $data->id}}">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>  
                                            </a>
                                            <form action="{{ route('asset.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
</div>

<!-- Modal Tambah Category -->
<div class="modal fade" id="ModalEmergency" tabindex="-1" aria-labelledby="CompanyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CompanyModal">Tambah Data Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('emergency-category.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" placholder="Kebakaran" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit category -->
 @foreach($category as $data)
<div class="modal fade" id="ModalEmergency{{$data->id}}" tabindex="-1" aria-labelledby="CompanyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CompanyModal">Edit Data Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('emergency-category.update', $data->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" placholder="Kebakaran" value="{{$data->name}}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
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

<!-- Delete Company -->
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
                const deleteUrl = "{{ route('emergency-category.update', ':id') }}".replace(':id', id);
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
                        title: 'Data Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
<script>
    $(function() {
  'use strict';

  $(function() {
    $('#dataTableExample2').DataTable({
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

<!-- Update Status -->
<script>
document.querySelectorAll('.status-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var status = this.getAttribute('data-status');

        fetch(`/emergency/update-status/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`status-${id}`).innerText = status;
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Status updated successfully',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update status',
                });
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred',
            });
        });
    });
});
</script>

@endpush