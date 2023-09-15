@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Growth</a></li>
    <li class="breadcrumb-item"><a href="{{route('contact.index')}}">Contact</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $contact->name }}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row d-flex">
                <div class="col-md-6 align-self-center">
                    <h6 class="card-title mb-0">Contacts Details</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                @if ($previousContact)
                    <a href="{{ route('contact.show', $previousContact->id) }}" class="btn btn-sm btn-primary btn-icon"><i data-feather="chevron-left"></i></a>
                @endif

                @if ($nextContact)
                    <a href="{{ route('contact.show', $nextContact->id) }}" class="btn btn-sm btn-primary btn-icon"><i data-feather="chevron-right"></i></a>
                @endif
                </div>
            </div>
        </div>
        <hr>
        <!-- Content -->
        <div class="detail-contact-wrap">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-3">{{$contact->name}}</h3>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3" >Address</label>
                        <p class="text-muted">{{ $contact->address }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3" >City</label>
                        <p class="text-muted">{{ $contact->city }}</p>
                    </div>
                    <div class="address mb-3 d-flex">
                        <label for="" class="me-3" >State</label>
                        <p class="text-muted">{{ $contact->state }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="Phone mb-3 d-flex">
                        <label for="" class="me-3">Categories</label>
                        <p class="text-muted">@if ($contact->categories == 1) Individual @elseif ($contact->categories == 2) Company  @else Not Valid @endif</p>
                    </div>
                    <div class="Phone mb-3 d-flex">
                        <label for="" class="me-3">Phone</label>
                        <p class="text-muted">{{ $contact->phone }}</p>
                    </div>
                    <div class="Mobile mb-3 d-flex">
                        <label for="" class="me-3">Mobile</label>
                        <p class="text-muted">{{ $contact->mobile }}</p>
                    </div>
                    <div class="Email mb-3 d-flex">
                        <label for="" class="me-3">Email</label>
                        <p class="text-muted">{{ $contact->email }}</p>
                    </div>
                    <div class="Email mb-3 d-flex">
                        <label for="" class="me-3">Website</label>
                        <p class="text-muted">{{ $contact->website }}</p>
                    </div>
                    <div class="Email mb-3 d-flex">
                        <label for="" class="me-3">Tags</label>
                        <p class="text-muted">{{ $contact->tags }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="button-related-wrap">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="" class="btn btn-primary w-100">Sales</a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="" class="btn btn-primary w-100">Invoices</a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="" class="btn btn-primary w-100">Purchased</a>
                    </div>
                    <div class="col-md-3">
                        <a href="" class="btn btn-primary w-100">Delivery</a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="accounting-wrap">
                <h6 class="card-title mb-0">Accounting Data</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="Email mb-3 mt-3 d-flex">
                            <label for="" class="me-3">Bank</label>
                            <p class="text-muted">{{ $contact->bank}}</p>
                        </div>
                        <div class="Email mb-3 mt-3 d-flex">
                            <label for="" class="me-3">Bank Number</label>
                            <p class="text-muted">{{$contact->bank_number}}</p>
                        </div>
                        <div class="Email mb-3 mt-3 d-flex">
                            <label for="" class="me-3">Bank Name</label>
                            <p class="text-muted">{{$contact->bank_name}}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="Email mb-3 mt-3 d-flex">
                            <label for="" class="me-3">Account Receivable</label>
                            <p class="text-muted">{{$contact->account_receive}}</p>
                        </div>
                        <div class="Email mb-3 mt-3 d-flex">
                            <label for="" class="me-3">Account Payable</label>
                            <p class="text-muted">{{$contact->account_pay}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
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
<style>
    label.me-3 {
        width : 20%;
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