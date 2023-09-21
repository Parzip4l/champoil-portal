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
    <li class="breadcrumb-item active" aria-current="page">Create Vendor Bills</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="card-title">Vendor Bills</h6>
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

                @if ($existingVendorBill && $existingVendorBill->payment_status == 'Paid')
                    <div class="alert alert-success">
                        <strong>Paid</strong> - This Vendor bill has been paid.
                    </div>
                @endif
                </div>
            </div>
        </div>
        <div class="card-body-wrap">
            <div class="form">
                <!-- Data Form -->
                <form action="{{route('vendor-bills.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="code" class="form-label">Vendor Bill</label>
                                @php
                                    $existingBill = \App\VendorBill::where('code', $billCode)->first();
                                    if ($existingBill) {
                                        $billCodeFromDatabase = $existingBill->code;
                                    } else {
                                        // Jika belum ada, gunakan kode yang baru
                                        $billCodeFromDatabase = $billCode;
                                    }
                                @endphp
                                <input type="text" class="form-control" name="code" value="{{ $existingVendorBill ? $existingVendorBill->code : $billCode }}" readonly required>
                                <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="code" class="form-label">Vendor Name</label>
                                <input type="text" class="form-control" value="{{$vendor->name}}" readonly required>
                                <input type="hidden" class="form-control" name="vendor" value="{{$vendor->id}}" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="code" class="form-label">Bill Date</label>
                                <input type="text" class="form-control" name="bill_date" value="{{ date('Y-m-d') }}" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="code" class="form-label">Accounting Date</label>
                                <input type="text" class="form-control" name="accounting_date" value="{{ date('Y-m-d') }}" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="code" class="form-label">Recipient Bank</label>
                                <select name="bank_receipt" id="" class="form-control">
                                    <option value="1" {{ $existingVendorBill && $existingVendorBill->bank_receipt == 1 ? 'selected' : '' }}>1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="expected_arrival" class="form-label">Due Date</label>
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" class="form-control" placeholder="Select date" name="due_date" value="{{ $existingVendorBill ? $existingVendorBill->due_date : '' }}" data-input required>
                                    <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                                </div>
                                @if (session('error'))
                                <p id="due_date_error" style="color: red;">{{ session('error') }}</p>
                                @endif
                            </div>
                        </div>
                                <input type="hidden" name="journal" value="Vendor Bills" readonly class="form-control">
                                <input type="hidden" name="purchase_details" value="{{ json_encode($productDetails) }}">
                                <input type="hidden" name="payment_status" value="Not Paid">
                                <input type="hidden" name="status" value="Posted">
                                <input type="hidden" name="created_by" value="{{ auth()->user()->name }}">
                        <div class="col-md-12 mb-2">
                        @php
                            $existingVendorBill = \App\VendorBill::where('purchase_id', $purchase->id)->first();
                            $paymentStatus = $existingVendorBill ? $existingVendorBill->payment_status : null;
                        @endphp
                        @if (!$existingVendorBill)
                        <button class="btn btn-primary" type="submit">Confirm Vendor Bill</button>
                        @else
                        <button class="btn btn-primary" type="submit" disabled>Confirm Vendor Bill</button>
                        @endif
                        @if (!$existingVendorBill || $paymentStatus !== 'Paid')
                            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target=".regist-payment-modal">Create Payment</a>
                        @endif
                        </div>
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
                                    <table class="table table-bordered" id="productTable">
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
                                                        // Ambil nama produk berdasarkan product_id
                                                        $AnalyticsName = \App\AnalyticsAccount::find($detail['analytics'])->name;
                                                    @endphp
                                                    {{ $AnalyticsName }}
                                                </td>
                                                <td>{{ $detail['subtotal'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="title-total mt-3" style="text-align:right;">
                                        <h4>Total : Rp.{{ number_format($purchase->total, 0, ',', '.') }}</h4>
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
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Amount</label>
                            <input type="text" class="form-control" value="Rp.{{ number_format($purchase->total, 0, ',', '.') }}">
                            <input type="hidden" name="amount" value="{{$purchase->total}}">
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
                            <input type="text" name="memo" class="form-control" value="{{ $existingVendorBill ? $existingVendorBill->code : $billCode }}">
                            <input type="hidden" name="vendor_bill_id" value="{{ $existingVendorBill ? $existingVendorBill->id : $billCode }}">
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
<script>
    document.getElementById('due_date').addEventListener('blur', function() {
        var dueDateInput = document.getElementById('due_date');
        var dueDateError = document.getElementById('due_date_error');
        
        if (dueDateInput.value === '') {
            dueDateError.textContent = 'Due Date is required.';
        } else {
            dueDateError.textContent = ''; // Clear any previous error message
        }
    });
</script>
@endpush

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush