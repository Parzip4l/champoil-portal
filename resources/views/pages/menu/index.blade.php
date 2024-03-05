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
            <div class="head-card">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">
                        Menu Setting 
                        <a href="#" 
                           class="btn btn-xs btn-primary " 
                           style="float:right"
                           data-bs-toggle="modal" 
                           data-bs-target="#menu">Tambah Menu</a>
                        
                    </h6>
                    
                    
                </div>
                
            </div>
        </div>
        <div class="card-body">
            <div class="accordion" id="accordionExample">
                @if($result)
                    @php 
                        $no=1;
                    @endphp
                    @foreach($result as $row)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$row->id}}" aria-expanded="true" aria-controls="collapseOne">
                                {{ $row->title }}
                            </button>
                            </h2>
                            <div id="collapseOne{{$row->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <a class="dropdown-item d-flex align-items-center" 
                                        href="javasvript:void(0)"
                                        style="float:right"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#sub-{{ $row->id }}">
                                        <span class="btn btn-xs btn-success">Tambah Sub Menu</span>
                                    </a>
                                    <table id="example" class="display table">
                                        <thead>
                                            <tr role="row">
                                                <td width="30px"></td>
                                                <th width="30px">No</th>
                                                <th class="ord" data-name="title">Nama Menu</th>
                                                <th class="ord" data-name="url">Link</th>
                                                <th class="ord" data-name="description">Keterangan</th>
                                                <th class="ord" data-name="icon" width="30px">Icon</th>
                                                <th class="ord" data-name="menu_order" width="50px">Urutan</th>
                                                <th width="110px" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($row->parent))
                                            @php 
                                                $parent_no=1;
                                            @endphp
                                            @foreach($row->parent as $row_parent)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $parent_no }}</td>
                                                    <td>{{ $row_parent->title }}</td>
                                                    <td>{{ $row_parent->url }}</td>
                                                    <td>{{ $row_parent->description }}</td>
                                                    <td>{{ $row_parent->is_icon }}</td>
                                                    <td>{{ $row_parent->menu_order }}</td>
                                                    <td></td>
                                                </tr>


                                            @php 
                                                $parent_no++;
                                            @endphp
                                            @endforeach
                                        @else
                                            

                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="modal fade" id="sub-{{ $row->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Parent : {{ $row->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('menu-create') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                
                                                <div class="col-md-12 mb-2">
                                                    <label for="" class="form-label">Menu Name</label>
                                                    <input type="text" class="form-control" name="title">
                                                    <input type="hidden" name="parent_id" value="{{$row->id}}">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label for="" class="form-label">Route</label>
                                                    <input type="text" class="form-control" name="url">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label for="" class="form-label">Description</label>
                                                    <textarea class="form-control" name="description"></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label for="" class="form-label">Menu Order</label>
                                                    <input type="number" class="form-control" name="menu_order">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label for="" class="form-label">Icon</label>
                                                    <input type="text" class="form-control" name="is_icon">
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
                        @php 
                            $no++;
                        @endphp
                    @endforeach
                @endif
               
                </div>
           
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="menu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('menu-create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Menu Name</label>
                            <input type="text" class="form-control" name="title">
                            <input type="hidden" name="parent_id" value="0">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Route</label>
                            <input type="text" class="form-control" name="url">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Menu Order</label>
                            <input type="number" class="form-control" name="menu_order">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Icon</label>
                            <input type="text" class="form-control" name="is_icon">
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