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
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Finish Goods</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Raw Materials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Packaging</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#fngModal">Tambah Data Baru</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($fng as $data)
                                    <tr>
                                        <td> {{$nomor++}} </td>
                                        <td> {{ $data->created_at }} </td>
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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                            <div class="col-md-12">
                            <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#rmaModal">Tambah Data Baru</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTableRMA" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($rma as $data)
                                    <tr>
                                        <td>{{$nomor++}}</td>
                                        <td> {{ $data->created_at }} </td>
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
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="row">
                            <div class="col-md-12">
                                <a href="" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#pckModal">Tambah Data Baru</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTablePCK" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $nomor = 1;
                                    @endphp
                                    @foreach ($pck as $data)
                                    <tr>
                                        <td>{{$nomor++}}</td>
                                        <td> {{ $data->created_at }} </td>
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
<div class="modal fade" id="pckModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data PCK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('warehouse-stock-pck.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Supreme 15</label>
                            <input type="number" class="form-control" name="supreme_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Supreme 4</label>
                            <input type="number" class="form-control" name="supreme_4" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Optima</label>
                            <input type="number" class="form-control" name="optima_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Super</label>
                            <input type="number" class="form-control" name="super" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">F 300</label>
                            <input type="number" class="form-control" name="f300" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Heavy Loader</label>
                            <input type="number" class="form-control" name="heavy_loader" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">XTREME</label>
                            <input type="number" class="form-control" name="xtreme" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 15</label>
                            <input type="number" class="form-control" name="power_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 10</label>
                            <input type="number" class="form-control" name="power_10" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 4</label>
                            <input type="number" class="form-control" name="power_4" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Active 10</label>
                            <input type="number" class="form-control" name="active_10" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">WH 300</label>
                            <input type="number" class="form-control" name="wh300" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- End -->

<!-- Modal Data PCK -->
<div class="modal fade" id="fngModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data FNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('warehouse-stock.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Supreme 15</label>
                            <input type="number" class="form-control" name="supreme_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Supreme 4</label>
                            <input type="number" class="form-control" name="supreme_4" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Optima</label>
                            <input type="number" class="form-control" name="optima_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Super</label>
                            <input type="number" class="form-control" name="super" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">F 300</label>
                            <input type="number" class="form-control" name="f300" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Heavy Loader</label>
                            <input type="number" class="form-control" name="heavy_loader" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">XTREME</label>
                            <input type="number" class="form-control" name="xtreme" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 15</label>
                            <input type="number" class="form-control" name="power_15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 10</label>
                            <input type="number" class="form-control" name="power_10" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Power 4</label>
                            <input type="number" class="form-control" name="power_4" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Active 10</label>
                            <input type="number" class="form-control" name="active_10" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">WH 300</label>
                            <input type="number" class="form-control" name="wh300" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal Data RMA -->
<div class="modal fade" id="rmaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Data RMA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('warehouse-stock-rma.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Oli Bahan</label>
                            <input type="text" class="form-control" name="oli_bahan" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Oli Service</label>
                            <input type="text" class="form-control" name="oli_service" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Oli Trafo</label>
                            <input type="text" class="form-control" name="oli_trafo" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Lemak</label>
                            <input type="text" class="form-control" id="lemak" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Wandes</label>
                            <input type="text" class="form-control" id="wandes" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">PFAD</label>
                            <input type="text" class="form-control" id="pfad" required>
                        </div>
                            <input type="hidden" class="form-control" name="lemak1" id="lemak_drum" required>
                        <div class="col-md-6 mb-2">
                            <input type="hidden" class="form-control" name="wandes1" id=wandes_drum required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="hidden" class="form-control" name="pfad1" id="pfad_drum" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Kapur</label>
                            <input type="text" class="form-control" name="kapur" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Latex</label>
                            <input type="text" class="form-control" name="latex" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Minarex</label>
                            <input type="text" class="form-control" name="minarex" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Sepuhan Merah</label>
                            <input type="text" class="form-control" name="s_merah" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Sepuhan Hijau</label>
                            <input type="text" class="form-control" name="s_hijau" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Sepuhan Kuning</label>
                            <input type="text" class="form-control" name="s_kuning" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Sepuhan Biru</label>
                            <input type="text" class="form-control" name="s_biru" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Sepuhan KUHL</label>
                            <input type="text" class="form-control" name="s_kuhl" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Tackifier 2022</label>
                            <input type="text" class="form-control" name="tackifier_22" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Tackifier Champ</label>
                            <input type="text" class="form-control" name="tackifier_champ" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Natrium Bicarbonat</label>
                            <input type="text" class="form-control" name="natrium_bicarbonat" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Soda Ash</label>
                            <input type="text" class="form-control" name="soda_ash" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End -->
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
<script>
    // Fungsi untuk mengkonversi kg ke drum
    function konversiKgKeDrum(inputKg, outputDrum) {
        const kgPerDrum = 180; // Satu drum setara dengan 180 Kg

        // Mengambil nilai dalam kg dari input
        const nilaiKg = parseFloat(inputKg.value);

        // Melakukan konversi dan menampilkan hasil dalam input drum
        const nilaiDrum = nilaiKg / kgPerDrum;
        outputDrum.value = nilaiDrum.toFixed(2); // Menampilkan hanya 2 angka desimal
    }

    // Mendapatkan elemen-elemen input kg dan output drum
    const inputLemakKg = document.getElementById('lemak');
    const inputWandesKg = document.getElementById('wandes');
    const inputPfadKg = document.getElementById('pfad');
    const outputLemakDrum = document.getElementById('lemak_drum');
    const outputWandesDrum = document.getElementById('wandes_drum');
    const outputPfadDrum = document.getElementById('pfad_drum');

    // Menambahkan event listener untuk setiap input kg
    inputLemakKg.addEventListener('input', () => {
        konversiKgKeDrum(inputLemakKg, outputLemakDrum);
    });
    inputWandesKg.addEventListener('input', () => {
        konversiKgKeDrum(inputWandesKg, outputWandesDrum);
    });
    inputPfadKg.addEventListener('input', () => {
        konversiKgKeDrum(inputPfadKg, outputPfadDrum);
    });
</script>
@endpush