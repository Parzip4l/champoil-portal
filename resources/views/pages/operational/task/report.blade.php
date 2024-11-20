@extends('layout.master')
    @php 
        $user = Auth::user();
        $dataLogin = json_decode(Auth::user()->permission); 
        $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
    @endphp
<style>
  /* styles.css */
.loading-backdrop {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 9999; /* High z-index to ensure it covers other elements */
    align-items: center;
    justify-content: center;
    display: flex;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    color: #fff; /* White text color */
    border: 4px solid rgba(255, 255, 255, 0.3); /* Light border */
    border-radius: 50%;
    border-top: 4px solid #fff; /* White top border for spinner effect */
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite; /* Spin animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@section('content')
<!-- <div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div> -->
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span style="font-size:30px" id="total_titik">0</span>
            </div>
            <div class="card-footer">
                <h6>Jumlah Titik Patroli</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span style="font-size:30px"  id="total_point">0</span>
            </div>
            <div class="card-footer">
                <h6>Jumlah Point</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span style="font-size:30px" id="jumlah_shift">0</span>
            </div>
            <div class="card-footer">
                <h6>Jumlah SHIFT</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span style="font-size:30px" id="patroli_pershift">0</span><br/>
                <span style="font-size:15px" id="total_patroli">0</span>
                
            </div>
            <div class="card-footer">
                <h6>Jumlah Patroli per-SHIFT</h6>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div id="chart"></div>
            </div>
            
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div id="pie"></div>
            </div>
        </div>
    </div>
    
</div>
<!-- 
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Insiden
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Insiden Close
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    
    
</div> -->

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <!-- <script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
@endpush


@push('custom-scripts')
<script>
    grafik_data=function(response_data){
        var options = {
            chart: {
                height: 350,
                stacked: false, // Set stacked to false so the bars don't stack on each other
            },
            series: [
                {
                name: 'Activity',
                type: 'line', // Line chart for SHIFT 1
                data: response_data.value_shift1 // Data points for SHIFT 1
                },
                {
                name: 'Target Activity',
                type: 'bar', // Bar chart for Target Activity
                data:  response_data.grafik_value  // Data points for Target Activity
                }
            ],
            stroke: {
                curve: 'smooth', // Smooth lines for SHIFT series
                width: 2
            },
            xaxis: {
                categories: response_data.grafik_key, // Matching the number of categories with the data points
            },
            title: {
                text: 'Patroli Activity',
                align: 'center'
            },
            markers: {
                size: 4
            },
            colors: ['#FEB019', '#00E396', '#008FFB'], // Custom colors for the lines and bars
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
    }
    
</script>
<script>
    var options = {
      chart: {
        type: 'pie', // Pie chart type
        height: 350
      },
      series: [44, 13], // Data for the pie chart
      labels: ['Patroli', 'No Patrol'], // Labels for the pie chart
      colors: ['#008FFB','#FF4560'], // Custom colors for the pie slices
      title: {
        text: 'Chart Statistic',
        align: 'center'
      },
      legend: {
        position: 'bottom'
      }
    };

    var chart = new ApexCharts(document.querySelector("#pie"), options);
    chart.render();
</script>
<script>
    const postData = {
        key1: 'value1',
        key2: 'value2',
    };

    // Make the POST request using Axios
    axios.post('/api/v1/dashboard-patroli', postData)
        .then(response => {
            var response_data = response.data.record;
            $("#total_titik").text(response_data.total_titik);
            $("#total_point").text(response_data.total_point);
            $("#jumlah_shift").text(response_data.jumlah_shift);
            $("#patroli_pershift").text(response_data.patroli_pershift);
            $("#total_patroli").text(response_data.total_patroli);
            grafik_data(response_data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
</script>
@endpush