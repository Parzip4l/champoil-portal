@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Details Schedule {{$employeeName}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('schedule.store') }}" method="POST">
                    @csrf
                    <div class="wrap-schedule">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Employee Name</label>
                                    <input type="text" class="form-control" value="{{$employeeName}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Details Schedule</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Schedule Date</th>
                                                    <th>Shift</th>
                                                    <th>Project</th>
                                                    <th>Periode</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $nomor = 1;
                                                @endphp
                                                @foreach($schedules as $schedules)
                                                <tr>
                                                    @php
                                                        $projectname = \App\ModelCG\Project::find($schedules->project)->name;
                                                    @endphp
                                                    <td><input type="number" class="form-control" value="{{$nomor++}}"></td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $schedules->tanggal)->format('l') }}, {{$schedules->tanggal}}">
                                                        <input type="hidden" name="tanggal" value="{{$schedules->tanggal}}">
                                                    </td>
                                                    <td>
                                                        <select name="shift" class="form-control" id="">
                                                            @foreach ($shift as $data)
                                                            <option value="{{ $data->code }}" {{ $schedules->shift == $data->code ? 'selected' : '' }}>
                                                                {{ $data->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{$projectname}}" readonly>
                                                        <input type="hidden" name="project" value="{{$schedules->project}}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="{{$schedules->periode}}" name="periode" readonly>
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
                    <!-- Add other input fields as needed -->

                    <button type="submit" class="btn btn-primary w-100">Update Schedule</button>
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
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
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
@endpush