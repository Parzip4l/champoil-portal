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
                <h5 class="mb-0 align-self-center">Job Aplicant</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Melamar</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Detail</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($records['records'])
                                @php 
                                    $no=1;
                                @endphp
                                @foreach($records['records'] as $row)
                                    @php 
                                    if($row['status']=="Lolos Kualifikasi"){
                                        $status="success";
                                    }else{
                                        $status="danger";
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ date('d F Y',strtotime($row['tanggal'])) }}</td>
                                        <td>{{ $row['nik'] }}</td>
                                        <td>{{ $row['nama'] }}</td>
                                        <td>
                                            <ol type="1">
                                                <li>Whatsapp : {{ $row['nomor_wa'] }}</li>
                                                <li>Usia : {{ $row['usia'] }}</li>
                                                <li>TB : {{ $row['tb'] }}</li>
                                                <li>BB : {{ $row['bb'] }}</li>
                                                <li>BMI : {{ round($row['bmi'],0) }}</li>
                                            </ol>
                                        </td>
                                        <td><span class="badge bg-{{ $status }}">{{ $row['status'] }}</span></td>
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