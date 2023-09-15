@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Warehouse Management</a></li>
    <li class="breadcrumb-item"><a href="{{route('inventory-product.index')}}">Inventory</a></li>
    <li class="breadcrumb-item"><a href="{{route('inventory-product.index')}}">Product</a></li>
    <li class="breadcrumb-item"><a href="{{route('product-category.index')}}">Warehouse</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $data[0]->warehouse_name }}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Product Inventory By Warehouse {{ $data[0]->warehouse_name }}</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                    <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target=".contact">Add A Product</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Category</th>
                <th>Warehouse</th>
                <th>Onhand</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $nomor = 1;
                @endphp
                @foreach ($data as $data)
                <a href=""></a>
                <tr>
                    <td> {{ $nomor++ }} </td>
                    <td> <a href="{{ route('inventory-product.show', $data->id) }}">{{ $data->code }}</a> </td>
                    <td> {{ $data->name }} </td>
                    <td> {{ $data->type }} </td>
                    <td> {{ $data->categoryname }} </td>
                    <td> {{ $data->warehouse_name }} </td>
                    <td> {{ $data->uom_name}} </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('inventory-product.edit', $data->id)}}">
                                    <i data-feather="edit-2" class="icon-sm me-2"></i>
                                    <span class="">Edit</span>
                                </a>
                                <form action="{{ route('warehouse-location.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
            <h4 class="pb-2">Add New Product</h4>
            <hr>
            <form class="forms-sample" action="{{ route('inventory-product.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Code</label>
                            <input type="text" class="form-control" id="product_code" autocomplete="off" name="code" value="" placeholder="Automated Generated Code" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" placeholder="Product Name" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Product Type</label>
                            <select name="type" class="form-control" id="">
                                <option value="Consumable">Consumable</option>
                                <option value="Service">Service</option>
                                <option value="Storable Product">Storable Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Taxes</label>
                            <select name="taxes" id="" class="form-control">
                                <option value="-">Non Taxes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">UOM</label>
                            <select name="uom" class="form-control" id="">
                                @foreach($uom as $uom)
                                    <option value="{{ $uom->id }}">{{$uom->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Purchase UOM</label>
                            <select name="purchase_uom" id="" class="form-control">
                                @foreach($purchaseuom as $unit)
                                    <option value="{{ $unit->name }}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Category</label>
                            <select name="category" id="product_category" class="form-control" required>
                                <option value="-">Select Product</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="onhand" value="0">
                            <input type="hidden" name="cost" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Warehouse Location</label>
                            <select name="warehouse" id="" class="form-control" required>
                                <option value="-">Select Warehouse</option>
                                @foreach($warehouse as $data)
                                    <option value="{{ $data->id }}">{{$data->name}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="onhand" value="0">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Save Product</button>
            </form>
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
            title: 'Delete Data',
            text: 'Are You Sure You Will Delete This Data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('inventory-product.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Warehouse Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Warehouse Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Warehouse Failed to Delete',
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
<script>
    $('#product_category').change(function() {
        var selectedOption = $('#product_category option:selected');
        if (selectedOption.val() !== '-') {
            var category = selectedOption.text();

            // Menggunakan AJAX untuk mengambil data terakhir dari database
            $.ajax({
                url: '/get-last-product-code', // Gantilah dengan rute yang sesuai di Laravel
                type: 'GET',
                data: { category: category },
                dataType: 'json',
                success: function(response) {
                    var lastCodeString = response.lastCode; // This is the string "000"
                    var lastCodeInteger = parseInt(lastCodeString);
                    var randomCode = getRandomInt(1, 99999); // Generate a random number between 1 and 999
                    var code = 'PRD-' + category.toUpperCase() + '-' + pad(randomCode, 5); // Format as "001"
                    $('#product_code').val(code);
                }
            });
        }
        else {
            // Clear the product code input if "Select Product" is chosen
            $('#product_code').val('');
        }
    });

    function pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }

    function getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>
@endpush