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
                    <h6 class="card-title align-self-center mb-2">Rekap Report</h6>
                   
                    
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
                    @if(bulan())
                        @foreach(bulan() as $bln)
                            <th>{{ $bln }}</th>
                        @endforeach
                    @endif
                    
                </tr>
                </thead>
                <tbody>
                    @if($project)
                    @php 
                        $no=1;
                    @endphp
                        @foreach($project as $row)
                            <tr>
                                <td width="5">{{ $no }}</td>
                                <td>{{ $row->name }}</td>
                                @if(bulan())
                                    @foreach(bulan() as $bln_r)
                                        <td>{{ $row['sschedule'.$bln_r] }}<br/>{{ $row['persentase_absen'.$bln_r] }}</td>
                                    @endforeach
                                @endif
                                
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
    
    flatpickr('#date_range', {
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: [startDate,endDate]
        
    });
    // Button click event
    document.getElementById('search').addEventListener('click', function () {
        // Add your button click logic here
        var periode = $('#periode').val();
        window.location.href = '?periode=' +periode ;
    });
    
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