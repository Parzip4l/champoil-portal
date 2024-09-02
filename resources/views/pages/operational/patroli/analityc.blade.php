@extends('layout.master')

<style>
  .loading-backdrop {
      display: none; 
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5); 
      z-index: 9999; 
      align-items: center;
      justify-content: center;
      display: flex;
  }

  .loading-spinner {
      text-align: center;
      padding: 20px;
      font-size: 18px;
      color: #fff; 
      border: 4px solid rgba(255, 255, 255, 0.3); 
      border-radius: 50%;
      border-top: 4px solid #fff; 
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite; 
  }

  @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }
</style>

@section('content')
<div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div>
<div class="row">
    <div class="col-md-10">
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Analytic
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Jumlah Titik Patroli</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_value">9</h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="value_test">
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Jumlah Shift</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_app_to_training">3</h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="app_training">
                                    <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Jumlah Patroli per-shift</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_patrol_per_shift">3</h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="patrol_per_shift">
                                        Patroli dilaksanakan 3x per-titik
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Total Patroli per-bulan</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_patrol_per_month">3</h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="patrol_per_month">
                                        Patroli yang harus dilaksanakan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Statistik Per-bulan</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <div id="chart" width="400" height="500"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="monthly_stats">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Persentase Patroli</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <div id="data_source" width="400" height="500"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="patrol_percentage">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
const loadingBackdrop = document.getElementById('loadingBackdrop');

function showLoading() {
    loadingBackdrop.style.display = 'flex';
}

function hideLoading() {
    loadingBackdrop.style.display = 'none';
}

hideLoading();

var donutOptions = {
    series: [10, 20],
    chart: {
        type: 'donut',
    },
    plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    total: {
                        showAlways: true,
                        show: true
                    }
                }
            }
        }
    },
    labels: ["Patroli Komplit", "Patroli Tidak Komplit"],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 280
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

var donutChart = new ApexCharts(document.querySelector("#data_source"), donutOptions);
donutChart.render();


var options = {
    chart: {
    type: 'bar',
    height: 900,  // Set the height to 900px
    stacked: true,
    stackType: '100%',  // Optional: stack bars to 100% height (remove this if you don't want 100% stacking)
  },
  series: [{
      name: 'Shift I Komplit',
      data: [20, 40, 25, 10, 12,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22]
    },
    {
      name: 'Shift I Tidak Komplit',
      data: [34, 40, 25, 10, 12,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22]
    },
    {
      name: 'Shift II Komplit',
      data: [20, 40, 25, 10, 12,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22]
    },
    {
      name: 'Shift II Tidak Komplit',
      data: [20, 40, 25, 10, 12,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22,44, 55, 41, 67, 22]
    }
  ],
  xaxis: {
    categories: <?php echo $dates ?>
  },
  plotOptions: {
    bar: {
      horizontal: true,
      dataLabels: {
        position: 'center' // centers data labels
      },
    }
  },
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return (val.toFixed(1)) + " %";
    },
    style: {
      colors: ['#fff']
    }
  },
  colors: ['#74c0fc', '#c68080', '#96f2d7', '#c68080'], // Custom colors for each series
  legend: {
    position: 'top',
    horizontalAlign: 'left'
  },
  tooltip: {
    shared: true,
    intersect: false
  }
}

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

</script>
@endpush
