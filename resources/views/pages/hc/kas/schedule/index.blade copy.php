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
                <div class="judul align-self-center">
                    <h5 class="align-self-center">Data Schedule</h5>
                </div>
                <div class="button-actions">
                    <a href="{{route('schedule.create')}}" class="btn btn-sm btn-primary">Create</a>
                    <a href="{{route('export.schedule')}}" class="btn btn-sm btn-warning text-white">Download Template</a>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Import Schedule
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <form action="{{ route('schedule.index') }}" method="get" id="filterForm">
                            @csrf
                            <label for="periode" class="form-label">Filter Periode:</label>
                            <select name="periode" class="form-control mb-2" id="periodeSelect" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Semua Periode</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ strtoupper(date('F', mktime(0, 0, 0, $month, 10))) }}-{{ $currentYear }}" {{ request('periode') == strtoupper(date('F', mktime(0, 0, 0, $month, 10))) . '-' . $currentYear ? 'selected' : '' }}>
                                        {{ strtoupper(date('F', mktime(0, 0, 0, $month, 10))) }}-{{ $currentYear }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Periode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedulesByProject as $scheduleByProject)
                            <tr>
                                @php
                                    $project = \App\ModelCG\Project::find($scheduleByProject->project);
                                    $projectname = isset($project->name) ? $project->name : 'Project not found';
                                @endphp
                                <td>{{ $projectname }}</td>
                                <td>{{ $scheduleByProject->periode }}</td>
                                <td>
                                    <a href="{{ route('schedule.details', ['project' => $scheduleByProject->project, 'periode' => $scheduleByProject->periode]) }}" class="btn btn-primary btn-sm">Details</a>
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
<!-- Modal IMport -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('read.excel') }}" method="post" enctype="multipart/form-data">
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
                const deleteUrl = "{{ route('shift.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Shift Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Shift Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Shift Failed to Delete',
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
    document.addEventListener('DOMContentLoaded', function () {
        // Menangkap perubahan pada elemen select
        document.getElementById('organizationSelect').addEventListener('change', function () {
            // Mengirim formulir saat terjadi perubahan
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush