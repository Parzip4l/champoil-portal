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
                <h5 class="mb-0 align-self-center">Data Patrol</h5>
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#taskModel">Tambah Patrol</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Jam Patrol Shift 1</th>
                                <th>Jam Patrol Shift 2</th>
                                <th>Jam Patrol Shift 3</th>
                                <th>Jam Patrol Shift 4</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($records as $record)
                                <?php 
                                    if($record->status == 0){
                                        $label = "Non Active";
                                        $class = "badge rounded-pill bg-primary";
                                    }else{
                                        $label = "Active";
                                        $class = "badge rounded-pill bg-success";
                                    }
                                ?>
                            <tr>
                                <td> {{$nomor++}} </td>
                                <td> {{ $record->judul }} <br/> {{ $record->project_name }}</td>
                                <td>{{ $record->jam_mulai_shift_1 }} s/d {{ $record->jam_akhir_shift_1 }}</td>
                                <td>{{ $record->jam_mulai_shift_2 }} s/d {{ $record->jam_akhir_shift_2 }}</td>
                                <td>{{ $record->jam_mulai_shift_3 }} s/d {{ $record->jam_akhir_shift_3 }}</td>
                                <td>{{ $record->jam_mulai_shift_4 }} s/d {{ $record->jam_akhir_shift_4 }}</td>
                                <td><span class="<?php echo $class ?>">{{ $label }}</span></td>
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
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('add_task', ['id' => $record->id]) }}" >
                                                <i data-feather="list" class="icon-sm me-2"></i>
                                                <span class="">Add List Task</span>
                                            </a>

                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('qrcode') }}" >
                                                <i data-feather="grid" class="icon-sm me-2"></i>
                                                <span class="">Show QR</span>
                                            </a>
                                          
                                            <form action="{{ route('task.destroy', $record->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
<div class="modal fade" id="taskModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('task.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Project</label>
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="">PILIH PROJECT</option>
                                @foreach($project as $row)
                                <option value="{{ $row->id }}">{{ $row->project }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 1</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_1">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_1">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 2</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_2">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_2">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 3</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_3">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_3">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 4</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_4">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_4">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
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
                const deleteUrl = "{{ route('task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Task Failed to Delete',
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