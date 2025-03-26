@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div id="loading" style="display: none; text-align: center;">
    <img src="spinner.gif" alt="Loading..." style="width: 50px;">
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Employee Resign</h6>
                </div>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kode Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Organisasi</th>
                    <th>Jabatan</th>
                    <th>Status Karyawan</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1;
                    @endphp
                    @foreach ($karyawan as $data)
                <tr id="tr{{$data->id}}">
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->nama }} 
                    <div class="btn-group float-right" role="group" style="float: right;">
                        <a href="javascript:void(0)" 
                        class="btn btn-sm btn-success" 
                        onClick="downloadPaklaring({{$data->id}})">Download Paklaring</a>
                        
                        <a href="javascript:void(0)" 
                        class="btn btn-sm btn-danger" 
                        onClick="unResign({{$data->id}})">Unresign</a>
                    </div>

                    </td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ $data->organisasi }}</td>
                    <td>{{ $data->jabatan }}</td>
                    <td><span class="@if($data->status_kontrak == 'Permanent') badge rounded-pill bg-primary @else badge rounded-pill bg-success @endif">{{ $data->status_kontrak }}</span></td>
                    <td><a href="{{route('employee.show', $data->id)}}" class="btn btn-sm btn-secondary">Detail</a></td>
                    
                </tr>
                <div class="modal fade" id="resign-{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Resign</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('employee.resign')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="id" value="{{ $data->id }}">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label for="" class="form-label">Reason</label>
                                            <textarea class="form-control" id="reason" name="reason"></textarea>
                                        </div>
                                        
                                        <div class="col-md-12 mt-2">
                                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                                        </div>
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
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('import.employee') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control mb-2" name="csv_file" required accept=".xlsx">
                    <button type="submit" class="btn btn-primary w-100">Import Excel</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
                const deleteUrl = "{{ route('employee.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Employee Successfully Deleted',
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
    function downloadPaklaring(id) {
        const url = `/api/v1/paklaring/${id}`; // Define the API endpoint
        
        Swal.fire({
            title: 'Processing Export',
            text: 'Please wait while the file is being generated...',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch the file.');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            if (data.path) {
                Swal.fire({
                    title: 'Download Ready',
                    text: 'Your document is ready to download!',
                    icon: 'success',
                }).then(() => {
                    const link = document.createElement('a');
                    link.href = data.path;
                    link.download = data.file_name;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            } else {
                Swal.fire({
                    title: 'Download Failed',
                    text: 'Unable to download the file. Please try again.',
                    icon: 'error',
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                title: 'Error',
                text: 'Error fetching the file: ' + error.message,
                icon: 'error',
            });
        });
    }
    function unResign(id) {
        const url = `/api/v1/unresign/${id}`;

        Swal.fire({
            title: 'Processing Unresign',
            text: 'Please wait while the request is being processed...',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include' // Menjaga sesi jika perlu
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();

            if (data.error === false) { // Perbaikan kondisi sukses
                Swal.fire({
                    title: 'Success',
                    text: data.message || 'Unresign process completed!',
                    icon: 'success',
                }).then(() => {
                    $("tbody #tr"+id).remove();
                });
            } else {
                throw new Error(data.message || 'Unable to process your request.');
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                title: 'Error',
                text: 'An error occurred: ' + error.message,
                icon: 'error',
            });
        });
    }







</script>
@endpush