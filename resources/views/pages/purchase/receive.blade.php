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
                <div class="col-md-6">
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
            <form action="{{ route('purchase.partial_receive', $purchase->id) }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Ordered</th>
                            <th>Quantity Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseDetails as $detail)
                        <tr>
                            <td>{{ $detail['product_id'] }}</td>
                            <td>{{ $detail['quantity'] }}</td>
                            <td>
                                <input type="number" name="received_quantity[{{ $detail['product_id'] }}]" min="0" max="{{ $detail['quantity'] }}" class="form-control">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary mt-6">Receive Product</button>
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
@endpush