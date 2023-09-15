@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Delivery</a></li>
    <li class="breadcrumb-item"><a href="{{route('vendor-bills.index')}}">Delivery Orders</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Delivery Orders</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
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
                            </div>
                        </div>
                    </div>    
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

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush