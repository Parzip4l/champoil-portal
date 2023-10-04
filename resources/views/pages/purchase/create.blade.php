@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Purchasing</a></li>
    <li class="breadcrumb-item active" aria-current="page">Purchase Order</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Purchase Orders</h6>
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
                </div>
            </div>
        </div>
        <div class="card-body-wrap">
            <div class="form">
            <form method="post" action="{{ route('purchase.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="code" class="form-label">Code :</label>
            <input type="text" class="form-control" name="code" value="{{$purchaseCode}}"required>
        </div>
        <div class="form-group mb-3">
            <label for="vendor" class="form-label">Vendor :</label>
            <select name="vendor" class="form-control" id="">
                @foreach($contact as $data)
                <option value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="expected_arrival" class="form-label">Estimate Arrival Date :</label>
            <div class="input-group flatpickr" id="flatpickr-date">
                <input type="text" class="form-control" placeholder="Select date" name="expected_arrival" data-input required>
                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                <input type="hidden" class="form-control" name="status" value="Not Received" required>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="warehouse" class="form-label">Warehouse :</label>
            <select name="warehouse" class="form-control" id="">
                @foreach($warehouse as $data)
                <option value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
    <table class="table table-bordered" id="productTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>UOM</th>
                <th>Analytics</th>
                <th class="categories">Category</th>
                <th>Taxes</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <button type="button" id="addProduct" class="btn btn-primary mt-1 mb-3">Tambah Produk</button>
</div>
        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
    </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script>
    function addProductRow() {
        const newRow = `
            <tr>
                <td>
                    <select name="product[]" class="js-example-basic-single form-select product-select">
                        <option value="">Select Product</option>
                        @foreach($product2 as $product)
                            <option value="{{ $product->id }}" data-purchase-uom="{{ $product->purchase_uom }}" data-purchase-categories="{{ $product->category }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="unit_price[]" placeholder="Unit Price" class="form-control">
                </td>
                <td>
                    <input type="number" name="quantity[]" placeholder="Quantity" class="form-control">  
                </td>
                <td class="purchase-uom-td">
                    <!-- Ini adalah tempat di mana Purchase UOM akan ditampilkan -->
                </td>
                <td>
                    <select name="analytics[]" class="js-example-basic-single form-select">
                        <option value="">Select Analytics</option>
                        @foreach($accountAnalytics as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>  
                </td>
                <td class="product-categories-td">
                    <!-- Ini adalah tempat di mana Product Categories akan ditampilkan -->
                </td>
                <td>
                    <select name="tax[]" class="js-example-basic-single form-select">
                        <option value="">Select Tax</option>
                        @foreach($tax2 as $tax)
                            <option value="{{ $tax->tax }}">{{ $tax->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">Hapus</button>
                </td>
            </tr>
        `;
        document.querySelector('#productTable tbody').insertAdjacentHTML('beforeend', newRow);

        updateProductCategory(document.querySelector('#productTable tbody').lastElementChild.querySelector('.product-select'));
    }

    function removeProductRow(button) {
        const row = button.closest('tr');
        row.remove();
    }

    // Fungsi untuk menampilkan Purchase UOM saat produk dipilih
    $(document).on('change', '.product-select', function () {
        const selectedProductId = $(this).val();
        const purchaseUomTd = $(this).closest('tr').find('.purchase-uom-td');

        // Reset isi kolom Purchase UOM
        purchaseUomTd.html('');

        // Cek apakah produk dipilih
        if (selectedProductId !== '') {
            // Dapatkan data Purchase UOM dari atribut data-purchase-uom pada option yang dipilih
            const selectedOption = $(this).find('option:selected');
            const purchaseUom = selectedOption.data('purchase-uom');

            // Tampilkan Purchase UOM pada kolom Purchase UOM
            purchaseUomTd.text(purchaseUom);
        }
    });

    function updateProductCategory(select) {
    const selectedOption = select.options[select.selectedIndex];
    const productCategoryTd = select.closest('tr').querySelector('.product-categories-td');

    // Reset isi kolom Product Categories
    productCategoryTd.textContent = '';

    // Cek apakah produk dipilih
    if (selectedOption) {
        const productCategories = selectedOption.getAttribute('data-purchase-categories');

        // Buat elemen input
        const inputElement = document.createElement('input');
        inputElement.type = 'text';
        inputElement.name = 'product_categories[]';
        inputElement.value = productCategories;

        // Tambahkan elemen input ke dalam kolom Product Categories
        productCategoryTd.appendChild(inputElement);
    }
}

$(document).on('change', '.product-select', function () {
    updateProductCategory(this);
});
    document.getElementById('addProduct').addEventListener('click', addProductRow);
</script>

<style>
    td.product-categories-td {
        display : none;
    }
    th.categories {
    display: none;
}
</style>
@endpush

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush