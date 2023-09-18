@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Growth</a></li>
    <li class="breadcrumb-item active" aria-current="page">Contacts</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Contacts</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                    <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target=".contact">Add A Contact</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Categories</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $nomor = 1;
                @endphp
                @foreach ($contact as $data)
                <tr>
                    <td> {{ $nomor++ }} </td>
                    <td> {{ $data->name }} </td>
                    <td> {{ $data->phone }} </td>
                    <td> {{ $data->email }} </td>
                    <td> 
                        @if ($data->categories == 1)
                            Individual
                        @elseif ($data->categories == 2)
                            Company
                        @else
                            Not Valid
                        @endif 
                    </td>
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
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('contact.show', $data->id) }}">
                                    <i data-feather="eye" class="icon-sm me-2"></i>
                                    <span class="">Details</span>
                                </a>
                                <form action="{{ route('contact.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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

<!-- Modal Tambah Contact -->
<div class="modal fade bd-example-modal-lg contact" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Add New Contact</h4>
            <hr>
            <form class="forms-sample" action="{{ route('contact.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" placeholder="Full Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Categories</label>
                            <select name="categories" id="" class="form-control" required>
                                <option value="1">Individual</option>
                                <option value="2">Company</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Address" required> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="City" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">State</label>
                            <input type="text" name="state" class="form-control" placeholder="State" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mobile</label>
                            <input type="number" name="mobile" class="form-control" placeholder="Mobile" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Website</label>
                            <input type="text" name="website" class="form-control" placeholder="Website">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control" placeholder="Tags">
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Accounting</h5>
                    <hr>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Account Receivable</label>
                            <select name="account_receive" id="" class="form-control" required>
                                @foreach($coa as $coadata)
                                <option value="{{$coadata->id}}">{{$coadata->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Account Payable</label>
                            <select name="account_pay" id="" class="form-control" required>
                                @foreach($coa as $coadata)
                                    <option value="{{$coadata->id}}">{{$coadata->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Bank Information</h5>
                    <hr>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bank</label>
                            <input type="text" class="form-control" name="bank" placeholder="BCA">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bank Account Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="Account Name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bank Account Number</label>
                            <input type="number" class="form-control" name="bank_number" placeholder="0029XXXXX">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Save Contact</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Contact -->
@foreach($contact as $d )
<div class="modal fade bd-example-modal-lg contact" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="EditContact{{ $d->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Edit Contact {{ $d->name }}</h4>
            <hr>
            <form class="forms-sample" action="{{ route('contact.update', $d->id) }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" placeholder="Full Name" value="{{$d->name}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Categories</label>
                            <select name="categories" id="" class="form-control" required>
                                <option value="1" {{ $d->categories == '1' ? 'selected' : '' }}>Individual</option>
                                <option value="2" {{ $d->categories == '2' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Address" value="{{ $d->address }}"> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="City" value="{{ $d->city }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">State</label>
                            <input type="text" name="state" class="form-control" placeholder="State" value="{{ $d->state }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $d->email }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" placeholder="Phone" value="{{ $d->phone }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mobile</label>
                            <input type="number" name="mobile" class="form-control" placeholder="Mobile" value="{{ $d->mobile }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Website</label>
                            <input type="text" name="website" class="form-control" placeholder="Website" value="{{ $d->website }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control" placeholder="Tags" value="{{ $d->tags }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Update Contact</button>
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
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('contact.destroy', ':id') }}".replace(':id', id);
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