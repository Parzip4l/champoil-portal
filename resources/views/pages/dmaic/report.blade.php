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
                    <h6 class="card-title align-self-center mb-0">Report DMAIC</h6>
                    <div class="input-group mb-3">
                        <input type="text" id="date_range" class="form-control" placeholder="Select Date" aria-label="Select Date" aria-describedby="basic-addon2">
                            <button class="input-group-text btn-primary" id="search">
                                <span  data-feather="search" ></span>
                            </button>
                    </div>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Project</th>
                    <th>Tanggal</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @if($report)
                        @php 
                            $no=1;
                            
                        @endphp
                        @foreach($report as $row)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $row->nama_karyawan }}</td>
                                <td>{{ project_byID($row->project)->name }}</td>
                                <td>{{ date('d F Y H:i:s',strtotime($row->created_at)) }}</td>
                                <td>{{ $row->category_name }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade bd-example-modal-xl" tabindex="-1" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                
                                        <div class="modal-header">
                                        <h5 class="modal-title h4" id="myExtraLargeModalLabel">DMAIC REPORT</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>PROJECT NAME : {{ project_byID($row->project)->name }}</p>
                                            <p>NAME : {{ $row->nama_karyawan }}</p>
                                            <p>DATE : {{ date('d F Y H:i:s',strtotime($row->created_at)) }}</p><hr/>
                                            @if($row->detail)
                                                @foreach($row->detail as $child)
                                                    <strong>{{$child->point_name}}</strong>
                                                    {!! $child->dmaic_value !!}
                                                @endforeach
                                            @endif
                                            <p>KEYWORD – KATA KUNCI MASALAH :</p>
                                            {{$row->category_name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
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