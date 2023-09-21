@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sales</a></li>
        <li class="breadcrumb-item"><a href="#">Invoice</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{$datainvoice->first()->code}}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-4 ps-0">
                        <a href="#" class="noble-ui-logo d-block mt-3">CHAMPOIL<span> LOGO</span></a>                 
                        <p class="mt-1 mb-1"><b>CHAMPOIL INDONESIA</b></p>
                        <p>Jl. Kapuk Kencana No.36A, RT.2/RW.3, Kapuk Muara, <br>Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14460</p>
                        <h5 class="mt-5 mb-2 text-muted">Invoice to :</h5>
                        @php
                            $CustomerData = \App\ContactM::find($datainvoice->first()->customer)->name;
                            $CustomerAddress = \App\ContactM::find($datainvoice->first()->customer)->address;
                        @endphp
                        <p>{{$CustomerData}}</p>
                        <p>{{$CustomerAddress}}</p>
                    </div>
                    <div class="col-lg-4 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">invoice</h4>
                        <h6 class="text-end mb-5 pb-4"># {{$datainvoice->first()->code}}</h6>
                        <p class="text-end mb-1">Invoice Total</p>
                        <h4 class="text-end fw-normal">Rp. {{ number_format($datainvoice->first()->total, 0, ',', '.') }}</h4>
                        <h6 class="mb-0 mt-3 text-end fw-normal mb-2"><span class="text-muted">Invoice Date :</span> {{ $datainvoice->first()->invoice_date }}</h6>
                        <h6 class="text-end fw-normal"><span class="text-muted">Due Date :</span> {{ $datainvoice->first()->due_date }}</h6>
                    </div>
                </div>
                <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Tax</th>
                                <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $detaildata = json_decode($datainvoice->first()->product_data, true);
                                    $nomor = 1;
                                @endphp
                                @foreach($detaildata as $key)
                                <tr class="text-end">
                                    <td class="text-start">{{$nomor++}}</td>
                                    <td class="text-start">{{$key['name']}}</td>
                                    <td>{{$key['quantity']}}</td>
                                    <td>Rp. {{ number_format($key['unit_price'], 0, ',', '.') }}</td>
                                    <td>{{$key['tax']}}</td>
                                    <td>Rp. {{ number_format($key['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
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
                                            <td class="text-bold-800">Total</td>
                                            <td class="text-bold-800 text-end"> Rp. {{ number_format($datainvoice->first()->total, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid w-100">
                    <a href="javascript:;" class="btn btn-primary float-end mt-4 ms-2"><i data-feather="send" class="me-3 icon-md"></i>Send Invoice</a>
                    <a href="javascript:;" class="btn btn-outline-primary float-end mt-4"><i data-feather="printer" class="me-2 icon-md"></i>Print</a>
                </div>
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
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('contact.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Contact Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Contact Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif
</script>
@endpush