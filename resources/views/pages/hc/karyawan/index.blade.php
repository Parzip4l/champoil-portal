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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Employees</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <div class="dropdown"> 
                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-lg text-muted pb-3px align-self-center" data-feather="align-justify"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center me-2" href="{{ route('employee.create') }}"><i data-feather="plus" class="icon-sm me-2"></i> Tambah Karyawan</a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i data-feather="upload" class="icon-sm me-2"></i> Import</a>
                            <a class="dropdown-item d-flex align-items-center me-2"  href="{{ route('export.employee') }}"><i data-feather="download" class="icon-sm me-2"></i> Export</a>
                            <a class="dropdown-item d-flex align-items-center me-2"  href="https://truest.co.id/wp-content/uploads/2024/02/Tamplate-Karyawan-1.xlsx"><i data-feather="file-text" class="icon-sm me-2"></i> Download Template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kode Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Organisasi</th>
                    <th>Jabatan</th>
                    <th>Status Karyawan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1;
                    @endphp
                    @foreach ($karyawan as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td><a href="{{ route('employee.show', $data->id) }}">{{ $data->nama }}</a></td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ $data->organisasi }}</td>
                    <td>{{ $data->jabatan }}</td>
                    <td><span class="@if($data->status_kontrak == 'Permanent') badge rounded-pill bg-primary @else badge rounded-pill bg-success @endif">{{ $data->status_kontrak }}</span></td>
                    <td>
                        <div class="dropdown"> 
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('employee.edit', ['employee' => $data->nik]) }}"><i data-feather="git-branch" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('employee.show', $data->id) }}"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View Detail</span></a>
                                <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#resign-{{ $data->id }}">
                                    <i data-feather="user-x" class="icon-sm me-2"></i> <span class="">Resign</span>
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
                <div class="modal fade" id="resign-{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Resign</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('employee.resign')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label for="" class="form-label">Reason</label>
                                            <textarea class="form-control" id="reason" name="reason"></textarea>
                                        </div>
                                        
                                        <div class="col-md-12 mt-2">
                                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('import.employee') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control mb-2" name="csv_file" required>
                    <button type="submit" class="btn btn-primary w-100">Import Excel</button>
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
@endpush