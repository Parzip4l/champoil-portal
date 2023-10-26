@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/animate-css/animate.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="absen-wrap mb-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="button-absen">
                      @php 
                        $employee = \App\Employee::where('nik', Auth::user()->name)->first();
                      @endphp
                    <h4 class="mb-3 text-center">{{$greeting}} {{ $employee->nama }} {{$greeting === 'Selamat Pagi' ? 'Selamat Beraktifitas' : ''}}</h4>
                        @foreach ($datakaryawan as $data)
                            @if (Auth::check())
                                @php
                                    $user = Auth::user();
                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    $hasScheduleForToday = \App\ModelCG\Schedule::where('employee', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->exists();
                                    $clockin = \App\Absen::where('nik', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->first();
                                @endphp
                                @if ($clockin)
                                <h5 class="text-center mb-3">Let's Get To Home !</h5>
                                @else
                                <h5 class="text-center mb-3">Let's Get To Work !</h5>
                                @endif
                            @endif
                        @endforeach
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                                $today = \Carbon\Carbon::now()->format('Y-m-d');
                                $hasScheduleForToday = \App\ModelCG\Schedule::where('employee', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->exists();
                                $clockin = \App\Absen::where('nik', $user->employee_code)
                                    ->whereDate('tanggal', $today)
                                    ->first();
                            @endphp
                            @if($hasScheduleForToday)
                                @if ($clockin)
                                <form action="{{ route('clockout') }}" method="POST" id="form-absen2">
                                @csrf
                                    <input type="hidden" name="latitude_out" id="latitude_out">
                                    <input type="hidden" name="longitude_out" id="longitude_out">
                                    <input type="hidden" name="status" value="H">
                                    <button type="submit" class="btn btn-lg btn-danger btn-icon-text mb-2 mb-md-0 w-100" id="btnout">Clock Out</button>
                                </form>
                                @else
                                <form action="{{ route('clockin') }}" method="POST" class="me-1" id="form-absen">
                                    @csrf
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                        <input type="hidden" name="status" value="H">
                                        <a href="#" class="btn btn-lg btn-primary btn-icon-text mb-2 mb-md-0 w-100" id="btn-absen" onClick="formAbsen()">
                                        Clock IN</a>
                                </form>
                                @endif
                            @else
                            <h4 class="text-center text-danger">Day Off</h4>
                        @endif
                    @endif
                        <div class="log-absen-today mt-2">
                            <div class="card ">
                                <div class="card-header text-center bg-warning">
                                    <h5>Attendance Log</h5>   
                                </div>
                                <div class="card-body">
                                    @foreach ($logs as $log)
                                    <div class="clock-in-wrap d-flex justify-content-between">
                                        <div class="con">
                                            <h5 class="text-bold mb-1">{{ $log->clock_in }}</h5>
                                            <h6 class="text-muted">{{ date('d M', strtotime($log->tanggal)) }}</h6>
                                        </div>
                                        <div class="ket align-self-center">
                                            <h5 class="mb-1 text-end text-success">CLOCK IN</h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="clock-in-wrap d-flex justify-content-between">
                                    @if (isset($log->clock_out) && !empty($log->clock_out))
                                    <div class="con">
                                            <h5 class="text-bold mb-1">{{ $log->clock_out}}</h5>
                                            <h6 class="text-muted">{{ date('d M', strtotime($log->tanggal)) }}</h6>
                                        </div>
                                        <div class="ket align-self-center">
                                            <h5 class="mb-1 text-end text-danger">CLOCK OUT</h5>
                                        </div>
                                    </div>
                                    @else
                                    <div class="w-100">
                                        <p class="text-center">Anda Belum Absen Pulang</p>  
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="menu-absen-wrap desktop">
                        <div class="owl-carousel owl-theme owl-basic">
                            <div class="item">
                                <a href="{{route('mylogs')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="git-branch"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Attendence Log</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{route('attendence-request.create')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="user-plus"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Request Attendence</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{ route('mySlip')}} ">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="file-text"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">My Payslip</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{route('attendence.backup')}}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="clock"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Backup Attendence</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{ route('list-class') }}">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="book"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">Class Room</p>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="javascript:void(0)">
                                    <div class="icon text-center">
                                        <i class="me-2 icon-lg" data-feather="book"></i>
                                    </div>
                                    <div class="menu-name text-center">
                                        <p class="text-muted">E-Workplan</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  @php 
    $dataLogin = json_decode(Auth::user()->permission);
  @endphp 
  @if(in_array('superadmin_access', $dataLogin) || in_array('accounting_access', $dataLogin) || in_array('sales_access', $dataLogin))
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
      <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
      <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
    </div>
    <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="printer"></i>
      Print
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="download-cloud"></i>
      Download Report
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">New Customers</h6>
              <div class="dropdown mb-2">
                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">3,897</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+3.3%</span>
                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0" id="Orders">Orders</h6>
              <div class="dropdown mb-2">
                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="{{route('sales.index')}}"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h4 class="mb-2">
                    @php 
                        function formatNumber($number) {
                            $suffix = '';
                            if ($number >= 1000000000000) {
                                $number = $number / 1000000000000;
                                $suffix = ' Triliyun';
                            } elseif ($number >= 1000000000) {
                                $number = $number / 1000000000;
                                $suffix = ' Miliar';
                            } elseif ($number >= 1000000) {
                                $number = $number / 1000000;
                                $suffix = ' Juta';
                            } elseif ($number >= 1000) {
                                $number = $number / 1000;
                                $suffix = ' Ribu';
                            }
                            return number_format($number, 2, '.', ',') . $suffix;
                        }
                    @endphp
                    {{ formatNumber($TotalSales) }}
                </h4>
                <div class="d-flex align-items-baseline">
                  <p class="{{$textClass2}}">
                    <span>{{ number_format($PersentaseSales, 2) }}%</span>
                    <i data-feather="{{$arrowIcon2}}" class="icon-sm mb-1"></i>
                  </p>
                </div>  
                <p class="{{$textClass2}}">{{$changeMessage2}}</p>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="SalesChart" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0" id="purchaseTitle">Purchase</h6>
              <div class="dropdown mb-2">
                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                  <a class="dropdown-item d-flex align-items-center" href="{{route('purchase.index')}}"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h4 class="mb-2">
                    {{ formatNumber($totalPembelianBulanIni) }}
                </h4>
                <div class="d-flex align-items-baseline">
                  <p class="{{$textClass}}">
                    <span>{{ number_format($percentageChange, 2) }}%</span>
                    <i data-feather="{{$arrowIcon}}" class="icon-sm mb-1"></i>
                  </p>
                </div>
                <p class="text-muted">{{$changeMessage}}</p>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="PurchaseChart" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
          <h6 class="card-title mb-0" id="RevenueYearly">Revenue</h6>
          <div class="dropdown">
            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div class="row align-items-start mb-2">
          <div class="col-md-7">
            <p class="text-muted tx-13 mb-3 mb-md-0">Revenue is the income that a business has from its normal business activities, usually from the sale of Finish goods to customers.</p>
          </div>
          <div class="col-md-5 d-flex justify-content-md-end">
            <div class="btn-group mb-3 mb-md-0" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-outline-primary">Today</button>
              <button type="button" class="btn btn-outline-primary d-none d-md-block">Week</button>
              <button type="button" class="btn btn-outline-primary">Month</button>
              <button type="button" class="btn btn-primary">Year</button>
            </div>
          </div>
        </div>
        <div id="revenueChart2"></div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Monthly sales</h6>
          <div class="dropdown mb-2">
            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <p class="text-muted">Sales are activities related to selling or the number of Finish Goods sold in a given time period.</p>
        <div id="monthlySalesChart2"></div>
      </div> 
    </div>
  </div>
  <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Target Sales</h6>
          <div class="dropdown mb-2">
            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div id="storageChart"></div>
        <div class="row mb-3">
          <div class="col-6 d-flex justify-content-end">
            <div>
              <label class="d-flex align-items-center justify-content-end tx-10 text-uppercase fw-bolder">Total Sales <span class="p-1 ms-1 rounded-circle bg-secondary"></span></label>
              <h5 class="fw-bolder mb-0 text-end">100%</h5>
            </div>
          </div>
          <div class="col-6">
            <div>
              <label class="d-flex align-items-center tx-10 text-uppercase fw-bolder"><span class="p-1 me-1 rounded-circle bg-primary"></span> Sales Now</label>
              <h5 class="fw-bolder mb-0">67%</h5>
            </div>
          </div>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">View Sales Detail</button>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->
@endif
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/carousel.js') }}"></script>
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
  <script>
    var salesData = {!! json_encode($salesData) !!};
    var salesDates = salesData.map(item => item.date);
    var salesValues = salesData.map(item => item.total_sales);
    var primary = "#6571ff";

    var options3 = {
        chart: {
            type: "line",
            height: 60,
            sparkline: {
                enabled: true
            }
        },
        series: [{
            name: '',
            data: salesValues
        }],
        xaxis: {
            type: 'datetime',
            categories: salesDates
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        markers: {
            size: 0
        },
        colors: [primary],
        tooltip: {
          enabled: true,
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
            var formattedDate = new Date(salesDates[dataPointIndex]).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
            var formattedValue = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(parseFloat(series[seriesIndex][dataPointIndex]));

            return '<div class="tooltip">' +
                '<div class="date">' + formattedDate + '</div>' +
                '<div class="value">' + formattedValue + '</div>' +
                '</div>';
        }
    }
    };
    new ApexCharts(document.querySelector("#PurchaseChart"), options3).render();
</script>
<script>
    var salesData2 = {!! json_encode($salesData2) !!};
    var salesDates2 = salesData2.map(item => item.date2);
    var salesValues2 = salesData2.map(item => item.total_sales2);
    var primary = "#6571ff";

    var options3 = {
        chart: {
            type: "bar",
            height: 60,
            sparkline: {
                enabled: true
            }
        },
        series: [{
            name: '',
            data: salesValues2
        }],
        xaxis: {
            type: 'datetime',
            categories: salesDates2
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        markers: {
            size: 0
        },
        colors: [primary],
        tooltip: {
          enabled: true,
          custom: function ({ series, seriesIndex, dataPointIndex, w }) {
              var formattedDate = new Date(salesDates[dataPointIndex]).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
              var formattedValue = new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR'
              }).format(parseFloat(series[seriesIndex][dataPointIndex]));

              return '<div class="tooltip">' +
                  '<div class="date">' + formattedDate + '</div>' +
                  '<div class="value">' + formattedValue + '</div>' +
                  '</div>';
          }
      }
    };
    new ApexCharts(document.querySelector("#SalesChart"), options3).render();
</script>

<script>
    // Get Bulan
    var currentMonth = new Date().toLocaleString('default', { month: 'long' });
    var currentYear = new Date().toLocaleString('default', { year: 'numeric' });

    // Ganti Text
    document.getElementById('purchaseTitle').innerText = 'Purchase ' + currentMonth;
    document.getElementById('Orders').innerText = 'Orders ' + currentMonth;
    document.getElementById('RevenueYearly').innerText = 'Revenue Tahun ' + currentYear; 
</script>

<!-- Mothly Sales -->
<script>
    var MonthlySales = {!! json_encode($YearlySales) !!};
    var salesMonth = MonthlySales.map(item => item.month);
    var mothlyValues = MonthlySales.map(item => item.total_sales);
    var primary = "#6571ff";
    var muted = "#7987a1";
    var bodyColor = "#b8c3d9";
    var cardBg = "#fff";
    var gridBorder = "rgba(77, 138, 240, .15)";
    var fontFamily = "'Roboto', Helvetica, sans-serif"
    
    var options = {
      chart: {
        type: 'bar',
        height: '318',
        parentHeightOffset: 0,
        foreColor: bodyColor,
        background: cardBg,
        toolbar: {
          show: true
        },
      },
      theme: {
        mode: 'light'
      },
      tooltip: {
        theme: 'light'
      },
      colors: [primary],  
      fill: {
        opacity: .9
      } , 
      grid: {
        padding: {
          bottom: -4
        },
        borderColor: gridBorder,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      series: [{
        name: 'tes',
        data: mothlyValues
      }],
      
      tooltip: {
          enabled: true,
          custom: function ({ series, seriesIndex, dataPointIndex, w }) {
              var formattedDate = new Date(salesDates[dataPointIndex]).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
              var formattedValue = new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR'
              }).format(parseFloat(series[seriesIndex][dataPointIndex]));

              return '<div class="tooltip">' +
                  '<div class="date">' + 'Total Sales' + '</div>' +
                  '<div class="value">' + formattedValue + '</div>' +
                  '</div>';
          }
      },
      xaxis: {
        type: 'category',
        categories: salesMonth.map((month, index) => {
          const date = new Date();
          date.setFullYear(MonthlySales[index].year);
          date.setMonth(month - 1); // Months are 0-based in JavaScript Dates
          return date.toLocaleString('default', { month: 'long', year: 'numeric' });
        }),
        axisBorder: {
          color: gridBorder,
        },
        axisTicks: {
          color: gridBorder,
        },
      },
      yaxis: {
        title: {
            text: 'Number of Sales',
            style: {
                size: 10,
                color: muted
            }
        },
        labels: {
            formatter: function (value) {
                if (value >= 1000000000) {
                    // Convert to billions
                    return (value / 1000000000).toFixed(2) + ' Miliar';
                } else if (value >= 1000000) {
                    // Convert to millions
                    return (value / 1000000).toFixed(2) + ' Juta';
                } else {
                    return value;
                }
            }
        }
    },
      legend: {
        show: true,
        position: "bottom",
        horizontalAlign: 'left',
        fontFamily: fontFamily,
        itemMargin: {
          horizontal: 8,
          vertical: 0
        },
      },
      stroke: {
        width: 0
      },
      dataLabels: {
          enabled: true,
          style: {
              fontSize: '16px',
              fontFamily: fontFamily,
          },
          formatter: function (val) {
              if (val >= 1000000000) {
                  // Convert to billions
                  return (val / 1000000000).toFixed(2) + ' Miliar';
              } else if (val >= 1000000) {
                  // Convert to millions
                  return (val / 1000000).toFixed(2) + ' Juta';
              } else {
                  return val;
              }
          },
          offsetY: -100
      },
      plotOptions: {
        bar: {
          columnWidth: "50%",
          borderRadius: 4,
          dataLabels: {
            position: 'top',
            orientation: 'vertical',
          }
        },
      },
    }
    
    var apexBarChart = new ApexCharts(document.querySelector("#monthlySalesChart2"), options);
    apexBarChart.render();

</script>

<!-- Revenue Charts -->
<script>
    var MonthlySales = {!! json_encode($YearlySales) !!};
    var salesMonth = MonthlySales.map(item => item.month);
    var mothlyValues = MonthlySales.map(item => item.total_sales);
    var primary = "#6571ff";
    var muted = "#7987a1";
    var bodyColor = "#b8c3d9";
    var cardBg = "#fff";
    var secondary = "#7987a1";
    var gridBorder = "rgba(77, 138, 240, .15)";
    var fontFamily = "'Roboto', Helvetica, sans-serif";
    
    // Assuming you have appropriate data for revenueChartData and revenueChartCategories
    var revenueChartData = mothlyValues; // Update this with your actual revenue data
    var revenueChartCategories = salesMonth; // Update this with your actual categories

    var lineChartOptions = {
      chart: {
        type: "line",
        height: '400',
        parentHeightOffset: 0,
        foreColor: bodyColor,
        background: cardBg,
        toolbar: {
          show: true
        },
      },
      theme: {
        mode: 'light'
      },
      tooltip: {
        theme: 'light'
      },
      colors: [primary],
      grid: {
        padding: {
          bottom: -4,
        },
        borderColor: gridBorder,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      series: [
        {
          name: "Revenue",
          data: revenueChartData
        },
      ],
      xaxis: {
        type: "date",
        categories: salesMonth.map((month, index) => {
          const date = new Date();
          date.setMonth(month - 1); // Months are 0-based in JavaScript Dates
          date.setFullYear(MonthlySales[index].year);
          return date.toLocaleString('en-US', { month: 'long', year: 'numeric', day: 'numeric' });
        }),
        labels: {
          style: {
            fontSize: '12px',
            color: '#000',
            fontFamily: fontFamily,
          },
          rotate: -45,
          offsetY: 0,
          formatter: function (value) {
            return value;
          }
        },
        axisBorder: {
          color: gridBorder,
        },
        axisTicks: {
          color: gridBorder,
        },
        crosshairs: {
          stroke: {
            color: secondary,
          },
        },
      },
      yaxis: {
        title: {
          text: 'Revenue (IDR)',
          style:{
            fontSize: '16px',
            color: secondary
          }
        },
        tickAmount: 4,
        tooltip: {
          enabled: true
        },
        crosshairs: {
          stroke: {
            color: secondary,
          },
        },
        labels: {
          formatter: function (value) {
            if (value >= 1000000000) {
                // Convert to billions
                return (value / 1000000000).toFixed(2) + ' Miliar';
            } else if (value >= 1000000) {
                // Convert to millions
                return (value / 1000000).toFixed(2) + ' Juta';
            } else {
                return value;
            }
          }
        }
      },
      markers: {
        size: 0,
      },
      stroke: {
        width: 2,
        curve: "smooth",
      },
    };
    var apexLineChart = new ApexCharts(document.querySelector("#revenueChart2"), lineChartOptions);
    apexLineChart.render();
</script>

<style>
  div.apexcharts-canvas .apexcharts-tooltip * {
    opacity: 1!important;
  }
</style>

  <!-- Absen -->
  <script>
      $(document).ready(function () {
          // Mengambil data lokasi pengguna saat tombol absen ditekan
          $('#btn-absen').on('click', function () {
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function (position) {
                      // Mengisi nilai hidden input dengan data lokasi pengguna
                      $('#latitude').val(position.coords.latitude);
                      $('#longitude').val(position.coords.longitude);

                      // Mengirim form absen
                      $('#form-absen').submit();
                  });
              } else {
                  alert('Geolocation tidak didukung oleh browser Anda');
              }
          });
      });
      </script>
      <script>
          $(document).ready(function () {
              // Mengambil data lokasi pengguna saat tombol absen ditekan
              $('#btnout').on('click', function () {
                  if (navigator.geolocation) {
                      navigator.geolocation.getCurrentPosition(function (position) {
                          // Mengisi nilai hidden input dengan data lokasi pengguna
                          $('#latitude_out').val(position.coords.latitude);
                          $('#longitude_out').val(position.coords.longitude);

                          // Mengirim form absen
                          $('#form-absen2').submit();
                      });
                  } else {
                      alert('Geolocation tidak didukung oleh browser Anda');
                  }
              });
          });
      </script>
      <script>
          function formAbsen() {
          document.getElementById("btn-absen").submit();
          }
      </script>
      <style>
        .owl-theme .owl-nav.disabled+.owl-dots{
            display : none;
        }
      </style>
@endpush