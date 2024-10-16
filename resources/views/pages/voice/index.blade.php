
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

@php 
    $user = Auth::user();
    $dataLogin = json_decode(Auth::user()->permission); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
@endphp
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Voice Of Guardians</h6>
                    
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
                            <th>Project</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                           
                        </tr>
                    </thead>
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
                    <input type="file" class="form-control mb-2" name="csv_file" required>
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
<script>
    let jenis_kelamin = "";
    let sertifikasi = "";
    let bpjs = "";
    let jabatan = "";

    document.getElementById('search').addEventListener('click', function () {
        jenis_kelamin = $("#jenis_kelamin").val();
        sertifikasi = $("#sertifikasi").val();
        bpjs = $("#bpjs").val();
        jabatan = $("#jabatan").val();

        // Reload the DataTable with the updated jenis_kelamin parameter
        $('#dataTableExample').DataTable().ajax.url("{{ route('employee.index') }}?jenis_kelamin=" + jenis_kelamin +"&sertifikasi=" + sertifikasi +"&bpjs=" + bpjs +"&jabatan=" + jabatan).load();
    });

    $(document).ready(function() {
        $('#dataTableExample').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('employee.index') }}",
                data: function (d) {
                    d.jenis_kelamin = jenis_kelamin; // Pass the jenis_kelamin parameter
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama', name: 'nama' },
                { data: 'nik', name: 'nik' },
                { data: 'jenis_kelamin', name: 'jenis_kelamin' },
                { data: 'organisasi', name: 'organisasi' },
                { data: 'jabatan', name: 'jabatan' }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return meta.row + 1; // Return the row number
                    }
                }
            ],
            order: [[1, 'asc']]
        });
    });


</script>
@endpush