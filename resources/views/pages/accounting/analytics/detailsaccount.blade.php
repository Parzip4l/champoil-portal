@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item"><a href="{{route('analytics-account.index')}}">Analytics</a></li>
    <li class="breadcrumb-item"><a href="{{route('analytics-account.index')}}">Analytics Account</a></li>
    <li class="breadcrumb-item active" aria-current="page">[{{$dataaccount->code}}] - {{$dataaccount->name}}</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-2">Analytics Account</p>
                            <h3 style="text-transform:uppercase;">{{$dataaccount->name}}</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-2">{{$dataaccount->code}}</p>
                            <span class="badge bg-success">
                                @php
                                    // Ambil nama produk berdasarkan product_id
                                    $accountPlan = \App\AnalyticsPlans::find($dataaccount->plan)->name;
                                @endphp
                                {{$accountPlan}}
                            </span>
                        </div>
                    </div>
                </div>
                
                <ul class="nav nav-tabs nav-tabs-line mt-2" id="lineTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="gross-line" data-bs-toggle="tab" data-bs-target="#grossline" role="tab" aria-controls="grossline" aria-selected="true">Gross Margin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Customer Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="vendor-bill-line" data-bs-toggle="tab" data-bs-target="#vendrobill" role="tab" aria-controls="vendrobill" aria-selected="false">Vendor Bills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="purchase-line-tab" data-bs-toggle="tab" data-bs-target="#purchase" role="tab" aria-controls="purchase" aria-selected="false">Purchase</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="lineTabContent">
                    <div class="tab-pane fade show active" id="grossline" role="tabpanel" aria-labelledby="gross-line">

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">

                    </div>
                    <div class="tab-pane fade" id="vendrobill" role="tabpanel" aria-labelledby="vendor-bill-line">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Vendor</th>
                                        <th>Bill Date</th>
                                        <th>Due Date</th>
                                        <th>Total</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendorBills as $Vendor)
                                    @php
                                        // Ambil nama produk berdasarkan product_id
                                        $NamaPertner = \App\ContactM::find($Vendor->vendor)->name;
                                        $purchase = \App\Purchase::find($Vendor->purchase_id);
                                    @endphp
                                    <tr>
                                        <td><a href="{{route('vendor-bills.show', $Vendor->id)}}">{{$Vendor->code }}</a></td>
                                        <td>{{$NamaPertner }}</td>
                                        <td>{{$Vendor->bill_date }}</td>
                                        <td>{{$Vendor->due_date }}</td>
                                        <td>
                                            @if ($purchase)
                                                {{ 'Rp ' . number_format($purchase->total, 0, ',', '.') }}
                                            @else
                                                Purchase not found
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $Vendor->payment_status === 'Paid' ? 'bg-success' : 'bg-danger' }}">
                                                {{$Vendor->payment_status}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $Vendor->status === 'Posted' ? 'bg-success' : '' }}">
                                                {{$Vendor->status}}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="purchase" role="tabpanel" aria-labelledby="purchase-line-tab">
                        <div class="table-responsive">
                            <table id="DataTablePurchase" class="table">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Confirmation Date</th>
                                        <th>Vendor</th>
                                        <th>Total</th>
                                        <th>Expected Arrival</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Purchase as $purchase)
                                    <tr>
                                        <td><a href="{{route('purchase.show', $purchase->id )}}">{{$purchase->code }}</a></td>
                                        <td>{{$purchase->created_at }}</td>
                                        <td>
                                            @php
                                                // Ambil nama produk berdasarkan product_id
                                                $NamaPertner = \App\ContactM::find($purchase->vendor)->name;
                                            @endphp
                                            {{$NamaPertner }}
                                        </td>
                                        <td>{{ 'Rp ' . number_format($purchase->total, 0, ',', '.') }}</td>
                                        <td>
                                            {{$purchase->expected_arrival }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush
@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script>
    $(function() {
    'use strict';

    $(function() {
        $('#DataTablePurchase').DataTable({
        "aLengthMenu": [
            [10, 30, 50, -1],
            [10, 30, 50, "All"]
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        }
        });
        $('#DataTablePurchase').each(function() {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.removeClass('form-control-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
        });
    });

    });
</script>
@endpush