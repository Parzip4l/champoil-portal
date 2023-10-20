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
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Penetrasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">KUHL</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#penetrasiModal">Tambah Data Baru</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Batch Code</th>
                                        <th>Product</th>
                                        <th>Penetrasi</th>
                                        <th>Checker</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($penetrasi as $data)
                                    <tr>
                                        <td> {{$nomor++}} </td>
                                        <td> {{ $data->created_at }} </td>
                                        <td> {{ $data->batch }} </td>
                                        <td> {{ $data->product }} </td>
                                        <td> {{ $data->p_fng }} </td>
                                        <td> {{ $data->checker }} </td>
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
                                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#penetrasiModal{{ $data->id}}">
                                                        <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                        <span class="">Edit</span>
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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#kuhlModal">Tambah Data Baru</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTableRMA" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Batch Code</th>
                                        <th>Ph</th>
                                        <th>Keterangan</th>
                                        <th>Checker</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($kuhl as $data)
                                    <tr>
                                        <td> {{$nomor++}} </td>
                                        <td> {{ $data->created_at }} </td>
                                        <td> {{$data->batch }}</td>
                                        <td> {{$data->ph }}</td>
                                        <td> {{$data->keterangan }}</td>
                                        <td> {{$data->checker }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#kuhlModal{{ $data->id}}">
                                                        <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                        <span class="">Edit</span>
                                                    </a>
                                                    <form action="{{ route('contact.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                                        @csrf @method('DELETE') 
                                                        <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialogKuhl('{{ $data->id }}')">
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
                <h5 class="modal-title" id="exampleModalLabel">Buat Data Penetrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('rnd-check.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Batch Code</label>
                            <input type="text" class="form-control" name="batch" required>    
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Produk</label>
                            <select name="product" id="" class="form-control">
                                <option value="Supreme">Supreme</option>
                                <option value="Super">Super</option>
                                <option value="Optima">Optima</option>
                                <option value="Heavy Loader">Heavy Loader</option>
                                <option value="Xtreme">Xtreme</option>
                                <option value="Power">Power</option>
                                <option value="Activ">Activ</option>
                                <option value="Multi Purpose">Multi Purpose</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Penetrasi Proses</label>
                            <input type="number" class="form-control" name="p_process" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan Penetrasi Proses</label>
                            <input type="text" class="form-control" name="k_process" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Penetrasi Finish Goods</label>
                            <input type="number" class="form-control" name="p_fng" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan Finish Goods</label>
                            <input type="text" class="form-control" name="k_fng" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Checker</label>
                            <select name="checker" class="form-control" id="">
                                <option value="Alfiandri">Alfiandri</option>
                                <option value="Leo">Leo</option>
                                <option value="Yovilianda">Yovilianda</option>
                            </select>
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

<!-- Modal Data PCK -->
<div class="modal fade" id="kuhlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data FNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('rnd-check-kuhl.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Batch Code</label>
                            <input type="text" class="form-control" name="batch" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Ph</label>
                            <input type="text" class="form-control" name="ph" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Checker</label>
                            <select name="checker" class="form-control" id="">
                                <option value="Alfiandri">Alfiandri</option>
                                <option value="Leo">Leo</option>
                                <option value="Yovilianda">Yovilianda</option>
                            </select>
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

<!-- Modal Edit Kuhl -->
@foreach($kuhl as $editkuhl)
<div class="modal fade" id="kuhlModal{{$editkuhl->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data FNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('rnd-check-kuhl.update', $editkuhl->id )}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Batch Code</label>
                            <input type="text" class="form-control" name="batch" value="{{$editkuhl->batch}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Ph</label>
                            <input type="text" class="form-control" name="ph" value="{{$editkuhl->ph}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="{{$editkuhl->keterangan}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Checker</label>
                            <select name="checker" class="form-control" id="">
                                <option value="Alfiandri" {{$editkuhl->checker == 'Alfiandri' ? 'selected' : ''}}>Alfiandri</option>
                                <option value="Leo" {{$editkuhl->checker == 'Leo' ? 'selected' : ''}}>Leo</option>
                                <option value="Yovilianda" {{$editkuhl->checker == 'Yovilianda' ? 'selected' : ''}}>Yovilianda</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($penetrasi as $dataPenetrasi)
<!-- Modal Edit Penetrasi -->
<div class="modal fade" id="penetrasiModal{{$dataPenetrasi->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data Penetrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('rnd-check.update', $dataPenetrasi->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Batch Code</label>
                            <input type="text" class="form-control" name="batch" value="{{$dataPenetrasi->batch}}" required>    
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Produk</label>
                            <select name="product" id="" class="form-control">
                                <option value="Supreme" {{$dataPenetrasi->product == 'Supreme' ? 'selected' : ''}}>Supreme</option>
                                <option value="Super" {{$dataPenetrasi->product == 'Super' ? 'selected' : ''}}>Super</option>
                                <option value="Optima" {{$dataPenetrasi->product == 'Optima' ? 'selected' : ''}}>Optima</option>
                                <option value="Heavy Loader" {{$dataPenetrasi->product == 'Heavy Loader' ? 'selected' : ''}}>Heavy Loader</option>
                                <option value="Xtreme" {{$dataPenetrasi->product == 'Xtreme' ? 'selected' : ''}}>Xtreme</option>
                                <option value="Power" {{$dataPenetrasi->product == 'Power' ? 'selected' : ''}}>Power</option>
                                <option value="Activ" {{$dataPenetrasi->product == 'Activ' ? 'selected' : ''}}>Activ</option>
                                <option value="Multi Purpose" {{$dataPenetrasi->product == 'Multi Purpose' ? 'selected' : ''}}>Multi Purpose</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Penetrasi Proses</label>
                            <input type="number" class="form-control" name="p_process" value="{{$dataPenetrasi->p_process}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan Penetrasi Proses</label>
                            <input type="text" class="form-control" name="k_process" value="{{$dataPenetrasi->k_process}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Penetrasi Finish Goods</label>
                            <input type="number" class="form-control" name="p_fng" value="{{$dataPenetrasi->p_fng}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Keterangan Finish Goods</label>
                            <input type="text" class="form-control" name="k_fng" value="{{$dataPenetrasi->k_fng}}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Checker</label>
                            <select name="checker" class="form-control" id="">
                                <option value="Alfiandri" {{$dataPenetrasi->checker == 'Alfiandri' ? 'selected' : ''}}>Alfiandri</option>
                                <option value="Leo" {{$dataPenetrasi->checker == 'Leo' ? 'selected' : ''}}>Leo</option>
                                <option value="Yovilianda" {{$dataPenetrasi->checker == 'Yovilianda' ? 'selected' : ''}}>Yovilianda</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
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
                const deleteUrl = "{{ route('rnd-check.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Data Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Data Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Data Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
<script>
    function showDeleteDataDialogKuhl(id) {
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
                const deleteUrl = "{{ route('rnd-check-kuhl.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Data Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Data Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Data Failed to Delete',
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
    $('#dataTableRMA').DataTable({
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#dataTableRMA').each(function() {
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