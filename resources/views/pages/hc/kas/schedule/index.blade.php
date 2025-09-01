@extends('layout.master')
@php 
    $schComplete =  0;
    $schIncomplete =  0;
    $schLebih = 0;
    @endphp
    


@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-success text-white" style="font-size:20px;font-weight: bold;text-align:center">Complete Schedule</div>
            <div class="card-body text-center" style="font-size:50px;">{{ $schComplete }}</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-warning text-white" style="font-size:20px;font-weight: bold;text-align:center">Incomplete Schedule</div>
            <div class="card-body text-center" style="font-size:50px;">{{ $schIncomplete }}</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-danger text-white" style="font-size:20px;font-weight: bold;text-align:center">Incomplete Schedule</div>
            <div class="card-body text-center" style="font-size:50px;">{{ $schLebih }}</div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card custom-card2 shadow-sm">
            <div class="card-header bg-info text-white" style="font-size:20px;font-weight: bold;text-align:center">Average Rate</div>
            <div class="card-body text-center" style="font-size:50px;">
                @php
                    $totalSchedules = $schComplete + $schIncomplete + $schLebih;
                    $averageRate = $totalSchedules > 0 ? round(($schComplete / $totalSchedules) * 100, 2) : 0;
                @endphp
                {{ $averageRate }}%
            </div>
        </div>
    </div>
</div>
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
                                    @php
                                        $monthName = strtoupper(date('F', mktime(0, 0, 0, $month, 10)));
                                    @endphp
                                    <option value="{{ $monthName }}-{{ $currentYear }}" {{ request('periode') == $monthName . '-' . $currentYear ? 'selected' : '' }}>
                                        {{ $monthName }}-{{ $currentYear }}
                                    </option>
                                @endforeach
                                <option value="JANUARY-2025">JANUARY 2025</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="scheduleTable" class="table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Periode</th>
                                <th>Jumlah MP</th>
                                <th>Total Schedule</th>
                                <th>Jumlah Hari</th>
                                <th>Total Seharusnya</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project as $row)
                                <tr>
                                    <td>{{ strtoupper($row->name) }}</td>
                                    <td>{{ $row->periode }}</td>
                                    <td>{{ $row->total_mp }}</td>
                                    <td>{{ $row->jumlah_schedule }}</td>
                                    <td>{{ $row->jumlah_hari }}</td>
                                    <td>{{ $row->jumlah_hari * $row->total_mp }}</td>
                                    <td>
                                        @php
                                            $expectedSchedules = $row->jumlah_hari * $row->total_mp;
                                            if ($row->jumlah_schedule > $expectedSchedules) {
                                                $status = '<span class="badge bg-danger">SCHEDULE OVER</span>';
                                            } elseif ($row->jumlah_schedule < $expectedSchedules) {
                                                $status = '<span class="badge bg-warning text-dark">SCHEDULE UNDER</span>';
                                            } else {
                                                $status = '<span class="badge bg-success">SCHEDULE COMPLETE</span>';
                                            }
                                        @endphp
                                        {!! $status !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('schedule.details', ['project' => $row->id, 'periode' => $row->periode]) }}" 
                                           class="btn btn-primary btn-sm">Details</a>
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
<!-- Modal Import -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('import.schedule') }}" method="post" enctype="multipart/form-data">
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
                const deleteUrl = "{{ route('shift.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    if (response.ok) {
                        Swal.fire({
                            title: 'Shift Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Shift Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch(() => {
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
        $('#scheduleTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });

        document.getElementById('organizationSelect')?.addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush