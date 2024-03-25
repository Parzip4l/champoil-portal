@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">THR Data</h5>
                <div class="button-data">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#RunTHRModal">Run THR</a>
                    <a href="{{url('component-data-thr')}}" class="btn btn-sm btn-success" >Assign Component</a>
                </div>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Karyawan</th>
                                <th>THP</th>
                                <th>tahun</th>
                                <th>Run By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($data as $thr)
                            <tr>
                                <td> {{$nomor++}} </td>
                                <td> <a href="{{route('thr.show', $thr->id )}}">{{ \App\Employee::where('nik', $thr->employee_code)->value('nama') ?? 'Nama tidak ditemukan' }}</a> </td>
                                <td>    Rp. {{ number_format($thr->thp, 0, ',', '.') }}</td>
                                <td> {{ $thr->tahun }}</td>
                                <td> {{ $thr->run_by }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('thr.show', $thr->id )}}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">View</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('thr.edit', $thr->id )}}">
                                                <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                <span class="">Edit</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{route('thr.edit', $thr->id )}}">
                                                <i data-feather="mail" class="icon-sm me-2"></i>
                                                <span class="">Kirim Email</span>
                                            </a>
                                            <form action="#" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE') 
                                                <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $thr->id }}')">
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
<!-- End -->

<!-- Run Modal -->
<div class="modal fade bd-example-modal-xl" id="RunTHRModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Run THR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('thr.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Year</label>
                                <input type="number" name="year" class="form-control" value="{{ date('Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Select Employee</label>
                                <select class="js-example-basic-multiple form-select" id="employeeSelect" name="employee_code[]" multiple="multiple" data-width="100%">
                                    @foreach ($datakaryawan as $data)
                                        @if ($data)
                                            <option value="{{$data->nik}}">{{$data->nama}}</option>
                                        @else
                                            <option value="{{$data->nik}}">Karyawan Tidak Ditemukan</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="selectAllBtn" class="form-label">Select All Employee</label><br>
                            <button type="button" class="btn btn-primary btn-sm w-100" id="selectAllBtn">Select All</button>
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
<!-- End Modal -->
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
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
                const deleteUrl = "{{ route('thr.destroy', ':id') }}".replace(':id', id);
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
    $(document).ready(function() {
        // Event listener for the "Select All" button
        $('#selectAllBtn').click(function() {
            // Select all options in the multiple select dropdown
            $('#employeeSelect option').prop('selected', true);
            // Trigger the change event to update Select2
            $('#employeeSelect').trigger('change');
        });

        // Initialize Select2
        $('.js-example-basic-multiple').select2();
    });
</script>
@endpush