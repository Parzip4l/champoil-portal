@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <a href="{{route('dashboard.koperasi')}}" class="w-100">
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="content-card-custom-button text-center">
                        <div class="content-koperasi">
                            <h4 class="text-primary">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h4>
                            <p class="text-muted" style="font-size : 12px;">Saldo Total Simpanan Wajib</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <a href="{{route('anggota.page')}}" class="w-100">
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="content-card-custom-button text-center">
                        <div class="content-koperasi">
                            <h4 class="text-primary">{{$anggota}} </h4>
                            <p class="text-muted" style="font-size : 12px;">Total Anggota Koperasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <a href="{{route('pendinganggota.page')}}" class="w-100">
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="content-card-custom-button text-center">
                        <div class="content-koperasi">
                            <h4 class="text-primary">{{$anggotapending}}</h4>
                            <p class="text-muted" style="font-size : 12px;">Total Pengajuan Anggota</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <a href="{{route('pinjamananggota.page')}}" class="w-100">
            <div class="card custom-card2">
                <div class="card-body">
                    <div class="content-card-custom-button text-center">
                        <div class="content-koperasi">
                            <h4 class="text-primary">{{$pinjaman}}</h4>
                            <p class="text-muted" style="font-size : 12px;">Total Pengajuan Pinjaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Koperasi Settings</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Cek Data -->
                        @if($koperasi->isNotEmpty())
                            @foreach($koperasi as $item)
                                <a href="#" class="btn btn-primary" data-bs-target="#KoperasiModalEdit{{ $item->id }}" data-bs-toggle="modal">Edit Settings</a>
                            @endforeach
                        @else
                            <a href="#" class="btn btn-primary" data-bs-target="#KoperasiModal" data-bs-toggle="modal">Add Settings</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="setting-wrap">
                    @foreach($koperasi as $data)
                    <div class="details-data d-flex mb-2">
                        <h5 class="me-2">Company Name</h5>
                        <p>{{$data->company}}</p>
                    </div>

                    <div class="details-data d-flex mb-2">
                        <h5 class="me-2">Membership</h5>
                        <p>{{$data->membership}}%</p>
                    </div>

                    <div class="details-data d-flex mb-2">
                        <h5 class="me-2">Merchendise</h5>
                        <p>{{$data->merchendise}}%</p>
                    </div>

                    <div class="details-data d-flex mb-2">
                        <h5 class="me-2">Tenor Pinjaman</h5>
                        <p>{{$data->tenor}} Bulan</p>
                    </div>

                    <div class="details-data d-flex mb-2">
                        <h5 class="me-2">Iuran Wajib</h5>
                        <p>Rp {{ number_format($data->iuran, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Loan Settings</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Add Setting -->
                        <a href="#" class="btn btn-primary" data-bs-target="#SettingsLoan" data-bs-toggle="modal">Add Settings</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                    <table id="dataTableExample2" class="table">
                        <thead>
                            <tr>
                                <th>Min Saving</th>
                                <th>Max Saving</th>
                                <th>Max Limit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loansettings as $data)
                            <tr>
                                <td>Rp {{ number_format($data->min_saving, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->max_saving, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->max_limit, 0, ',', '.') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="" data-bs-target="#SettingsLoan{{$data->id}}" data-bs-toggle="modal">
                                                <i data-feather="edit" class="icon-sm me-2"></i> <span class="">Edit</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                <i data-feather="trash" class="icon-sm me-2"></i>
                                                <span class="">Delete</span>
                                            </a>
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

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Data Anggota OnLoan</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample23" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Join Date</th>
                                <th>Sisa Hutang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggotaOnLoan as $data)
                            <tr>
                                @php 
                                    $employeeName = \App\Employee::where('nik',$data->employee_code)->first();
                                @endphp
                                <td>{{$employeeName->nama}}</td>
                                <td>{{$data->join_date}}</td>
                                <td>Rp {{ number_format($data->sisahutang, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Add Setting -->
<div class="modal fade" id="KoperasiModal" tabindex="-1" aria-labelledby="KoperasiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="KoperasiModal">Add Setting Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('koperasi.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Potongan</label>
                            <input type="number" name="potongan" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Iuran Wajib</label>
                            <input type="number" name="iuran" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Membership</label>
                            <input type="number" name="membership" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Merchendise</label>
                            <input type="number" name="merchendise" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Tenor Pinjaman</label>
                            <input type="number" name="tenor" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Terms & Condition</label>
                            <textarea name="persayaratan" id="" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Add Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loan Settings -->
@foreach ($loansettings as $data)
<div class="modal fade" id="SettingsLoan{{$data->id}}" tabindex="-1" aria-labelledby="SettingsLoan" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SettingsLoan">Add Loan Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('loan-settings.update', $data->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Min Saving</label>
                            <input type="number" name="min_saving" class="form-control" placholder="" value="{{$data->min_saving}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Max Saving</label>
                            <input type="number" name="max_saving" class="form-control" placholder="" value="{{$data->max_saving}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Max Loan</label>
                            <input type="number" name="max_limit" class="form-control" placholder="" value="{{$data->max_limit}}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Update Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- End Modal -->

<!-- Update Setting Loan -->
<div class="modal fade" id="SettingsLoan" tabindex="-1" aria-labelledby="SettingsLoan" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SettingsLoan">Add Loan Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('loan-settings.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Min Saving</label>
                            <input type="number" name="min_saving" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Max Saving</label>
                            <input type="number" name="max_saving" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Max Loan</label>
                            <input type="number" name="max_limit" class="form-control" placholder="" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Add Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Setting -->
@foreach($koperasi as $item)
<div class="modal fade" id="KoperasiModalEdit{{$item->id}}" tabindex="-1" aria-labelledby="KoperasiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="KoperasiModal">Add Setting Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('koperasi.update', $item->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Potongan</label>
                            <input type="number" name="potongan" class="form-control" placholder="" value="{{$item->potongan}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Iuran Wajib</label>
                            <input type="number" name="iuran" class="form-control" placholder="" value="{{$item->iuran}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Membership</label>
                            <input type="number" name="membership" class="form-control" placholder="" value="{{$item->membership}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Merchendise</label>
                            <input type="number" name="merchendise" class="form-control" placholder="" value="{{$item->merchendise}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Tenor Pinjaman</label>
                            <input type="number" name="tenor" class="form-control" placholder="" value="{{$item->tenor}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Pesyaratan</label>
                            <input type="text" name="persayaratan" class="form-control" placholder="" value="{{$item->persayaratan}}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Update Settings</button>
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

<!-- Delete Company -->
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
                const deleteUrl = "{{ route('loan-settings.destroy', ':id') }}".replace(':id', id);
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
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#dataTableAnggota').DataTable();
    });
</script>
<script>
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#dataTableExample2').DataTable();
    });
</script>
<script>
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#dataTableExample23').DataTable();
    });
</script>

@endpush