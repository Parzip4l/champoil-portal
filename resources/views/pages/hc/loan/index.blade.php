@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Data Pinjaman Karyawan</h5>
                <!-- <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#JabatanModal">Tambah Data Pinjaman</a> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Karyawan</th>
                                <th>Bulan Pinjam</th>
                                <th>Bulan Akhir Pinjaman</th>
                                <th>Jumlah Pijaman</th>
                                <th>Potongan Perbulan</th>
                                <th>Sisa Pinjaman</th>
                                
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($Loandata as $data)
                            <tr>
                                <td>{{ $nomor++ }}</td>
                                @php 
                                    // Ambil nama produk berdasarkan product_id
                                    $Employee = \App\Employee::where('nik',$data->employee_id)->first();
                                    $due_date = $data->installments-1;
                                    $endDate = $data->created_at->copy()->addMonths($due_date);
                                @endphp
                                <td><a href="#" data-bs-toggle="modal" data-bs-target="#DetailsLoanModal{{ $data->id }}">{{ @$Employee->nama }}</a></td>
                                <td>{{ $data->created_at->format('F Y') }}</td>
                                <td>{{ $endDate->format('F Y') }}</td>
                                <td>Rp {{ number_format($data->amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->installment_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->remaining_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($data->is_paid)
                                        Lunas
                                    @else
                                        Belum Lunas
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#DetailsLoanModal{{ $data->id }}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <form action="#" method="POST" id="delete_contact" class="contactdelete">
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
                            <div class="modal fade bd-example-modal-xl" id="DetailsLoanModal{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pinjaman {{$Employee->nama}} {{ $data->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('employee-loan.update', $data->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Nama Karyawan</label>
                            <input type="name" name="" class="form-control" value="{{$Employee->nama}}" required>          
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Jumlah Pinjaman</label>
                            <input type="number" name="amount" class="form-control" value="{{$data->amount}}" required>   
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Tenor Pinjaman</label>
                            <input type="number" name="installments" class="form-control" value="{{$data->installments}}" required>   
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Sisa Pinjaman</label>
                            <input type="number" name="remaining_amount" class="form-control" value="{{$data->remaining_amount}}" required>   
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="" class="form-label">Status Pinjaman</label>
                            <select name="is_paid" class="form-control" id="">
                                <option value="1" {{$data->is_paid == '1' ? 'selected' : ''}}>Lunas</option>
                                <option value="0" {{$data->is_paid == '0' ? 'selected' : ''}}>Belum Lunas</option>
                            </select>  
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-primary w-100" type="submit">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Tambah Data Pinjaman</h5>
            </div>
            <div class="card-body">
            <form action="{{route('employee-loan.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Employee</label>
                            <select class="form-select select2" data-width="100%" name="employee_id" >
                                @foreach($karyawan as $data)
                                    <option value="{{$data->nik}}">{{$data->nama}}</option>
                                @endforeach
                            </select>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Jumlah Pinjaman</label>
                            <input type="number" name="amount" class="form-control" required>   
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Tenor Pinjaman</label>
                            <input type="number" name="installments" class="form-control" required>   
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
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
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
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
                const deleteUrl = "{{ route('employee-loan.destroy', ':id') }}".replace(':id', id);
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
    $('.select2').on('select2:open', function () {
            $('.select2-container--open').css('z-index', '99999');
        });
</script>
<style>
    span.select2-container.select2-container--default.select2-container--open {
        z-index: 9999!important;
    }
    
</style>
@endpush