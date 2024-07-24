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
                    <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalExport">Export Absen</a>
                </div>
                @if($client_id==NULL)
                <form action="{{ route('attendance.filter') }}" method="get" id="filterForm">
                    <div class="row">
                        @csrf
                        <label for="organization" class="form-label">Filter :</label>
                        <div class="col-md-3">
                            <select name="organization" class="form-control mb-2" id="organizationSelect">
                                <option value="">Semua Organisasi</option>
                                <option value="Management Leaders" {{ request('organization') == 'Management Leaders' ? 'selected' : '' }}>Management Leaders</option>
                                <option value="Frontline Officer" {{ request('organization') == 'Frontline Officer' ? 'selected' : '' }}>Frontline Officer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="project" class="form-control mb-2 select2" id="project">
                                <option value="">Project</option>
                                @if($project)
                                    @foreach($project as $row)
                                        <option value="{{ $row->id }}" {{ request('project') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="periode" class="form-control mb-2 select2" id="periode">
                                <option value="">Periode</option>
                                @php
                                    // Current date
                                    $today = \Carbon\Carbon::now();

                                    // Calculate start and end dates for the period
                                    $startDate = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
                                    $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                                    // Get previous period's dates
                                    $previousStartDate = $startDate->copy()->subMonth();
                                    $previousEndDate = $endDate->copy()->subMonth();

                                    // Create periods for dropdown
                                    $periods = [
                                        $previousStartDate->format('d M Y') . ' - ' . $previousEndDate->format('d M Y'),
                                        $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                                    ];
                                @endphp
                                @if(!empty($periods))
                                    @foreach($periods as $period)
                                        <option value="{{ $period }}">{{ $period }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        
                    
                    </div>
                </form>
                <hr>
                @endif
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
                        @php
                            $previousName = null;
                        @endphp
                        @foreach($data1 as $k)
                            @if($previousName != $k->name)
                                @php
                                    $previousName = $k->name;
                                    $employee = \App\Employee::where('nik', $previousName)->first();
                                @endphp
                                <tr>
                                    <td>
                                        @if($employee)
                                            <a href="{{route('absen.details', ['nik' => $previousName])}}">{{ $employee->nama }}</a>
                                        @else
                                        <p>Employee Not Found</p>
                                        @endif
                                    </td>
                                    @foreach(\Carbon\CarbonPeriod::create($startDate, $endDate) as $date)
                                        <td>
                                            @php
                                                $absensi = $data1->where('name', $k->name)->where('tanggal', $date->format('Y-m-d'))->first();
                                            @endphp
                                            @if($absensi)
                                                <span class="text-success">{{ $absensi->clock_in }}</span> - <span class="text-danger">{{ $absensi->clock_out }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Export Absen -->
<div class="modal fade bd-example-modal-sm" id="ModalExport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Export Attendence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('export.attendence') }}" method="GET">
                    <div class="form-group mb-3">
                        <label for="selected_month" class="form-label">Pilih Bulan :</label>
                        <select class="form-control" id="selected_month" name="selected_month">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach ($months as $key => $month)
                                <option value="{{ $key }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Export Absen</button>
                </form>
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
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
  <script>
    $(document).ready(function() {
    var table = $('#dataTableExample1').DataTable( {
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   true
    } );
} );
  </script>
@endpush