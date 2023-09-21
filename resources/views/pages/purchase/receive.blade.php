@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Purchasing</a></li>
        <li class="breadcrumb-item"><a href="#">Receive Product</a></li>
        <li class="breadcrumb-item active" aria-current="page">Purchase Order {{ $purchase->code }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-title">Receive Product for Purchase Order {{ $purchase->code }}</h6>
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
                        @if ($purchase->status !== 'Received')
                            <form action="{{ route('purchase.partial_receive', $purchase->id) }}" method="POST">
                                @csrf
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity Ordered</th>
                                            <th>Sisa Quantity</th>
                                            <th class="{{ $purchase->status === 'Received' ? 'd-none' : '' }}">Quantity Received</th>
                                            <th>Full Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($purchaseDetails as $detail)
                                            <tr>
                                                <td>
                                                    @php
                                                        // Ambil nama produk berdasarkan product_id
                                                        $productName = \App\Product::find($detail['product_id'])->name;
                                                    @endphp
                                                    {{ $productName }}
                                                </td>
                                                <td>{{ $detail['quantity'] }}</td>
                                                <td>{{ $detail['remaining_quantity'] }}</td>
                                                <td class="{{ $purchase->status === 'Received' ? 'd-none' : '' }}">
                                                    <input type="number" name="received_quantity[{{ $detail['product_id'] }}]" min="0" max="{{ $detail['quantity'] }}" class="form-control">
                                                </td>
                                                <td colspan="4">
                                                    <button type="button" class="btn btn-primary" onclick="setFullReceived()">Full Received</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary mt-2">Receive Product</button>
                            </form>
                        @else
                            <div class="alert alert-success mt-3">All products have been received for this purchase order.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Receive History </a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="lineTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Purchase Code</th>
                                <th>Product Name</th>
                                <th>Received Quantity</th>
                                <th>Received By</th>
                                <th>Received Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseReceipts as $receipt)
                                <tr>
                                    <td>{{ $purchase->code }}</td>
                                    <td>
                                        @php
                                            // Ambil nama produk berdasarkan product_id
                                            $productNameData = \App\Product::find($receipt['product_id'])->name;
                                        @endphp
                                        {{ $productNameData }}
                                    </td>
                                    <td>{{ $receipt->received_quantity }}</td>
                                    <td>{{ $receipt->received_by }}</td>
                                    <td>{{ $receipt->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    function setFullReceived() {
        // Loop through each input field and set received quantity to maximum quantity
        $('input[name^="received_quantity["]').each(function() {
            var maxQuantity = parseFloat($(this).attr('max'));
            $(this).val(maxQuantity);
        });
    }
</script>
@endpush
