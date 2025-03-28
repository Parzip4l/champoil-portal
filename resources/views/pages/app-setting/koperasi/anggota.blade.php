@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row mb-2">
    <div class="breadcumb d-flex">
        <a href="{{url('koperasi')}}" class="me-1">Koperasi / </a>
        <a href="" class="text-primary">Daftar Anggota</a>
    </div>
    
</div>
<div class="row">
    <!-- Table Data Anggota -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Daftar Anggota Koperasi</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{route('export.anggota')}}" class="btn btn-success">Download Excel</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="{{ url()->current() }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="filterStatus" class="form-label">Filter Member Status</label>
                                    <select name="member_status" id="filterStatus" class="form-control">
                                        <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>All</option>
                                        <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="exit" {{ $filterStatus === 'exit' ? 'selected' : '' }}>Exit</option>
                                        <option value="onhold" {{ $filterStatus === 'onhold' ? 'selected' : '' }}>On Hold</option>
                                    </select>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6"> 
                        <form method="GET" action="{{ url()->current() }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="filterStatus" class="form-label">Filter Member Loan Status</label>
                                    <select name="loan_status" id="filterStatus" class="form-control">
                                        <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>All</option>
                                        <option value="onloan" {{ $filterStatus === 'onloan' ? 'selected' : '' }}>On Loan</option>
                                        <option value="noloan" {{ $filterStatus === 'noloan' ? 'selected' : '' }}>No Loan</option>
                                    </select>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="dataTableAnggota" class="table">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th>Saldo Simpanan</th>
                                <th>Status Pinjaman</th>
                                <th>Sisa Hutang</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggota as $dataAnggota)
                            <tr>
                                <td>{{$dataAnggota->nama}}</td>
                                <td>{{$dataAnggota->join_date}}</td>
                                @if($dataAnggota->member_status === "active")
                                <td><span class="badge bg-success">{{$dataAnggota->member_status}}</span></td>
                                @else
                                <td><span class="badge bg-danger">{{$dataAnggota->member_status}}</span></td>
                                @endif
                                <td>Rp {{ number_format($dataAnggota->saldo_simpanan ?? 0, 0, ',', '.') }}</td>
                                @if($dataAnggota->loan_status === "noloan")
                                <td><span class="badge bg-primary">{{$dataAnggota->loan_status}}</span></td>
                                @else 
                                <td><span class="badge bg-danger">{{$dataAnggota->loan_status}}</span></td>
                                @endif
                                <td>Rp {{ $dataAnggota->sisahutang == 1 ? '0' : number_format($dataAnggota->sisahutang, 0, ',', '.') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" data-bs-target="#KoperasiModal{{$dataAnggota->id}}" data-bs-toggle="modal">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>
                                            </a>
                                            @if($dataAnggota->loan_status === "onloan")
                                            <a class="dropdown-item d-flex align-items-center" href="{{ url('/download-kontrak/' . $dataAnggota->employee_code) }}" target="_blank">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Lihat Kontrak</span>
                                            </a>
                                            @endif
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

<!-- Modal Details -->
@foreach ($anggota as $dataAnggota)
<div class="modal fade" id="KoperasiModal{{$dataAnggota->id}}" tabindex="-1" aria-labelledby="KoperasiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="KoperasiModal">Edit Data Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('koperasi-page.update', $dataAnggota->id)}}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{$dataAnggota->nama}}" placholder="" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Join Date</label>
                            <input type="text" name="join_date" class="form-control" placholder="" value="{{$dataAnggota->join_date}}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Member Status</label>
                            <select name="member_status" id="" class="form-control">
                                <option value="active" {{ $dataAnggota->member_status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="deactive" {{ $dataAnggota->member_status === 'deactive' ? 'selected' : '' }}>Exit</option>
                                <option value="onhold" {{ $dataAnggota->member_status === 'onhold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Update Member</button>
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
<script>
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#dataTableExample2').DataTable();
    });
</script>
<script>
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#pinjamanPengajuanTable').DataTable();
    });
</script>

@endpush