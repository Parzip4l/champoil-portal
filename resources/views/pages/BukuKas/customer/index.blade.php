@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php 
    $user = Auth::user();
    $dataLogin = json_decode(Auth::user()->permission); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
@endphp
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
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Customer Data</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPengumuman">Tambah Data Customer</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Whatsapp</th>
                            <th>Job Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->handphone }}</td>
                            <td>{{ $data->job_status }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalPengumuman{{ $data->id }}">
                                            <i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span>
                                        </a>
                                        <form action="#" method="POST" id="delete_contact" class="contactdelete">
                                            @csrf @method('DELETE')
                                            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                <i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span>
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
<div class="modal fade" id="ModalPengumuman" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="Judul" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Konten" class="form-label">Alamat</label>
                        <textarea name="alamat" id="" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tujuan" class="form-label">Whatsapp</label>
                        <input type="number" class="form-control" name="handphone" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tujuan" class="form-label">Job Status</label>
                        <select name="job_status" id="" class="form-control">
                            <option value="TO DO">TO DO</option>
                            <option value="In Progress">In Progress</option>
                            <option value="On Review">On Review</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tambah Data Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach($customer as $data)
<div class="modal fade" id="ModalPengumuman{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Data Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Use PUT method for updates -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $data->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $data->alamat }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="handphone" class="form-label">Whatsapp</label>
                        <input type="number" class="form-control" name="handphone" value="{{ $data->handphone }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="job_status" class="form-label">Job Status</label>
                        <select name="job_status" id="" class="form-control">
                            <option value="TO DO" {{ $data->job_status == 'TO DO' ? 'selected' : '' }}>TO DO</option>
                            <option value="In Progress" {{ $data->job_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="On Review" {{ $data->job_status == 'On Review' ? 'selected' : '' }}>On Review</option>
                            <option value="Completed" {{ $data->job_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Customer</button>
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
                const deleteUrl = "{{ route('customer.destroy', ':id') }}".replace(':id', id);
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