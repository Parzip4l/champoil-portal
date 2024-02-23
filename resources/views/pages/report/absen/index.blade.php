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
                    <select name="periode" onchange="filter_absen(this)" class="form-control mt-3 select2">
                        <option value="January-2024" <?php echo ($_GET['periode'] == 'January-2024') ? 'selected' : ''; ?>>January - 2024</option>
                        <option value="February-2024" <?php echo ($_GET['periode'] == 'February-2024') ? 'selected' : ''; ?>>February - 2024</option>
                        <option value="MARCH-2024" <?php echo ($_GET['periode'] == 'MARCH-2024') ? 'selected' : ''; ?>>March - 2024</option>
                        <option value="April-2024" <?php echo ($_GET['periode'] == 'April-2024') ? 'selected' : ''; ?>>April - 2024</option>
                        <option value="May-2024" <?php echo ($_GET['periode'] == 'May-2024') ? 'selected' : ''; ?>>May - 2024</option>
                        <option value="June-2024" <?php echo ($_GET['periode'] == 'June-2024') ? 'selected' : ''; ?>>June - 2024</option>
                        <option value="July-2024" <?php echo ($_GET['periode'] == 'July-2024') ? 'selected' : ''; ?>>July - 2024</option>
                        <option value="August-2024" <?php echo ($_GET['periode'] == 'August-2024') ? 'selected' : ''; ?>>August - 2024</option>
                        <option value="September-2024" <?php echo ($_GET['periode'] == 'September-2024') ? 'selected' : ''; ?>>September - 2024</option>
                        <option value="October-2024" <?php echo ($_GET['periode'] == 'October-2024') ? 'selected' : ''; ?>>October - 2024</option>
                        <option value="November-2024" <?php echo ($_GET['periode'] == 'November-2024') ? 'selected' : ''; ?>>November - 2024</option>
                        <option value="December-2024" <?php echo ($_GET['periode'] == 'December-2024') ? 'selected' : ''; ?>>December - 2024</option>
                    </select>
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