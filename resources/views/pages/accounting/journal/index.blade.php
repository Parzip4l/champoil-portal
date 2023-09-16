@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item active" aria-current="page">Journal</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Journal</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                    <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target=".contact">Add A Journal</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Journal Name</th>
                <th>Type</th>
                <th>Short Code</th>
                <th>Default Account</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $nomor = 1;
                @endphp
                @foreach ($journal as $data)
                <tr>
                    <td> {{ $nomor++ }} </td>
                    <td> {{ $data->name }} </td>
                    <td> {{ $data->type }} </td>
                    <td> {{ $data->short_code }} </td>
                    <td> {{ $data->coa_name }} </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#EditContact{{ $data->id}}">
                                    <i data-feather="edit-2" class="icon-sm me-2"></i>
                                    <span class="">Edit</span>
                                </a>
                                <form action="{{ route('journal.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
  <div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4>User Activities</h4>
        </div>
        <div class="card-body">
            @foreach($useractivities as $user)
            <div class="item">
                <span class="@if ($user->action === 'Created') text-success @elseif ($user->action === 'Deleted') text-danger @elseif ($user->action === 'Updated') text-warning @endif">{{ $user->action }} By</span> - {{ $user->username }} - {{ $user->created_at }}
            </div>
            <hr>
            @endforeach
        </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Contact -->
<div class="modal fade bd-example-modal-lg contact" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Add New Journal</h4>
            <hr>
            <form class="forms-sample" action="{{ route('journal.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Journal Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" placeholder="Journal Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Type</label>
                            <select name="type" class="form-control" id="" required>
                                <option value="Sales">Sales</option>
                                <option value="Purchase">Purchase</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Default Account</label>
                            <select name="default_account" id="" class="form-control" required>
                                @foreach ($coa as $at)
                                    <option value="{{$at->id}}">{{$at->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Short Code</label>
                            <input type="text" class="form-control" name="short_code" required>
                        </div>
                    </div>  
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Save Journal</button>
            </form>
        </div>
    </div>
</div>

<!-- Update Modal -->
@foreach($journal as $d )
<div class="modal fade bd-example-modal-lg contact" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="EditContact{{ $d->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Edit Journal</h4>
            <hr>
            <form class="forms-sample" action="{{ route('journal.update', $d->id) }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Journal Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" value="{{$d->name}}" placeholder="Journal Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Type</label>
                            <select name="type" class="form-control" id="" required>
                                <option value="Sales">Sales</option>
                                <option value="Purchase">Purchase</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Default Account</label>
                            <select name="default_account" id="" class="form-control" required>
                                @foreach ($coa as $at)
                                    <option value="{{$at->id}}">{{$at->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Short Code</label>
                            <input type="text" class="form-control" name="short_code" value="{{$d->short_code}}" required>
                        </div>
                    </div>  
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Update Journal</button>
            </form>
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
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Delete Data',
            text: 'Are You Sure You Will Delete This Data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('journal.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Journal Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Journal Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Journal to Delete',
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