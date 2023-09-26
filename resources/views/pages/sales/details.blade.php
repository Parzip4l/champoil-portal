@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Growth</a></li>
    <li class="breadcrumb-item"><a href="{{route('sales.index')}}">Sales Order</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $sales->code }}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Details Sales Order {{ $sales->code }}</h6>
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
            <div class="detail-contact-wrap">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mb-3">{{$sales->contactname}}</h3>
                        <div class="address mb-3 d-flex">
                            <label for="" class="me-3">Sales Team</label>
                            <p class="text-muted">{{ $sales->sales_team }}</p>
                        </div>
                        <div class="address mb-3 d-flex">
                            <label for="" class="me-3">Order Date</label>
                            <p class="text-muted">{{ $sales->order_date }}</p>
                        </div>
                        <div class="address mb-3 d-flex">
                            <label for="" class="me-3">Delivery Date</label>
                            <p class="text-muted">{{ $sales->delivery_date }}</p>
                        </div>
                        <div class="address mb-3 d-flex">
                            <label for="" class="me-3">Payment Terms</label>
                            <p class="text-muted">{{ $sales->payment_terms }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2" style="text-align:right">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('delivery-orders.create', ['sales_id' => $sales->id]) }}" class="btn btn-primary w-100">Delivery</a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#invoiceTypeModal">Create Invoice</a>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('purchase.sendToSlack', ['purchase' => $sales->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Kirim ke Slack</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="productTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Tax</th>
                                    <th>Analytics</th>
                                    <th>Delivery Status</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $dataProduct = json_decode($sales->data_product, true);
                                @endphp
                                @foreach($salesDetails as $details)
                                <tr>
                                    <td>{{$details['product_name']}}</td>
                                    <td>Rp. {{ number_format($details['unit_price'], 0, ',', '.') }}</td>
                                    <td>{{$details['quantity']}}</td>
                                    <td>{{$details['tax']}}</td>
                                    <td>{{$details['analytics']}}</td>
                                    <td>
                                        <span class="badge {{ $sales->delivery_status === 'Full Delivery' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $sales->delivery_status }}
                                        </span> 
                                    </td>
                                    <td>Rp. {{ number_format($details['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="title-total mt-3" style="text-align:right;">
                            <h4>Total : Rp.{{ number_format($sales->total, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Invoice Type -->
<div class="modal fade" id="invoiceTypeModal" tabindex="-1" aria-labelledby="invoiceTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceTypeModalLabel">Select Invoice Type</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-primary w-100 mb-2" onclick="createRegularInvoice()">Regular Invoice</button>
                <button class="btn btn-primary w-100 mb-2" onclick="createDpPercentageInvoice()">DP (Percentage)</button>
                <button class="btn btn-primary w-100 mb-2" onclick="createDpFixedAmountInvoice()">DP (Fixed Amount)</button>
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
    function createRegularInvoice() {
        // Redirect to regular invoice creation page with necessary data
        window.location.href = "{{ route('invoice.create', ['sales_id' => $sales->id]) }}";
    }

    function createDpPercentageInvoice() {
        // Redirect to DP (Percentage) invoice creation page with necessary data
        window.location.href = "{{ route('invoice.create', ['sales_id' => $sales->id, 'invoice_type' => 'dp_percentage']) }}";
    }

    function createDpFixedAmountInvoice() {
        // Redirect to DP (Fixed Amount) invoice creation page with necessary data
        window.location.href = "{{ route('invoice.create', ['sales_id' => $sales->id, 'invoice_type' => 'dp_fixed_amount']) }}";
    }
</script>
<style>
    label.me-3 {
        width : 25%;
    }
    .detail-contact-wrap p.text-muted {
        width : 80%;
    }

    .accounting-wrap label.me-3 {
        width : 25%;
    }

    .btn-disabled {
        pointer-events: none; /* Mencegah tindakan pointer (klik, hover, dll.) */
        opacity: 0.5; /* Mengurangi kejelasan tampilan untuk menunjukkan status dinonaktifkan */
        text-decoration: none; /* Menghapus garis bawah tautan (opsional) */
        cursor: not-allowed; /* Menunjukkan kursor tidak diizinkan (opsional) */
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