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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Report Absensi</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <span class="input-group-text" id="basic-addon2">
                            <span data-feather="search"></span>
                        </span>
                    </div>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Project Name</th>
                    <th>Total Schedule</th>
                    <th>Total Absensi</th>
                    <th>Persentase</th>
                    <th>Total Schedule Backup</th>
                    <th>Total Absensi</th>
                    <th>Persentase</th>
                </tr>
                </thead>
                <tbody>
                    @if($project)
                        @php 
                            $no=1;
                        @endphp
                        @foreach($project as $row)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->schedule }}</td>
                                <td>{{ $row->absen }}</td>
                                <td>{{ $row->persentase_absen }} %</td>
                                <td>{{ $row->schedule_backup }}</td>
                                <td>{{ $row->absen_backup }}</td>
                                <td>{{ $row->persentase_backup }} %</td>
                            </tr>
                        @php 
                            $no++;
                        @endphp

                        @endforeach
                    @endif
                    
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
    
  <script>
    flatpickr('#date_range', {
        mode: 'range',
        dateFormat: 'Y-m-d',
    });
    function filter_absen(select) {
        // Get the selected value from the dropdown
        var selectedMonth = select.value;

        // Update the URL with the selected value
        window.location.href = '?periode=' + selectedMonth;
    }
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