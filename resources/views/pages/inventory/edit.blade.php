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
    <li class="breadcrumb-item"><a href="#">Edit</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
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
                    <form action="{{route('inventory-product.update', $product->id )}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Product Name</label>
                                    <input type="text" value="{{$product->name}}" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Product Category</label>
                                    <select name="category" class="form-control" id="product_category">
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" value="{{$product->code}}" id="product_code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Units of Measure</label>
                                    <select name="uom" class="form-control" id="">
                                        @foreach ($uom as $unitof)
                                            <option value="{{ $unitof->id }}" {{ $product->uom == $unitof->id ? 'selected' : '' }}>{{ $unitof->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Purchase UOM</label>
                                    <select name="purchase_uom" class="form-control" id="">
                                        @foreach ($uom as $unitof)
                                            <option value="{{ $unitof->name }}" {{ $product->purchase_uom == $unitof->name ? 'selected' : '' }}>{{ $unitof->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Product Type</label>
                                    <select name="type" class="form-control" id="">
                                        <option value="Consumable" {{ $product->type == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                                        <option value="Service" {{ $product->type == 'Service' ? 'selected' : '' }}>Service</option>
                                        <option value="Storable Product" {{ $product->type == 'Storable Product' ? 'selected' : '' }}>Storable Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Taxes</label>
                                    <select name="taxes" class="form-control" id="">
                                        <option value="Non Taxes" {{ $product->taxes == 'Non Taxes' ? 'selected' : '' }}>Non Taxes</option>
                                        <option value="11%" {{ $product->taxes == '11%' ? 'selected' : '' }}>11%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Product Cost</label>
                                    <input type="text" class="form-control" value="{{$product->cost}}" name="cost">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3">
                                    <label for="" class="me-3 form-label">Warehouse Location</label>
                                    <select name="warehouse" class="form-control" id="">
                                        @foreach ($warehouse as $wrh)
                                            <option value="{{ $wrh->id }}" {{ $product->warehouse_name == $wrh->name ? 'selected' : '' }}>{{ $wrh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Update Data</button>
                    </form>
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
        <!-- End Content -->
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