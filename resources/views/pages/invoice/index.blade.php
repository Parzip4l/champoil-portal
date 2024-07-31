<!-- resources/views/pages/invoice/index.blade.php -->

@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Invoice</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <a href="{{route('invoice.create')}}" class="btn btn-primary">Buat Invoice</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="invoiceTable" class="table">
                    <thead>
                        <tr>
                            <th>Invoice Code</th>
                            <th>Client</th>
                            <th>Tanggal</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal for creating a new invoice -->
<!-- Include your modal HTML here -->

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script>
$(document).ready(function() {
    $('#invoiceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('invoice.index') }}',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'client', name: 'client' },
            { data: 'date', name: 'date' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
<script>
    function viewInvoice(id) {
        // Redirect to the invoice view page
        window.location.href = '/invoice/' + id;
    }

    function printInvoice(id) {
        // Open a new window for printing
        window.open('/invoices/' + id + '/print', '_blank');
    }

    function editInvoice(id)
    {
        window.location.href = '/invoice/' + id + '/edit';
    }
</script>
@endpush
