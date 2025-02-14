@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Result Medis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <td>#</th>
                                <th>Nama</th>
                                <th>Nik</th>
                                <th>Tanggal Test</th>
                                <th>Hasil Test</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($records)
                                @php 
                                    $no=1;
                                @endphp
                                @foreach($records as $row)
                                    @php 
                                    
                                    @endphp
                                    <tr>
                                        <td>{{ $no }} </td>
                                        <td>
                                            {{ $row->detail->nama_lengkap }} 
                                        </td>
                                        <td>{{ $row->detail->nomor_induk }}</td>
                                        <td>{{ date('d F Y',strtotime($row->tanggal)) }}</td>
                                        <td>
                                            Tensi Darah : {{ $row->tensi_darah }} <br/>
                                            Narkoba : {{ $row->narkoba }} <br/>
                                            Hepatitis B ( HBsAg ) : {{ $row->hepatitis_b }} <br/>
                                            Tuberkulosis : {{ $row->tuberkulosis }} <br/>
                                        </td>
                                        <td>
                                            @if($row->status)
                                                @if($row->status === "Lolos")
                                                <span class="badge bg-success">{{ $row->status }}</span>
                                                @else
                                                <span class="badge bg-danger">{{ $row->status }}</span>
                                                @endif
                                            @else
                                                <a href="#" 
                                                class="btn btn-xs btn-outline-primary pull-right">Input Medis</a>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    @php 
                                        $no++;
                                    @endphp
                                @endforeach
                                
                            @endif
                            
                        </tbody>
                    </table>
                </div>
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
                const deleteUrl = "{{ route('list-task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'List Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'List Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'List Task Failed to Delete',
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