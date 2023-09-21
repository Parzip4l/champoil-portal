@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item"><a href="{{route('vendor-bills.index')}}">Vendor Bills</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$vendor_bills->code}}</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <h3>{{$vendor_bills->code}}</h3>
                            </div>
                            <div class="col-md-6 text-right align-self-center">
                                @if ($vendor_bills->payment_status == 'Paid')
                                    <span class="badge bg-success" style="padding: 8px 60px;">Paid</span>
                                @else
                                <a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target=".regist-payment-modal">Create Payment</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body-wrap">
                    <div class="form">
                        <!-- Data Form -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Vendor Name</label>
                                    <p class="text-muted">
                                        @php
                                            // Ambil nama produk berdasarkan product_id
                                            $VendorName = \App\ContactM::find($vendor_bills->vendor)->name;
                                        @endphp 
                                        {{ $VendorName }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Bill Date</label>
                                    <p class="text-muted">{{ $vendor_bills->bill_date }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Purchase Source</label>
                                    <p class="text-muted"><a href="{{ route('purchase.show', $vendor_bills->purchaseid) }}">{{ $vendor_bills->purchasecode }}</a></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Accounting Date</label>
                                    <p class="text-muted">{{ $vendor_bills->accounting_date }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                    <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Recipient Bank</label>
                                    <p class="text-muted">{{ $vendor_bills->bank_receipt }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                    <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Due Date</label>
                                    <p class="text-muted">{{ $vendor_bills->due_date }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                    <div class="address mb-3 d-flex">
                                    <label for="" class="me-3">Journal</label>
                                    <p class="text-muted">{{ $vendor_bills->journal }}</p>
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
                                            <th>UOM</th>
                                            <th>Tax</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $detaildata = json_decode($vendor_bills->purchase_details, true)
                                        @endphp
                                        @foreach($detaildata as $details)
                                        <tr>
                                            <td>{{$details['name']}}</td>
                                            <td>Rp. {{number_format($details['unit_price'], 0,',', '.')}}</td>
                                            <td>{{$details['quantity']}}</td>
                                            <td>{{$details['uom']}}</td>
                                            <td>{{$details['tax']}}</td>
                                            <td>Rp. {{number_format($details['subtotal'], 0,',', '.')}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <hr>
                                <div class="title-total mt-3" style="text-align:right;">
                                    <h4>TOTAL : Rp.{{ number_format($vendor_bills->total, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4>Log Activities</h4>
            </div>
            <div class="card-body">
                <div class="history">
                    @foreach($userActivities as $item)
                    <div class="item">
                        <span class="@if ($item->action === 'Created') text-success @elseif ($item->action === 'Deleted') text-danger @elseif ($item->action === 'Updated') text-warning @endif">{{ $item->action }} By</span> - {{ $item->username }} - {{ $item->created_at }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Amount</label>
                            <input type="text" class="form-control" value="Rp.{{ number_format($vendor_bills->total, 0, ',', '.') }}">
                            <input type="hidden" name="amount" value="{{$vendor_bills->total}}">
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
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Memo</label>
                            <input type="text" name="memo" class="form-control" value="{{$vendor_bills->code}}">
                            <input type="hidden" name="vendor_bill_id" value="{{$vendor_bills->id}}">
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
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<style>
    label.me-3 {
        width : 30%;
    }
    .detail-contact-wrap p.text-muted {
        width : 80%;
    }

    .accounting-wrap label.me-3 {
        width : 25%;
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