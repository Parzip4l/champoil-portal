@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Accounting</a></li>
    <li class="breadcrumb-item"><a href="{{route('analytics-plans.index')}}">Analytics</a></li>
    <li class="breadcrumb-item active" aria-current="page">Analytics Plans</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Analytics Plans</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                    <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target=".contact">New Account</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Reference</th>
                <th>Plans</th>
                <th>Debit</th>
                <th>Credit</th>
                <td>Balance</td>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $nomor = 1;
                @endphp
                @foreach ($data as $data)
                <tr>
                    <td> <a href="{{route('analytics-account.show', $data->id)}}">{{ $data->name }}</a> </td>
                    <td> {{ $data->code }} </td>
                    <td> {{ $data->plansname }} </td>
                    @php
                        // Mengambil total debit dari journal_item berdasarkan ID analytics
                        $totalDebit = App\JournalItem::where('analytics', $data->id)->sum('debit');

                        // Mengambil total kredit dari journal_item berdasarkan ID analytics
                        $totalCredit = App\JournalItem::where('analytics', $data->id)->sum('credit');

                        $balance = $totalDebit - $totalCredit;
                    @endphp
                    <td><span class="badge bg-success">Rp. {{number_format($totalDebit, 0,',', '.')}}</span></td>
                    <td><span class="badge bg-danger">Rp. {{number_format($totalCredit, 0,',', '.')}}</span></td>
                    <td>Rp. {{number_format($balance, 0,',', '.')}}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#EditContact{{ $data->id}}">
                                    <i data-feather="edit-2" class="icon-sm me-2"></i>
                                    <span class="">Edit</span>
                                </a>
                                <form action="{{ route('account-type.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                    @csrf @method('DELETE') 
                                    <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                        <i data-feather="trash" class="icon-sm me-2"></i>
                                        <span class="">Delete</span>
                                    </a>
                                </form>
                            </div>
                        </div>
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

<!-- Modal Tambah Contact -->
<div class="modal fade bd-example-modal-lg contact" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Add New Analytics Account</h4>
            <hr>
            <form class="forms-sample" action="{{ route('analytics-account.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="name" placeholder="Account Type" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Refference</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Analytics Plans</label>
                            <select name="plan" class="form-control" id="">
                                @foreach($plan as $plan)
                                <option value="{{$plan->id}}">{{$plan->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>  
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Save Analytics Account</button>
            </form>
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
            title: 'Delete Data',
            text: 'Are You Sure You Will Delete This Data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('analytics-plans.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Analytics Plans Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Analytics Plans Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Analytics Plans to Delete',
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