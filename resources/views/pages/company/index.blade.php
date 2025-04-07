@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Company List</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="#" class="btn btn-primary" data-bs-target="#CompanyModal" data-bs-toggle="modal">Add Company</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Company Code</th>
                                <th>Company Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companyData as $data)
                            <tr>
                                <td><a href="{{ route('company-settings.edit', $data->id)}}">{{$data->company_code}}</a></td>
                                <td>{{$data->company_name}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('company.show', $data->id)}}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" data-bs-target="#CompanyModalEdit{{ $data->id }}" data-bs-toggle="modal">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('companymenu.set', $data->id)}}">
                                                <i data-feather="menu" class="icon-sm me-2"></i>
                                                <span class="">Feature Settings</span>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Company -->
<!-- Modal Tambah User -->
<div class="modal fade" id="CompanyModal" tabindex="-1" aria-labelledby="CompanyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CompanyModal">Tambah Data Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('company.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Sisa modal tetap, hanya ini yang berubah -->
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Company Address</label>
                            <textarea name="company_address" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Email Admin (Akses Login)</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Add Company</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Company -->
@foreach($companyData as $data)
<div class="modal fade" id="CompanyModalEdit{{$data->id}}" tabindex="-1" aria-labelledby="CompanyModalEdit{{$data->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CompanyModalEdit{{$data->id}}">Edit Data Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('company.update', $data->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" placholder="e.g Indolumas Grease, PT" value="{{$data->company_name}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Company Address</label>
                            <textarea name="company_address" id="" cols="30" rows="10" class="form-control">{{$data->company_address}}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Use Schedule</label>
                            <select name="use_scedule" class="form-control" id="">
                                <option value="No" {{$data->use_schedule == 'No' ? 'selected' : ''}}>No</option>
                                <option value="Yes" {{$data->use_schedule == 'Yes' ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Schedule Type</label>
                            <select name="schedule_type" class="form-control" id="">
                                <option value="No" {{$data->schedule_type == 'No' ? 'selected' : ''}}>No</option>
                                <option value="Daily" {{$data->schedule_type == 'Daily' ? 'selected' : ''}}>Daily</option>
                                <option value="Monthly" {{$data->schedule_type == 'Monthly' ? 'selected' : ''}}>Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Cut Off Start</label>
                            <input type="number" name="cutoff_start" class="form-control" value="{{$data->cutoff_start}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Cut Off End</label>
                            <input type="number" name="cutoff_end" class="form-control" value="{{$data->cutoff_end}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Latitude</label>
                            <input type="text" name="latitude" value="{{$data->latitude}}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="{{$data->longitude}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Radius(KM)</label>
                            <input type="number" name="radius" class="form-control" value="{{$data->radius}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Update Company</button>
                        </div>
                    </div>
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
                const deleteUrl = "{{ route('company.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Company Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Company Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Company Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
@endpush