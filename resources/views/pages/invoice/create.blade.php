@extends('layout.master')

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
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-header">
                <h4>Create Invoice</h4>
            </div>
            <div class="card-body">
            <form action="{{ route('invoice.store') }}" method="POST" id="invoiceForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="client" class="form-label">Client</label>
                            <select name="client" id="client" class="form-control select2">
                                @foreach($customer as $datacust)
                                <option value="{{$datacust->name}}">{{$datacust->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Item Rows -->
                <div id="item-rows">
                    <div class="item-row mb-3">
                        <h5 class="mb-2">Item</h5>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="items[0][title]" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" name="items[0][description]" required>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="items[0][qty]" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" name="items[0][harga]" required>
                        </div>
                        <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="number" class="form-control subtotal" name="items[0][subtotal]" readonly>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" id="addItem">Add Item</button>
                <div class="mt-3">
                    <label for="total" class="form-label">Total</label>
                    <input type="text" class="form-control" id="total" name="total" readonly>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Invoice</button>
            </form>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    
    
</div>

<script>
    let itemIndex = 1;

    document.getElementById('addItem').addEventListener('click', function() {
        let newItem = `
            <div class="item-row mb-3">
                <h5>Item</h5>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][title]" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][description]" required>
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="items[${itemIndex}][qty]" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" name="items[${itemIndex}][harga]" required>
                </div>
                <div class="mb-3">
                    <label for="subtotal" class="form-label">Subtotal</label>
                    <input type="number" class="form-control subtotal" name="items[${itemIndex}][subtotal]" readonly>
                </div>
            </div>`;
        
        document.getElementById('item-rows').insertAdjacentHTML('beforeend', newItem);
        itemIndex++;
    });

    document.getElementById('invoiceForm').addEventListener('input', function(e) {
        if (e.target.name.includes('qty') || e.target.name.includes('harga')) {
            let itemRow = e.target.closest('.item-row');
            let qty = itemRow.querySelector('input[name$="[qty]"]').value || 0;
            let harga = itemRow.querySelector('input[name$="[harga]"]').value || 0;
            let subtotal = qty * harga;
            itemRow.querySelector('.subtotal').value = subtotal;

            // Calculate total
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(function(subtotalField) {
                total += parseFloat(subtotalField.value || 0);
            });
            document.getElementById('total').value = total;
        }
    });
</script>
@endsection
