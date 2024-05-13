@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
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
                        <h5 class="me-2">Potongan Pinjaman</h5>
                        <p>{{$data->potongan}} %</p>
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
                        <h6 class="card-title mb-0">Daftar Pengajuan Anggota</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Employee Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggotaPending as $pendingdata)
                            <tr>
                                <td>{{$pendingdata->id}}</td>
                                <td>{{$pendingdata->nama}}</td>
                                <td>
                                    <div class="dropdown">
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Approve</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" data-bs-target="" data-bs-toggle="modal">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Reject</span>
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
    <!-- Table Data Anggota -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Daftar Anggota Koperasi</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="#" class="btn btn-primary" data-bs-target="#KoperasiMember" data-bs-toggle="modal">Add Member</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableAnggota" class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Member Name</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th>Loan Limit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggota as $dataAnggota)
                            <tr>
                                <td>{{$dataAnggota->id}}</td>
                                <td>{{$dataAnggota->nama}}</td>
                                <td>{{$dataAnggota->join_date}}</td>
                                <td>{{$dataAnggota->member_status}}</td>
                                <td>{{$dataAnggota->loan_limit}}</td>
                                <td>
                                    <div class="dropdown">
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Approve</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" data-bs-target="" data-bs-toggle="modal">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Reject</span>
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
                            <label for="" class="form-label">Pesyaratan</label>
                            <input type="text" name="persayaratan" class="form-control" placholder="" required>
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
                const deleteUrl = "{{ route('company.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Company Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Company Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Company Failed to Delete',
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
@endpush