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
                <h5 class="mb-0 align-self-center">Data Schedule Project</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Periode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules  as $schedule)
                            <tr>
                                @php 
                                    $projectname = \App\ModelCG\Project::find($schedule->project)->name;
                                    $employee = \App\Employee::where('nik', $schedule->employee)->first();
                                @endphp
                                <td> <a href="{{route('schedule.employee', ['project' => $schedule->project, 'periode' => $schedule->periode, 'employee' => $schedule->employee])}}">
                                    @if($employee && $employee->nama)
                                        {{ $employee->nama }}
                                    @else
                                        Tidak ada
                                    @endif
                                    </a>
                                </td>
                                <td> {{ $projectname }} </td>
                                <td>{!! $schedule->status !!}</td>
                                <td> {{ $schedule->periode }} </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <form action="#" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE')
                                                <a class="dropdown-item d-flex align-items-center" href="{{route('schedule.employee', ['project' => $schedule->project, 'periode' => $schedule->periode, 'employee' => $schedule->employee])}}">
                                                    <i data-feather="eye" class="icon-sm me-2"></i>
                                                    <span class="">Details</span>
                                                </a>
                                                <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{$schedule->employee}},{{ $schedule->periode }},{{$schedule->project}}')">
                                                    <i data-feather="trash" class="icon-sm me-2"></i>
                                                    <span class="">Stop Schedule</span>
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
    function showDeleteDataDialog(employee, periode, project) {
    Swal.fire({
        title: 'Hapus Data',
        text: 'Anda Yakin Akan Stop Data Ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
    }).then((result) => {
        if (result.isConfirmed) {
            // Construct the delete URL with the provided parameters
            const deleteUrl = `{{ route('schedule.stop_report', ['employee' => ':employee', 'periode' => ':periode', 'project' => ':project']) }}`;
            const formattedUrl = deleteUrl
                .replace(':employee', encodeURIComponent(employee))
                .replace(':periode', encodeURIComponent(periode))
                .replace(':project', encodeURIComponent(project));
            
            fetch(formattedUrl, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then((response) => {
                if (response.ok) {
                    Swal.fire({
                        title: 'Schedule Successfully Stoped',
                        icon: 'success',
                    }).then(() => {
                        window.location.reload(); // Refresh the page after the alert closes
                    });
                } else {
                    Swal.fire({
                        title: 'Schedule Failed to Stoped',
                        text: 'An error occurred while Stoped data.',
                        icon: 'error',
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    title: 'Shift Failed to Stoped',
                    text: 'An error occurred while Stoped data.',
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