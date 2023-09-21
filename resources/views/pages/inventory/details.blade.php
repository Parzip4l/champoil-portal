@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Warehouse Management</a></li>
    <li class="breadcrumb-item"><a href="#">Inventory</a></li>
    <li class="breadcrumb-item"><a href="{{route('inventory-product.index')}}">Product</a></li>
    <li class="breadcrumb-item active" aria-current="page">[{{ $product->code }}] {{ $product->name }}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row d-flex">
                <div class="col-md-6 align-self-center">
                    <h6 class="card-title mb-0">Product Details</h6>
                </div>
            </div>
        </div>
        <hr>
        <!-- Content -->
        <div class="detail-contact-wrap">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mb-3">{{$product->name}}</h3>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Product Code</label>
                        <p class="text-muted">{{ $product->code }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Product Type</label>
                        <p class="text-muted">{{ $product->type }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Product Category</label>
                        <p class="text-muted"><a href="{{ route('product.byCategory', ['category_id' => $product->category]) }}">{{ $product->categoryname }}</a></p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Units of Measure</label>
                        <p class="text-muted"><a href="{{route('uom.index')}}">{{ $product->uom_name }}</a></p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Purchase UOM</label>
                        <p class="text-muted"><a href="{{route('uom.index')}}">{{ $product->purchase_uom }}</a></p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Taxes</label>
                        <p class="text-muted">{{ $product->taxes }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Product Cost</label>
                        <p class="text-muted">{{ $product->cost }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Warehouse Location</label>
                        <p class="text-muted"><a href="{{ route('product.byWarehouse', ['warehouse_id' => $product->warehouse]) }}">{{ $product->warehouse_name }}</a></p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3">Product Onhand</label>
                        <p class="text-muted">{{ $product->onhand }} {{ $product->uom_name }}</p>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- End Content -->
      </div>
    </div>
  </div>
  <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Moving History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">User Activities</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="lineTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                        <div class="row moving-history">
                            <div class="col-md-3">
                                <div class="to">
                                    Date
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="from">
                                    From
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="to">
                                    To
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="to">
                                    Qty
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row ">
                            @foreach($history as $moving)
                            <div class="col-md-3 mb-2">
                                <div class="to">
                                    {{ \Carbon\Carbon::parse($moving->created_at)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="from">
                                    {{$moving->modul}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="to">
                                    @if ($moving->modul == 'Sales')
                                        Customers
                                    @else
                                        {{ $product->warehouse_name }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="to {{ $moving->action === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{$moving->quantity}} {{ $product->uom_name }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">
                        <div class="user-activities-data">
                            @foreach($userActivities as $item) 
                            <div class="item">
                                <div class="created-date text-muted mb-1">
                                    {{$item->created_at}}
                                </div>
                                <div class="name mb-1">
                                    <h5>{{$item->username}}</h5>
                                </div>
                                <span class="badge @if ($item->action === 'Created') bg-success @elseif ($item->action === 'Deleted') bg-danger @elseif ($item->action === 'Updated') bg-warning @endif">{{$item->action}} Data</span>
                                <hr>    
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    
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
<style>
    label.me-3 {
        width : 40%;
    }
    .detail-contact-wrap p.text-muted {
        width : 60%;
    }

    .accounting-wrap label.me-3 {
        width : 45%;
    }

    @media (max-width : 678px){
        label.me-3 {
            width : 30%;
        }
        .detail-contact-wrap p.text-muted {
            width : 70%;
        }
        .col-md-6.align-self-center, .col-md-6.text-right.align-self-center {
            width : 50%;
        }
    }
</style>

@endpush