@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">Invoices</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$invoice->code}}</li>
  </ol>
</nav>
@php 
    $dataCustomer = App\KasManagement\CustomerManagement::where('company',$invoice->company)->where('name', $invoice->client)->first();
    $dataKantor = App\Company\CompanyModel::where('company_name',$invoice->company)->first();
@endphp
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid d-flex justify-content-between">
          <div class="col-lg-3 ps-0">
          <!-- <img class="wd-80 ht-80" src="{{ asset('images/company_logo/' . $dataKantor->logo) }}">        -->
            <a href="#" class="noble-ui-logo d-block mt-3">TRUEST<span>INVOICE</span></a>                 
            <p class="mt-1 mb-1"><b> Kantor Notaris Iin Titin Rohani, S.H.,M.Kn</b></p>
            <p>{{$dataKantor->company_address}}</p>
            <h5 class="mt-5 mb-2 text-muted">Invoice to :</h5>
            @php 
                $dataCustomer = App\KasManagement\CustomerManagement::where('company',$invoice->company)->where('name', $invoice->client)->first(); 
            @endphp
            <p>{{ $invoice->client }},<br> {{$dataCustomer->alamat}}</p>
          </div>
          <div class="col-lg-3 pe-0">
            <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">Invoice</h4>
            <h6 class="text-end mb-5 pb-4">{{ $invoice->code }}</h6>
            <h6 class="mb-0 mt-3 text-end fw-normal mb-2"><span class="text-muted">Invoice Date :</span> {{ $invoice->date }}</h6>
            <h6 class="text-end fw-normal"><span class="text-muted">Due Date :</span> {{ $invoice->due_date }}</h6>
          </div>
        </div>
        <div class="container-fluid mt-5 d-flex justify-content-center w-100">
          <div class="table-responsive w-100">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Unit cost</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $key => $item)
                        @if(is_array($item)) <!-- Check if $item is an array -->
                            <tr class="text-end">
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $item['title'] }}</td>
                                <td>{{ $item['qty'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 2) }}</td>
                                <td>Rp {{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="bg-light">
                        <td class="text-bold-800">Total</td>
                        <td class="text-bold-800 text-end" colspan="4">Rp {{ number_format($details['total'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <div class="container-fluid mt-5 w-100">
          <div class="row">
            <div class="col-md-6 ms-auto">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr>
                      <td>Sub Total</td>
                      <td class="text-end">Rp {{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                      <td>Tax</td>
                      <td class="text-danger text-end">(-) Rp {{ number_format($invoice->payment_made, 2) }}</td>
                    </tr>
                    <tr class="bg-light">
                      <td class="text-bold-800">Balance Due</td>
                      <td class="text-bold-800 text-end">Rp{{ number_format($total, 2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid w-100">
            <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="btn btn-outline-primary float-end mt-4"><i data-feather="printer" class="me-2 icon-md"></i>Print</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
