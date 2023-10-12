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
                <h5 class="mb-0 align-self-center">Data Pengajuan Attendence</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Tanggal Diajukan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($dataRequest as $data)
                            <tr>
                                @php 
                                    $employeename = \App\Employee::where('nik', $data->employee)->first();
                                @endphp
                                <td> {{$nomor++}} </td>
                                <td><a href="#" onclick="openSidebar('{{ $data }}')">{{ $employeename->nama }}</a></td>
                                <td> {{ $data->created_at }} </td>
                                <td> {{ $data->tanggal }} </td>
                                <td> {{ $data->status }} </td>
                                <td>{{$data->aprrove_status}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($data->aprrove_status !=="Approved")
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('approve.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('setujui-usulan-form-{{ $data->id }}').submit();">
                                                <i data-feather="check" class="icon-sm me-2"></i>
                                                <span class="">Approve</span>
                                            </a>
                                            @endif

                                            @if ($data->aprrove_status !=="Reject")
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('reject.request', $data->id)}}" onclick="event.preventDefault(); document.getElementById('reject-usulan-form-{{ $data->id }}').submit();">
                                                <i data-feather="x" class="icon-sm me-2"></i>
                                                <span class="">Reject</span>
                                            </a>
                                            @endif

                                            <!-- Form Approved -->
                                            <form id="setujui-usulan-form-{{ $data->id }}" action="{{ route('approve.request', $data->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <!-- Form Reject -->
                                            <form id="reject-usulan-form-{{ $data->id }}" action="{{ route('reject.request', $data->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>

                                            <a class="dropdown-item d-flex align-items-center" href="#" onclick="openSidebar('{{ $data }}')">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <form action="{{ route('contact.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar Content -->
<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">×</a>
    <div id="sidebarContent">
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
    function openSidebar(details) {
      // Update the sidebar content with the details
      document.getElementById('sidebarContent').innerHTML = `
      <h4 class="mb-3">Details</h4>
        <div class="detail-sidebar-wrap">
            <div class="form-group mb-3">
                <label for="" class="form-label">Employee</label>
                <h5>{{$employeename->nama}}</h5>
            </div>
            <div class="form-group mb-3">
                <label for="" class="form-label">Keperluan</label>
                <h5>{{$data->status}}</h5>
            </div>
            <div class="form-group mb-3">
                <label for="" class="form-label">Tanggal Keperluan</label>
                <h5>{{$data->tanggal}}</h5>
            </div>
            <div class="form-group mb-3">
                <label for="" class="form-label">Alasan</label>
                <h5>{{$data->alasan}}</h5>
            </div>
            <div class="form-group mb-3">
                <label for="" class="form-label">Tanggal Pengajuan</label>
                <h5>{{$data->created_at}}</h5>
            </div>
            <a href="{{route('dokumen.download', $data->id)}}" class="btn btn-primary mb-3">Download File Pendukung</a>
        </div>
      `;
      document.getElementById('mySidebar').style.width = '350px'; // Adjust width as needed
      document.getElementById('main').style.marginRight = '350px'; // Adjust margin as needed
    }

    function closeSidebar() {
      document.getElementById('mySidebar').style.width = '0';
      document.getElementById('main').style.marginRight = '0';
    }
  </script>
  <style>

    div#sidebarContent {
        padding: 100px 0px 0px 50px;
    }

    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        top: 0;
        right: 0;
        background-color: #fff;
        overflow-x: hidden;
        transition: 0.5s;
        z-index: 9999;
        box-shadow: 0 0 10px 0 rgba(183, 192, 206, 0.2);
        -webkit-box-shadow: 0 0 10px 0 rgba(183, 192, 206, 0.2);
        -moz-box-shadow: 0 0 10px 0 rgba(183, 192, 206, 0.2);
        -ms-box-shadow: 0 0 10px 0 rgba(183, 192, 206, 0.2);
    }
    .sidebar a.closebtn {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #000;
      display: block;
      transition: 0.3s;
    }
    .sidebar a.closebtn:hover {
      color: #ffc107;
    }
    .sidebar .closebtn {
      position: absolute;
      top: 10px;
      left: 10px;
      font-size: 36px;
      margin-right: 10px;
    }
    #main {
      transition: margin-right .5s;
    }
  </style>
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
                const deleteUrl = "{{ route('contact.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
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
</script>
@endpush