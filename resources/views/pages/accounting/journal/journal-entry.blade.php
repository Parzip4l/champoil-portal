@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item"><a href="#">Miscellaneous</a></li>
    <li class="breadcrumb-item active" aria-current="page">Journal Items</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Journal Items</h6>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Code</th>
                <th>Partner</th>
                <th>Reference</th>
                <th>Journal</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($journal as $data)
                <tr>
                    <td> {{ \Carbon\Carbon::parse($data->created_at)->format('d F Y') }} </td>
                    <td> {{ $data->code }} </td>
                    <td> {{ $data->partnername }} </td>
                    @if($data->purchasecode)
                        <td><a href="{{ route('purchase.show', $data->reference) }}">{{ $data->purchasecode }}</a></td>
                    @else
                        <td><a href="{{ route('invoice.show', $data->reference) }}">{{ $data->salescode }}</a></td>
                    @endif
                    <td> {{ $data->journalname }} </td>
                    <td> Rp. {{ number_format($data->total, 0, '.', '.') }} </td>
                    <td>
                        <span class="badge bg-success">
                            {{ $data->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush