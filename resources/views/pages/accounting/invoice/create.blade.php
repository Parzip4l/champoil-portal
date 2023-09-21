@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Invoice</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Invoice</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-12">
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
            <div class="form p-2">
                <!-- Data Form -->
                <form action="{{ route('invoice.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            @php
                                $ExistingCode = \App\Invoice::where('so_code', $SalesData->id)->first();
                                $paymentStatus = $ExistingCode ? $ExistingCode->payment_status : null;
                            @endphp
                            @if (!$ExistingCode)
                            <button class="btn btn-primary" type="submit">Confirm Invoice</button>
                            @else
                            <button class="btn btn-primary" type="submit" disabled>Confirm Invoice</button>
                            @endif
                            @if (!$ExistingCode || $paymentStatus !== 'Paid')
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target=".regist-payment-modal">Register Payment</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <h3>{{ $ExistingCode ? $ExistingCode->code : $InvoiceCode }}</h3>
                                <input type="hidden" class="form-control" name="code" value="{{$InvoiceCode}}" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Customer</label>
                                <input type="text" class="form-control" value="{{$vendor->name}}">
                                <input type="hidden" name="customer" class="form-control" value="{{$vendor->id}}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control" required>
                                <input type="hidden" name="total" class="form-control" value="{{$SalesData->total}}">
                                <input type="hidden" name="sales_team" class="form-control" value="{{$SalesData->sales_team}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Payment Reference</label>
                                <input type="text"class="form-control" value="{{$SalesData->code}}" required>
                                <input type="hidden" name="so_code" class="form-control" value="{{$SalesData->id}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control" required>
                                <input type="hidden" value="{{ json_encode($productDetails) }}" name="product_data">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Journal</label>
                                <select name="journal" id="" class="form-control">
                                    @if($journal)
                                        <option value="{{$journal->id}}">{{$journal->name}}</option>
                                    @else
                                        <option value="" disabled>No Journal found</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            
                        </div>
                        <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Invoice Lice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Journal Item</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="lineTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-resposive">
                                        <table class="table table-bordered responsive" id="dataTableExample" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Unit Price</th>
                                                    <th>Quantity</th>
                                                    <th>UOM</th>
                                                    <th>Tax</th>
                                                    <th>Analytics</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail['name'] }}</td>
                                                    <td>{{ $detail['unit_price'] }}</td>
                                                    <td>{{ $detail['quantity'] }}</td>
                                                    <td>{{ $detail['uom'] }}</td>
                                                    <td>{{ $detail['tax'] }}</td>
                                                    <td>
                                                        @php 
                                                            $Analytics = \App\AnalyticsAccount::find($detail['analytics'])->name;
                                                        @endphp
                                                        {{ $Analytics }}
                                                    </td>
                                                    <td>{{ $detail['subtotal'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="title-total mt-3" style="text-align:right;">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">...</div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Regist Payment Modal -->

<div class="modal fade bd-example-modal-lg regist-payment-modal" tabindex="-1" aria-labelledby="ModalRegistPayment" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Regist Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('payment-regist.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Journal</label>
                            <select name="journal" id="" class="form-control">
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method" id="" class="form-control">
                                <option value="1">Manual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Recipient Bank Account</label>
                            <select name="recipient_bank_account" id="" class="form-control">
                                <option value="1">Manual</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <button type="submit" class="btn btn-primary w-100">Create Payment</button>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- End Regist Payment Modal -->
@endsection

@push('plugin-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush