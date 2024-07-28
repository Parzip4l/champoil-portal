@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    ul.timeline {
    list-style-type: none;
    position: relative;
    border-left:none !important;
    background:none !important;
    margin:0px !important;
}
ul.timeline:before {
    content: ' ';
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
}
ul.timeline > li {
    margin: 20px 0;
    padding-left: 20px;
}
ul.timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
}

    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Report</h3>
            </div>
            <div class="card-body">
                <h3>{{ $task->judul}}</h3>
                <ul class="timeline">
                    @foreach($list as $row)
                    <li>
                        <a target="_blank" href="https://www.totoprayogo.com/#">{{ $row->task }}</a>
                        <a href="#" class="float-right"> - {{ date('d F Y') }}</a>
                        <p>
                            @if($row->detail)
                                
                                Petugas : {{$row->petugas->nama}} <br/>
                                Keterangan : {{$row->detail->description}}<br/>
                                @if($row->detail->status == 1)
                                    Status : <i class="link-icon text-success" data-feather="check-circle"></i>
                                @else 
                                    Status : <i class="link-icon text-danger" data-feather="x-circle"></i>
                                @endif

                            @else
                                Patroli Tidak Dilaksanakan
                            @endif
                        </p>
                    </li>   
                    @endforeach
				
				
			</ul>
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