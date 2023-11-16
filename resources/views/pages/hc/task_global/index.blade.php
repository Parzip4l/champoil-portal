@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Data Task</h5>
                <div class="btn-group">
                    <a href="#" class="btn btn-success">Report</a>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#knowledgeModal">Tambah Task</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Mengulang<th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($records as $record)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#EditContact{{ $record->id}}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('add_soal', ['id' => $record->id]) }}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Tambah Soal</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('asign_user', ['id' => $record->id]) }}">
                                                <i data-feather="users" class="icon-sm me-2"></i>
                                                <span class="">Asign User</span>
                                            </a>
                                            @if($record->count_cek > 0)
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('start_class', ['id' => $record->id]) }}">
                                                    <i data-feather="book" class="icon-sm me-2"></i>
                                                    <span class="">Start Class </span>
                                                </a>
                                            @endif
                                            <form action="{{ route('knowledge.destroy', $record->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE') 
                                                <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $record->id }}')">
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

<!-- Modal Data FNG -->
<div class="modal fade" id="knowledgeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
            <form
                    action="{{route('knowledge.store')}}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Unit Bisnis</label>
                            <select name="unit_bisnis" class="form-control">
                                <option value="Champoil">CHAMPOIL</option>
                                <option value="Kas">KAS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Nama Task</label>
                            <input type="text" class="form-control" name="task_name" required="required"></div>
                            <div class="col-md-12 mb-2">
                                <label for="" class="form-label">Upload Photo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        YES
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="0" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        NO
                                    </label>
                                </div>
                                <label for="" class="form-label">Mengulang</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Harian
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Mingguan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        Bulanan
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>

<!-- End -->
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
                const deleteUrl = "{{ route('knowledge.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
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