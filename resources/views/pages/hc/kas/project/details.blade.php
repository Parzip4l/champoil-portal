@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Project Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Project Code</p>
                            <p class="text-muted">{{ $project->id }}</p>
                        </div>
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Project Name</p>
                            <p class="text-muted">{{ $project->name }}</p>
                        </div>
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Project Badan</p>
                            <p class="text-muted">{{ $project->badan }}</p>
                        </div>
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Project Address</p>
                            <p class="text-muted text-right">{{ $project->latitude }}, {{ $project->longtitude }}</p>
                        </div>
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Contract Start</p>
                            <p class="text-muted text-right">{{ $project->contract_start }}</p>
                        </div>
                        <div class="project-name-wrap mb-3 d-flex justify-content-between">
                            <p>Contract End</p>
                            <p class="text-muted text-right">{{ $project->end_contract }}</p>
                        </div>
                        <div class="wrap d-flex">
                            <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Import Data
                            </button>
                            <a href="https://gdurl.com/ARl7/download" class="btn btn-success">Download Template</a>
                            <a href="" class="btn btn-warning text-white mx-2" data-bs-toggle="modal" data-bs-target="#ModalUpdateProject">Edit Project</a>
                        </div>
                    </div>

                    <!-- Modal Import Project -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Data Project Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('import.excel') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" class="form-control mb-2" name="csv_file" required accept=".xlsx">
                                        <button type="submit" class="btn btn-primary w-100">Import Excel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- Modal Edit Project -->
                    <div class="modal fade" id="ModalUpdateProject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Update Data Project</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('project.update', $project->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Project Name</label>
                                            <input type="text" name="name" class="form-control" value="{{$project->name}}" required>
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Project Badan</label>
                                            <input type="text" name="badan" class="form-control" value="{{$project->badan}}" required>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Project Latitude</label>
                                            <input type="text" name="latitude" class="form-control" value="{{$project->latitude}}" required>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Project Longitude</label>
                                            <input type="text" name="longtitude" class="form-control" value="{{$project->longtitude}}" required>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Contract Start</label>
                                            <input type="date" name="contract_start" class="form-control" value="{{$project->contract_start}}" required>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="" class="form-label">Contract End</label>
                                            <input type="date" name="end_contract" class="form-control" value="{{$project->end_contract}}" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Update Data Project</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="item-details-project">
                            <div class="table-responsive">
                                <table class="table table-borderd">
                                    <thead>
                                        <tr>
                                            <th>Kebutuhan Man Power</th>
                                            <th>Monthly Rate</th>
                                            <th>Daily Rate</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projectDetails as $data)
                                        <tr>
                                            <td>{{$data->kebutuhan}} {{$data->jabatan}}</td>
                                            <td>Rp {{ number_format($data->tp_bulanan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($data->rate_harian, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalDetails{{$data->id}}">Details</a>
                                                <a href="{{route('project-details.edit', $data->id)}}" class="btn btn-warning btn-sm text-white">Edit Data</a>
                                                <form action="#" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE') 
                                                <a class="btn btn-sm btn-danger" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                    Delete
                                                </a>
                                            </form>
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
        </div>
    </div>
</div>

@foreach($projectDetails as $data)
<div class="modal fade bd-example-modal-xl" id="ModalDetails{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Details Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Kebutuhan Man Power</p>
                            <h6>{{$data->kebutuhan}} {{$data->jabatan}}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Gaji Pokok</p>
                            <h6>Rp {{ number_format($data->p_gajipokok, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran BPJS Ketenagakerjaan</p>
                            <h6>Rp {{ number_format($data->p_bjstk, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran BPJS Kesehatan</p>
                            <h6>Rp {{ number_format($data->p_bpjs_ks, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran THR</p>
                            <h6>Rp {{ number_format($data->p_thr, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Tunjangan Kerja</p>
                            <h6>Rp {{ number_format($data->p_tkerja, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Tunjangan Seragam</p>
                            <h6>Rp {{ number_format($data->p_tseragam, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Tunjangan Lain-Lain</p>
                            <h6>Rp {{ number_format($data->p_tlain, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Tunjangan Training</p>
                            <h6>Rp {{ number_format($data->p_training, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Tunjangan Operasional</p>
                            <h6>Rp {{ number_format($data->p_operasional, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Membership Plan</p>
                            <h6>Rp {{ number_format($data->p_membership, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Gaji Pokok</p>
                            <h6>Rp {{ number_format($data->tp_gapok, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran BPJS Ketenagakerjaan</p>
                            <h6>Rp {{ number_format($data->tp_bpjstk, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran BPJS Kesehatan</p>
                            <h6>Rp {{ number_format($data->tp_bpjsks, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran THR</p>
                            <h6>Rp {{ number_format($data->tp_thr, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Tunjangan Kerja</p>
                            <h6>Rp {{ number_format($data->tp_tunjangankerja, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Tunjangan Seragam</p>
                            <h6>Rp {{ number_format($data->tp_tunjanganseragam, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Tunjangan Lain-Lain</p>
                            <h6>Rp {{ number_format($data->tp_tunjanganlainnya, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Tunjangan Training</p>
                            <h6>Rp {{ number_format($data->tp_training, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Tunjangan Operasional</p>
                            <h6>Rp {{ number_format($data->tp_operasional, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran Membership Plan</p>
                            <h6>Rp {{ number_format($data->tp_membership, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran PPH</p>
                            <h6>Rp {{ number_format($data->tp_pph, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Total Penawaran PPn</p>
                            <h6>Rp {{ number_format($data->tp_ppn, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Project Deductions</p>
                            <h6>{{ $data->p_deduction }} %</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Real Deductions</p>
                            <h6>{{$data->r_deduction}} %</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Monthly Rate</p>
                            <h6>Rp {{ number_format($data->tp_bulanan, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Daily Rate</p>
                            <h6>Rp {{ number_format($data->rate_harian, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Total</p>
                            <h4>Rp {{ number_format($data->tp_total, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <p class="text-muted mb-2">Penawaran Total Membership Plan</p>
                            <h4>Rp {{ number_format($data->tp_membership, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('plugin-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/js/project.js') }}"></script>
    
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
                const deleteUrl = "{{ route('project-details.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Jabatan Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Jabatan Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Jabatan Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
@endpush