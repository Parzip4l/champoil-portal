@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>E-Workplan</h3>
            </div>
            <div class="card-body">
                <ul class="nav">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('scan_qr') }}">
                            <i class="me-2 icon-lg" data-feather="grid"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="me-2 icon-lg" data-feather="book"></i>
                        </a>
                    </li>
                    
                </ul>
                <div class="list-group">
                    
                    <a href="#" class="list-group-item list-group-item-action">
                        Accidents<br/>
                        <small>List Of Accident Report</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        Assets<br/>
                        <small>List Of Assest with detail and logs</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        Daily Room Checklist<br/>
                        <small>List Of Checklist room in your area</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        Security Patrol<br/>
                        <small>List of patrol areas and today reports</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        Defect<br/>
                        <small>List of defect facility report by supervisor </small>
                    </a>
                    
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
                const deleteUrl = "{{ route('task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Task Failed to Delete',
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