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
@if($employee && ($employee->unit_bisnis == 'Kas' || $employee->unit_bisnis == 'KAS'))
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">GADA PRATAMA</h5>
            </div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-success">{{ $gp }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">NON GADA PRATAMA</h5>
            </div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-secondary">{{ $non_gp }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">Nearly Expired GADA PRATAMA ( < 60 Days )</h5>
            </div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-warning">{{ $nearly_expired }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">Expired GADA PRATAMA</h5>
            </div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-danger">{{ $expired }}</p>
            </div>
        </div>
    </div> 
</div>
@endif
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Data Karyawan</h6>
                    
                </div>
                <div class="tombol-pembantu d-flex">
                    <div class="dropdown"> 
                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-lg text-muted pb-3px align-self-center" data-feather="align-justify"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center me-2" href="{{ route('employee.create') }}"><i data-feather="plus" class="icon-sm me-2"></i> Tambah Karyawan</a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i data-feather="upload" class="icon-sm me-2"></i> Import</a>
                            <a class="dropdown-item d-flex align-items-center me-2"  href="{{ route('export.employee') }}"><i data-feather="download" class="icon-sm me-2"></i> Export</a>
                            <a class="dropdown-item d-flex align-items-center me-2"  href="https://truest.co.id/wp-content/uploads/2024/02/Tamplate-Karyawan-1.xlsx"><i data-feather="file-text" class="icon-sm me-2"></i> Download Template</a>
                            @if($employee->unit_bisnis == 'Kas')
                                <a class="dropdown-item d-flex align-items-center me-2"  
                                   href="javascript:voidd(0)"
                                   id="download_sertifikat">
                                   <i data-feather="file-text" class="icon-sm me-2"></i> 
                                   Download Sertifikat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($employee && $employee->unit_bisnis == 'Kas')
                <form class='mb-3' method="GET">
                    <div class="row">
                        @csrf
                        <div class="col-md-2">
                            <select name="jenis_kelamin" class="form-control mb-2 select2" id="jenis_kelamin">
                                <option value="">Jenis Kelamin</option>
                                @if($jenis_kelamin)
                                    @foreach($jenis_kelamin as $jk)
                                        <option value="{{ $jk->jenis_kelamin }}">{{ $jk->jenis_kelamin }}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sertifikasi" class="form-control mb-2 select2" id="sertifikasi">
                                <option value="">Sertifikasi</option>
                                @if($sertifikasi)
                                    @foreach($sertifikasi as $row_sertifikasi)
                                        <option value="{{ $row_sertifikasi->sertifikasi }}">{{ $row_sertifikasi->sertifikasi }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="bpjs" class="form-control mb-2 select2" id="bpjs">
                                <option value="">BPJS</option>
                                <option value="1">Terdaftar</option>
                                <option value="0">Belum Terdaftar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="jabatan" class="form-control mb-2 select2" id="jabatan">
                                <option value="">Jabatan</option>
                                @if($jabatan)
                                    @foreach($jabatan as $row_jabatan)
                                        <option value="{{ $row_jabatan->jabatan }}">{{ $row_jabatan->jabatan }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                        <button type="button" class="btn btn-primary" id='search'>Filter</button>
                        </div>
                        
                    </div>  
                </form>
            @endif
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
                            <th>BMI</th>
                            <th>Status Karyawan</th>
                            <th>Aksi</th>
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
    $(document).ready(function() {
        $('#download_sertifikat').on('click', function() {
            axios.get('/api/v1/download-sertifikat/{{$employee->unit_bisnis}}', {
                userId: 123 // Ganti dengan parameter sesuai kebutuhan
            }, {
                responseType: 'blob' // Untuk mengunduh file biner
            }).then(function(response) {
                const url = response.data.url;  // URL returned from the server
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'sertifikasi.xlsx');  // Adjust file name if needed
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }).catch(function(error) {
                console.error('Download gagal:', error);
                alert('Gagal mengunduh sertifikat. Silakan coba lagi.');
            });
        });
    });

  </script>
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
</script>
<script>
    let jenis_kelamin = "";
    let sertifikasi = "";
    let bpjs = "";
    let jabatan = "";

    $(document).ready(function() {
        $('#search').on('click', function () {
            const jenis_kelamin = $("#jenis_kelamin").val();
            const sertifikasi = $("#sertifikasi").val();
            const bpjs = $("#bpjs").val();
            const jabatan = $("#jabatan").val();
            const baseUrl = window.location.origin;

            // Reload the DataTable with the updated parameters
            $('#dataTableExample').DataTable().ajax.url(
        baseUrl + "/employee?jenis_kelamin=" + jenis_kelamin +
        "&sertifikasi=" + sertifikasi +
        "&bpjs=" + bpjs +
        "&jabatan=" + jabatan
    ).load();
        });
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
                { data: 'nama', name: 'nama',render: function (data, type, row) {
        return `${data.toUpperCase()} <br> Slack ID : ${row.slack_id}`;
    } },
                { data: 'nik', name: 'nik' },
                { data: 'jenis_kelamin', name: 'jenis_kelamin' },
                { data: 'organisasi', name: 'organisasi' },
                { data: 'jabatan', name: 'jabatan' },
                { data: 'bmi', name: 'bmi', render: function (data, type, row) {
                    let badge = '';
                    if (data == 'N/A'){
                        badge = '<span class="badge rounded-pill bg-seconddary">Belum Melakukan Update</span>';
                    }else{
                        if (data < 18.5) {
                            badge = '<span class="badge rounded-pill bg-warning">Underweight</span>';
                        } else if (data >= 18.5 && data < 24.9) {
                            badge = '<span class="badge rounded-pill bg-success">Normal</span>';
                        } else if (data >= 25 && data < 29.9) {
                            badge = '<span class="badge rounded-pill bg-danger">Overweight</span>';
                        } else {
                            badge = '<span class="badge rounded-pill bg-dark">Obese</span>';
                        }
                    }
                    
                    return `${data} ${badge}`;
                } },
                { data: 'status_kontrak', name: 'status_kontrak' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
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