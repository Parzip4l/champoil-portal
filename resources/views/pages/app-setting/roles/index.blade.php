@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title align-self-center mb-0">
                        List Role
                    </h4>

                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                        Create Role
                    </a>
                </div>
            </div>
            <div class="mb-3 mx-3">
                <label for="" class="mb-2">Search Data</label>
                <input type="text" id="search-input" class="form-control" placeholder="Search by roles name" value="{{ request()->get('search') }}">
            </div>
            <div id="table-search">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @foreach($role as $data)
                            <tr>
                                <td class="ps-3">{{$data->role_name}}</td>
                                <td>{{$data->description}}</td>
                                <td>
                                    <div class="dropdown"> 
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('roles.edit', $data->id)}}" ><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
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

                
                <tfoot>
                    <div class="d-flex justify-content-between mx-3 mt-2 mb-2 ">
                        <div>
                            Showing {{ $role->firstItem() }} to {{ $role->lastItem() }} of {{ $role->total() }} entries
                        </div>
                        <div class="">
                        {{ $role->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </tfoot>
            </div>
        </div>

        <!-- end card -->
    </div>
    <!-- end col -->
</div>

<!-- End Settings -->
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <style>
    a.link-settings {
        color : #555!important;
    }
  </style>
  <script>
        $(document).ready(function() {
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('roles.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the current page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                        $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                    }
                });
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                
                var page = $(this).attr('href').split('page=')[1];  // Extract the page number from the link
                var search = $('#search-input').val();  // Get the search input value

                $.ajax({
                    url: "{{ route('roles.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                        $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                    }
                });
            });
        });
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
                    const deleteUrl = "{{ route('roles.destroy', ':id') }}".replace(':id', id);
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    }).then((response) => {
                        // Handle the response as needed (e.g., show alert if data is deleted successfully)
                        if (response.ok) {
                            Swal.fire({
                                title: 'Role Successfully Deleted',
                                icon: 'success',
                            }).then(() => {
                                window.location.reload(); // Refresh halaman setelah menutup alert
                            });
                        } else {
                            // Handle error response if needed
                            Swal.fire({
                                title: 'Role Failed to Delete',
                                text: 'An error occurred while deleting data.',
                                icon: 'error',
                            });
                        }
                    }).catch((error) => {
                        // Handle fetch error if needed
                        Swal.fire({
                            title: 'Role Failed to Delete',
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