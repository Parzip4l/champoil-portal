@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumb as $bcFolder)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $bcFolder->name }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $bcFolder->url }}">{{ $bcFolder->name }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card2 mb-4">
            <form id="upload-form" action="{{ route('files.store', ['folderId' => $folder->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file[]" id="file-input" style="display: none;" multiple>
            </form>

            <div id="drop-zone" style="border: 2px dashed #ccc; padding: 20px;">
                <p>Drag & drop files here or click to select</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPengumuman"><i data-feather="plus" class="icon-sm me-2"></i>Create Folder</a>
            </div>
            <div class="card-body">
                @foreach($folder->subfolders as $subfolder)
                    <div class="folder mb-2">
                        <a href="{{ route('folders.show', $subfolder->id) }}" class="d-flex">
                            <i data-feather="folder" class="icon-xl me-2"></i>
                            <p class="text-muted align-self-center ">{{ $subfolder->name }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Upload By</th>
                                <th>Upload Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $data)
                            @php 
                                $karyawan = \App\Employee::where('nik',$data->uploader)->first();
                                $folder = \App\Document\FolderModel::where('id', $data->folder_id)->first();
                            @endphp
                            <tr>
                                <td>{{$data->name}}</td> 
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
                                            <form action="{{route('files.delete', $data->id)}}" method="post" id="deleteFiles">
                                                @csrf
                                                @method('delete')
                                                <a class="dropdown-item d-flex align-items-center" href="" onClick="submitForm()">
                                                    <i data-feather="trash" class="icon-sm me-2"></i> <s    pan class="">Delete</span>
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
                <h5 class="modal-title" id="exampleModalLabel">Buat Folder Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('folders.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="Judul" class="form-label">Folder Name</label>
                        <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buat Folder Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
    function submitForm() {
      document.getElementById("deleteFiles").submit();
    }
</script>

  <script>
    document.getElementById('drop-zone').addEventListener('click', () => {
    document.getElementById('file-input').click();
});

document.getElementById('file-input').addEventListener('change', (event) => {
    uploadFiles(event.target.files);
});

document.getElementById('drop-zone').addEventListener('dragover', (event) => {
    event.preventDefault();
    event.stopPropagation();
    event.target.style.background = "#f0f0f0";
});

document.getElementById('drop-zone').addEventListener('dragleave', (event) => {
    event.preventDefault();
    event.stopPropagation();
    event.target.style.background = "";
});

document.getElementById('drop-zone').addEventListener('drop', (event) => {
    event.preventDefault();
    event.stopPropagation();
    event.target.style.background = "";
    const files = event.dataTransfer.files;
    uploadFiles(files);
});

function uploadFiles(files) {
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('file[]', files[i]); // Note the `file[]` naming for multiple files
    }

    fetch("{{ route('files.store', ['folderId' => $folder->id]) }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    }).then(response => {
        if (response.ok) {
            Swal.fire({
                title: 'Files Uploaded Successfully',
                icon: 'success',
            }).then(() => {
                window.location.reload(); // Refresh the page after closing the alert
            });
        } else {
            Swal.fire({
                title: 'Upload Failed',
                text: 'An error occurred while uploading files.',
                icon: 'error',
            });
        }
    }).catch(error => {
        console.error('Error uploading files:', error);
        Swal.fire({
            title: 'Upload Error',
            text: 'File upload failed.',
            icon: 'error',
        });
    });
}
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
   function showDeleteDataDialogFiles(id) {
    Swal.fire({
        title: 'Delete File',
        text: 'Are you sure you want to delete this file?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = "{{ route('files.delete', ':id') }}".replace(':id', id);
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            }).then((response) => {
                if (response.ok) {
                    Swal.fire({
                        title: 'File Deleted Successfully',
                        icon: 'success',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Deletion Failed',
                        text: 'An error occurred while deleting the file.',
                        icon: 'error',
                    });
                }
            }).catch((error) => {
                Swal.fire({
                    title: 'Deletion Error',
                    text: 'An error occurred while deleting the file.',
                    icon: 'error',
                });
            });
        }
    });
}

</script>
@endpush