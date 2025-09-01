@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <!-- Folder Bagan -->
    <div class="col-md-4 mb-2">
        <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPengumuman"><i data-feather="plus" class="icon-sm me-2"></i>Create Folder</a>
    </div>
    
    <hr>
    <div class="header-card-folder mb-2 d-flex justify-content-between">
        <h5 class="align-self-center">Folder</h5>
        <form action="{{ route('folders.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search folders..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i data-feather="search" class="icon-sm me-2"></i>Search</button>
            </div>
        </form>
    </div>
    @foreach($folders as $folder)
        <div class="col-md-3 mb-4">
            <div class="card custom-card2">
                <div class="card-body d-flex justify-content-between">
                    <div class="folder">
                        <a href="{{ route('folders.show', $folder->id) }}" class="d-flex">
                            <i data-feather="folder" class="icon-xl me-2"></i>
                            <p class="text-muted align-self-center ">{{ $folder->name }}</p>
                        </a>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalFolder{{ $folder->id }}">
                                <i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Rename</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $folder->id }}')">
                                <i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="details-folder d-flex justify-content-between">
                        <p class="text-muted">{{ $fileCounts[$folder->id] ?? 0 }} files</p>
                        <p class="text-muted">{{ $folder->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="header-card-folder mb-2 d-flex justify-content-between mt-2">
        <h5>Recent Files</h5>
    </div>
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Folder</th>
                                <th>Upload By</th>
                                <th>Upload Date</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFile as $data)
                            @php 
                                $karyawan = \App\Employee::where('nik',$data->uploader)->first();
                                $folder = \App\Document\FolderModel::where('id', $data->folder_id)->first();
                            @endphp
                            <tr>
                                <td>{{$data->name}}</td> 
                                <td>{{$folder->name}}</td> 
                                <td>{{$karyawan->nama}}</td>
                                <td>{{$data->created_at->format('d M Y')}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('file.download', $data->id)}}">
                                                <i data-feather="download-cloud" class="icon-sm me-2"></i> <span class="">Download</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialogFiles('{{ $data->id }}')">
                                                <i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span>
                                            </a>
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
<!-- Modal -->
 
<div class="modal fade" id="ModalPengumuman" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Folder Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('folders.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="Judul" class="form-label">Folder Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buat Folder Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rename -->
@foreach($folders as $folder)
<div class="modal fade" id="ModalFolder{{$folder->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rename Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('folders.update', $folder->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="Judul" class="form-label">Folder Name</label>
                        <input type="text" name="name" class="form-control" value="{{$folder->name}}" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Rename Folder</button>
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
                const deleteUrl = "{{ route('folders.delete', ':id') }}".replace(':id', id);
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
    document.getElementById('search').addEventListener('keyup', function() {
        let query = this.value;

        fetch(`{{ route('folders.index') }}?search=${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('folder-list').innerHTML = data;
        });
    });
</script>
<script>
    function showDeleteDataDialogFiles(id) {
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
                const deleteUrl = "{{ route('files.delete', ':id') }}".replace(':id', id);
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
