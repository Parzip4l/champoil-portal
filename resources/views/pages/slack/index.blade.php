@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-line" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Slack Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="it-tab" data-bs-toggle="tab" data-bs-target="#it" role="tab" aria-controls="it" aria-selected="true">IT Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="userAccount-tab" data-bs-toggle="tab" data-bs-target="#userAccount" role="tab" aria-controls="userAccount" aria-selected="true">User Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Article-tab" data-bs-toggle="tab" data-bs-target="#Article" role="tab" aria-controls="Article" aria-selected="true">Article</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#penetrasiModal">Tambah Webhooks</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Channel Name</th>
                                        <th>Hook</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($slack as $data)
                                    <tr>
                                        <td> {{ $nomor++ }} </td>
                                        <td> {{ $data->channel }} </td>
                                        <td> {{ $data->url }} </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#EditContact{{ $data->id}}">
                                                        <i data-feather="eye" class="icon-sm me-2"></i>
                                                        <span class="">Details</span>
                                                    </a>
                                                    <form action="{{ route('contact.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
                    <div class="tab-pane fade" id="it" role="tabpanel" aria-labelledby="it-tab">
                        ..
                    </div>
                    <div class="tab-pane fade" id="userAccount" role="tabpanel" aria-labelledby="userAccount-tab">
                        ..
                    </div>
                    <div class="tab-pane fade" id="Article" role="tabpanel" aria-labelledby="Article-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#ArtikelModal">Tambah Artikel</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="DataTableArtikel" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($artikel as $data)
                                    <tr>
                                        <td> {{ $nomor++ }} </td>
                                        <td> {{ $data->title }} </td>
                                        <td> {{ $data->url }} </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#EditContact{{ $data->id}}">
                                                        <i data-feather="eye" class="icon-sm me-2"></i>
                                                        <span class="">Details</span>
                                                    </a>
                                                    <form action="{{ route('contact.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
    </div>
</div>

<!-- Modal Data FNG -->
<div class="modal fade" id="penetrasiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Slack Webhooks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('slack-account.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Channel Name</label>
                            <input type="text" class="form-control" name="channel" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">WebHook</label>
                            <input type="text" class="form-control" name="url" required>    
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End -->
<div class="modal fade" id="ArtikelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('slack-artikel.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Url</label>
                            <input type="text" class="form-control" name="url" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Tanggal Kirim</label>
                            <input type="date" class="form-control" name="tanggal_kirim" required>    
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>
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
<script>
    $(function() {
  'use strict';

  $(function() {
    $('#dataTablePCK').DataTable({
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#dataTablePCK').each(function() {
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
<script>
    $(function() {
  'use strict';

  $(function() {
    $('#DataTableArtikel').DataTable({
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#DataTableArtikel').each(function() {
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