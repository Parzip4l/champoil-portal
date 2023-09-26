@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Delivery</a></li>
    <li class="breadcrumb-item"><a href="{{route('delivery-orders.index')}}">Delivery Orders</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Delivery Orders</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-title">Delivery Code</h6>
                            @php
                                $existingDelivery = \App\Deliverysales::where('code', $billCode)->first();
                                // Jika sudah ada, gunakan kode yang ada di database
                                if ($existingDelivery) {
                                    $billCodeFromDatabase = $existingDelivery->code;
                                } else {
                                    // Jika belum ada, gunakan kode yang baru
                                    $billCodeFromDatabase = $billCode;
                                }
                            @endphp
                            <h3>{{ $existingDelivery ? $existingDelivery->code : $billCode }}</h3>
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
                        <!-- Data Form -->
                        <form action="{{route('delivery-orders.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code" class="form-label">Vendor Bill</label>
                                        @php
                                            $existingDelivery = \App\Deliverysales::where('code', $billCode)->first();
                                            // Jika sudah ada, gunakan kode yang ada di database
                                            if ($existingDelivery) {
                                                $billCodeFromDatabase = $existingDelivery->code;
                                            } else {
                                                // Jika belum ada, gunakan kode yang baru
                                                $billCodeFromDatabase = $billCode;
                                            }
                                        @endphp
                                        <input type="text" class="form-control" name="code" value="{{ $existingDelivery ? $existingDelivery->code : $billCode }}" readonly required>
                                        <input type="hidden" name="so_id" value="{{$Sales->id}}">
                                        <input type="hidden" name="so_code" value="{{$Sales->code}}">
                                        <input type="hidden" name="order_details" value="{{ json_encode($productDetails) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Sales Team</label>
                                        <input type="text" class="form-control" name="sales_team" value="{{$Sales->sales_team}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Customer</label>
                                        <input type="text" class="form-control" name="customer_id" value="{{$Sales->customer}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Delivery Address</label>
                                        @php
                                            // Ambil nama produk berdasarkan product_id
                                            $addresdelivery = \App\ContactM::find($Sales->customer)->address;
                                        @endphp
                                        <input type="text" class="form-control" name="delivery_address" value="{{$addresdelivery}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Delivery Date</label>
                                        <input type="text" class="form-control" name="delivery_date" value="{{$Sales->delivery_date}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Expedition</label>
                                        <input type="text" class="form-control" name="expedition" value="{{$Sales->ekspedition}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Unit Price</th>
                                                    <th>Quantity</th>
                                                    <th>Remaining Quantity to Send</th>
                                                    <th>Tax</th>
                                                    <th>Qty to Send</th>
                                                    <th>Analytics</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productDetails as $index => $details)
                                                <tr>
                                                        @php
                                                            // Mengambil product_id dari $details
                                                            $product_id = isset($details['product_id']) ? $details['product_id'] : null;

                                                            // Mengambil remaining_quantity dari tabel delivery_orders berdasarkan product_id
                                                            $deliveryOrder = \App\Sales::whereJsonContains('data_product', [['product_id' => $product_id]])->first();

                                                            $dataDetails = json_decode($deliveryOrder['data_product'], true);
                                                            
                                                            // Menentukan remaining quantity
                                                            $remainingQuantity = isset($dataDetails[0]['remaining_quantity']) ? $dataDetails[0]['remaining_quantity'] : $details['quantity'];
                                                            $qtySend = $dataDetails[0]['sent_quantity'];
                                                        @endphp
                                                    <td>{{$details['name']}}</td>
                                                    <td>Rp. {{number_format($details['unit_price'], 0,',','.')}}</td>
                                                    <td>{{$details['quantity']}}</td>
                                                    <td>
                                                        {{$remainingQuantity}}
                                                    </td>
                                                    <td>{{$details['tax']}}</td>
                                                    <td>
                                                        @if ($remainingQuantity > 0)
                                                            <input type="number" class="form-control" name="qtysend[{{$index}}]" min="0" max="{{ $remainingQuantity }}">
                                                        @else
                                                            <span class="badge bg-success">Full Delivered</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            // Ambil nama produk berdasarkan product_id
                                                            $AnalyticsName = \App\AnalyticsAccount::find($details['analytics'])->name;
                                                            echo $AnalyticsName;
                                                        @endphp
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    @if ($remainingQuantity > 0)
                                    <button class="btn btn-primary">Create Delivery Orders</button>
                                    @else
                                    <button class="btn btn-primary" disabled>Full Delivered</button>
                                    @endif
                                </div>
                            </div>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <h4>Delivery History</h4>
                </div>
                <div class="card-body">
                    <div class="data-history-delivery">
                        @foreach($HistoryDelivery as $delivery)
                        <div class="item-data">
                            @php 
                                $dataOrder = json_decode($delivery->order_details, true);
                            @endphp
                            <div class="item-tanggal">
                                {{$delivery->created_at}} ({{$delivery->code}})
                            </div>
                            <div class="qty">
                                @foreach($dataOrder as $product)
                                <div class="product-name">
                                    {{$product['name']}}
                                </div>
                                <div class="sent-qty">
                                    {{$product['sent_quantity']}} - {{$product['uom']}}
                                </div>
                                <hr>
                            @endforeach
                            </div>
                        </div>
                        @endforeach
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
@endpush

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush