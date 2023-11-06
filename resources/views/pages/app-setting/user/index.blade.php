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
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#ModalEditPass{{$data->id}}"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Ganti Password</span></a>
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
                            <select name="name" id="" class="js-example-basic-single form-select" data-width="100%">
                                <option disabled>Select Employee</option>
                                @foreach($employee as $karyawan)
                                    <option value="{{$karyawan->nik}}" data-nik="{{$karyawan->nik}}">{{$karyawan->nama}}</option>
                                @endforeach
                            </select>
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
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="ops_access">
                                        <label class="form-check-label">Ops Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="sales_access">
                                        <label class="form-check-label">Sales Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="hr_access">
                                        <label class="form-check-label">HR Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="formulation_access">
                                        <label class="form-check-label">Formulation Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="creative_access">
                                        <label class="form-check-label">Creative Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="maintenance_access">
                                        <label class="form-check-label">Maintenance Access</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="purchase_access">
                                        <label class="form-check-label">Purchase Access</label>
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

@foreach($users as $d)
<div class="modal fade bd-example-modal-lg" id="ModalEditPass{{$d->id}}" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-4">
        <h4 class="pb-2">Ganti Password Pemilik Akun Dengan Username {{$d->name}}</h4>
        <hr>
        <form class="forms-sample" action="{{ route('pass.update', $d->id) }}" method="POST">
        @csrf
        @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="passwordInput" autocomplete="off" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="authCheck">
                        <label class="form-check-label" for="authCheck">
                            Show Password
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary me-2">Submit</button>    
        </form>
    </div>
  </div>
</div>
@endforeach
<!-- End Modal -->
@endsection

@push('plugin-scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
  $('#nama').autocomplete({
        minLength: 1,
        source: function(request, response){
            $.ajax({
                url: "{{ route('users.autocomplete') }}",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data){
                    response($.map(data, function(item){
                        return {
                            nik: item.nik,
                            value: item.value
                        }
                    }));
                }
            });
        },
        select: function(event, ui){
            $('#nama').val(ui.item.value);
            $('#employee_code').val(ui.item.nik);
            return false;
        }
    });
</script>
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
<script>
  const authCheck = document.getElementById('authCheck');
  const passwordInput = document.getElementById('passwordInput');

  authCheck.addEventListener('change', function() {
    if (authCheck.checked) {
      passwordInput.type = 'text';
    } else {
      passwordInput.type = 'password';
    }
  });
</script>
@endpush