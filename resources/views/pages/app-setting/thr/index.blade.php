@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">THR Component</h5>
                <div class="button-data">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ComponentModal">Create Component</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($data as $data)
                            <tr>
                                <td> {{$nomor++}} </td>
                                <td> {{ $data->front_text }} </td>
                                <td> {{ $data->type }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ComponentModal{{ $data->id}}">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>
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
</div>
<!-- End -->

<!-- Modal Tambah Component -->
<div class="modal fade bd-example-modal-xl" id="ComponentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('thr-component.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Title</label>
                            <input type="text" class="form-control" name="front_text" placeholder="eg Gaji Pokok" required>  
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="eg gaji_pokok" required>   
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Type</label>
                            <select name="type" class="form-control" id="" required>
                                <option value="Allowance">Allowance</option>
                                <option value="Deduction">Deduction</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal Edit -->
@foreach($update as $editData)
<div class="modal fade bd-example-modal-xl" id="ComponentModal{{$editData->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('thr-component.update', $editData->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Title</label>
                            <input type="text" class="form-control" name="front_text" placeholder="eg Gaji Pokok" value="{{$editData->front_text}}" required>  
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="eg gaji_pokok" value="{{$editData->name}}" required>   
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Type</label>
                            <select name="type" class="form-control" id="" required>
                                <option value="Allowance" {{ $editData->type == 'Allowance' ? 'selected' : '' }}>Allowance</option>
                                <option value="Deduction" {{ $editData->type == 'Deduction' ? 'selected' : '' }}>Deduction</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-primary w-100" type="submit">Update Data</button>
                    </div>
                </form>
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
                const deleteUrl = "{{ route('thr-component.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Component Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Component Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Component Failed to Delete',
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