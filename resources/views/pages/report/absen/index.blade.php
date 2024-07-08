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
                    <form>
                        <div class="row">
                            @csrf
                            <div class="col-md-10">
                                <select name="periode" class="form-control mb-2 select2" id="periode" required>
                                    <option value="">Periode</option>
                                    @if(bulan())
                                        @foreach(bulan() as $key=>$value)
                                            <option value="{{ strtoupper($value).'-'.date('Y') }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                            <button type="button" class="btn btn-primary" id='search'>Filter</button>
                            </div>
                        </div>  
                    </form>
                    
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
                            $total_schedule=0;
                            $total_absen=0;
                            $schedule_backup=0;
                            $absen_backup=0;
                            $percent_absen=0;
                            $percent_backup=0;
                        @endphp
                        @foreach($project as $row)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>
                                    <a href="{{ route('report-detail',['id'=>$row->id,'periode'=>isset($_GET['periode'])?$_GET['periode']:date('Y-m-d')]) }}">
                                        {{ $row->name }}
                                    </a>
                                </td>
                                <td>{{ $row->schedule }}</td>
                                <td>{{ $row->absen }}</td>
                                <td>{{ $row->persentase_absen }} %</td>
                                <td>{{ $row->schedule_backup }}</td>
                                <td>{{ $row->absen_backup }}</td>
                                <td>{{ $row->persentase_backup }} %</td>
                            </tr>
                            @php
                                $total_schedule +=$row->schedule;
                                $total_absen +=$row->absen;
                                $schedule_backup +=$row->schedule_backup;
                                $absen_backup +=$row->absen_backup;
                                
                            $no++;
                        @endphp
                        

                        @endforeach
                        @php 
                            if($total_absen > 0 && $total_schedule> 0){
                                $percent_absen = round(($total_absen / $total_schedule) * 100,2);
                            }
                            if($absen_backup > 0 && $schedule_backup> 0){
                                $percent_backup = round(($absen_backup / $schedule_backup) * 100,2);
                            }
                        @endphp
                        <tr>
                            <td colspan=2>Total</td>
                            <td>{{ $total_schedule }}</td>
                            <td>{{ $total_absen }}</td>
                            <td>{{ $percent_absen }} %</td>
                            <td>{{ $schedule_backup }}</td>
                            <td>{{ $absen_backup }}</td>
                            <td>{{ $percent_backup }} %</td>
                        </tr>

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