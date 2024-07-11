@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
<div class="row mb-4">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{route('setting.pa')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Kategori PA</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalFaktor">Buat Faktor</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Bobot Nilai</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($faktor as $data)
                        <tr>
                            <td>{{$data->name}}</td>
                            <td>{{$data->deskripsi}}</td>
                            <td>{{$data->kategori}}</td>
                            <td>{{$data->bobot_nilai}}</td>
                            <td>{{$data->level}}</td>
                            <td>
                                <div class="dropdown"> 
                                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalFaktor{{$data->id}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="{{route('faktor-pa.duplikat', $data->id)}}"><i data-feather="copy" class="icon-sm me-2"></i> <span class="">Duplikat</span></a>
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
<!-- Modal kategori -->
<div class="modal fade" id="ModalFaktor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Faktor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('faktor-pa.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Kualitas Kerja" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="" placeholder="Deskripsi Penilaian" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Kategori</label>
                        <select name="kategori" class="form-control" id="" required>
                            @foreach($kategori as $data)
                                <option value="{{$data->name}}">{{$data->name}} ({{$data->level}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Bobot Nilai</label>
                        <input type="text" name="bobot_nilai" class="form-control" placeholder="60" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Konten" class="form-label">Level</label>
                        <select name="level" class="form-control" id="" required>
                            @foreach($level as $data)
                                <option value="{{$data->name}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buat Faktor</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Kategori -->

 <!-- Modal Edit kategori -->
@foreach($faktor as $FaktorPA)
<div class="modal fade" id="ModalFaktor{{$FaktorPA->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalFaktor">Update Faktor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('faktor-pa.update', $FaktorPA->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Hasil Kerja" value="{{ $FaktorPA->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="" required>{{ $FaktorPA->deskripsi }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Kategori</label>
                        <select name="kategori" class="form-control" id="" required>
                            @foreach($kategori as $data)
                                <option value="{{$data->name}}" {{ $FaktorPA->kategori == $data->name ? 'selected' : '' }}>{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Bobot Nilai</label>
                        <input type="text" name="bobot_nilai" class="form-control" placeholder="Hasil Kerja" value="{{ $FaktorPA->bobot_nilai }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Konten" class="form-label">Level</label>
                        <select name="level" class="form-control" id="" required>
                            @foreach($level as $dataLevel)
                                <option value="{{$dataLevel->name}}" @if($dataLevel->name == $data->level) selected @endif>{{$dataLevel->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Faktor</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- End Modal Kategori -->
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
                const deleteUrl = "{{ route('faktor-pa.destroy', ':id') }}".replace(':id', id);
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
@endpush