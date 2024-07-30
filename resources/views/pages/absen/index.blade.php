@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.min.css" rel="stylesheet"/>
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Additional Information</a></li>
    <li class="breadcrumb-item active" aria-current="page">Absensi</li>
  </ol>
</nav>

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
            <div class="card-body">
                <div class="head-card d-flex justify-content-between mb-3">
                    <h6 class="card-title align-self-center mb-0">Employees Attendance</h6>
                </div>
                <div class="table-responsive">
                    <table id="dataTableExample1" class="table table-striped nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Nama</th>  
                                @foreach(\Carbon\CarbonPeriod::create($startDate, $endDate) as $date)
                                    <th>{{ $date->format('d M Y') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
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
@endpush

@push('custom-scripts')
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#dataTableExample1').DataTable({
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        fixedColumns: {
            left: 1
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("absen.index") }}',
            data: function (d) {
                // Additional parameters can be added here if needed
            }
        },
        columns: [
            { data: 'nama', name: 'nama', render: function(data, type, row) {
                return '<a href="' + row.absen_details_url + '">' + data + '</a>';
            }},
            @foreach(\Carbon\CarbonPeriod::create($startDate, $endDate) as $date)
                { 
                    data: 'attendance.absens_{{ $date->format('Ymd') }}',
                    name: 'attendance.absens_{{ $date->format('Ymd') }}',
                    defaultContent: '-',
                    render: function(data, type, row) {
                        if (data) {
                            var clockIn = data.clock_in || '-';
                            var clockOut = data.clock_out || '-';
                            return '<span class="text-success">' + clockIn + '</span> - <span class="text-danger">' + clockOut + '</span>';
                        } else {
                            return '-';
                        }
                    }
                },
            @endforeach
        ]
    });
});

</script>
@endpush
