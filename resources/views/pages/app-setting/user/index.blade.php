@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Data Users</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="#" class="btn btn-primary" data-bs-target="#usersModal" data-bs-toggle="modal">Tambah User</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $data)
                            <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->email}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('users.edit', $data->id)}}">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('users.edit', $data->id)}}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <form action="{{ route('users.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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

<!-- Modal Tambah User -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('users.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Permission</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="dashboard_access">
                                        <label class="form-check-label">Dashboard Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="accounting_access">
                                        <label class="form-check-label">Accounting Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="inventory_access">
                                        <label class="form-check-label">Inventory Access</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Tambah Users</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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