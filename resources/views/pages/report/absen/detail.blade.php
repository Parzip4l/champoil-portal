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
                    <h6 class="card-title align-self-center mb-0">Report Absensi Detail</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Tanggal</th>
                    <th>Schedule</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                </tr>
                </thead>
                <tbody>
                    @if($records)
                        @php 
                            $no=1;
                        @endphp
                        @foreach($records as $row)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $row->nama_karyawan }}</td>
                                <td>{{ $row->tanggal }}</td>
                                <td>{{ $row->shift }}</td>
                                <td>{{ isset($row->clock_in)?$row->clock_in:'-' }}</td>
                                <td>{{ isset($row->clock_out)?$row->clock_out:'-' }}</td>
                            </tr>
                        @php 
                            $no++   ;
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
   
    function formatDateToYMD(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }

    var urlParams = new URLSearchParams(window.location.search);
    var dateRangeParam = urlParams.get('periode');
    let startDate=new Date();
    let endDate=new Date();

    if(dateRangeParam){
        var dateRangeArray = dateRangeParam.split('to');
    
        // Now dateRangeArray contains the start and end date values
        startDate = dateRangeArray[0]; // Assuming dates are separated by 'to'
        endDate = dateRangeArray[1];
    }
    

    // You can use startDate and endDate as needed
    console.log('Start Date:', startDate);
    console.log('End Date:', dateRangeParam);
    flatpickr('#date_range', {
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: [startDate,endDate]
        
    });
    // Button click event
    document.getElementById('search').addEventListener('click', function () {
        // Add your button click logic here
        var range_date = $('#date_range').val();
        window.location.href = '?periode=' + range_date;
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