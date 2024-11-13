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
<div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 d-none d-md-block">
                <div class="card">
                    <div class="card-body d-none">
                        <h6 class="card-title mb-4">Full calendar</h6>
                        <div id='external-events' class='external-events'>
                        
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0 align-self-center">Filter Report</h5>
                    </div>
                <div class="card-body">
                    <form class="row g-3" id="form-filter">
                        <div class="col-auto">
                            <label for="staticEmail2" class="visually-hidden">Project</label>
                            <select name="project_id" id="project_id" class="form-control select2">
                                <option value="">-- Select Project -- </option>
                                @if($project)
                                    @foreach($project as $pr)
                                        @php
                                            if($project_id==$pr->id){
                                                $selected="selected";
                                            }else{
                                                $selected="";
                                            }
                                        @endphp
                                        <option value="{{ $pr->id }}" {{$selected}}>{{ $pr->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-3 btn-sm">Filter</button>
                        </div>
                        <div class="col-auto">
                            <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="handleDownload()">Download</a> -->
                            <a href="javascript:void(0)" id="printButton" class="btn btn-primary btn-sm">Print Analityc</a>
                            
                        </div>
                    </form>
                    
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3 desktop mb-4">
                                            <div class="card custom-card2">
                                                <div class="card-body">
                                                    <div class="title-card">
                                                        <h6>Jumlah Titik Patroli</h6>
                                                    </div>
                                                    <div class="count mt-2">
                                                        <h2 id="jml_point">9</h2>
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
                                                        <h2 id="jml_shift">3</h2>
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
                                                    <small>Patroli dilaksanakan 3x per-titik</small>
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
                                                <div class="card-footer d-flex" id="patrol_per_shift">
                                                <small>Patroli dilaksanakan 3x per-titik</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 desktop mb-4">
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
                            <!-- <div class="row">
                                <div class="col-md-12    desktop mb-4">
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
                                
                            </div> -->
                        
                    <div id='fullcalendar'></div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-xl" tabindex="-1" id="fullCalModal"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle1">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Point</th>
                <th>Deskripsi</th>
                <th>Photo</th>
                <th>Petugas</th>
              </tr>
            </thead>
            <tbody id="body_data">
             
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <!-- <script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
@endpush

@push('custom-scripts')
  
<script>

const loadingBackdrop = document.getElementById('loadingBackdrop');

// Function to show the loading backdrop
function showLoading() {
    loadingBackdrop.style.display = 'flex';
}

// Function to hide the loading backdrop
function hideLoading() {
    loadingBackdrop.style.display = 'none';
}

hideLoading()

var point=[];
var point2=[];

document.getElementById('form-filter').addEventListener('submit', function(e) {
    e.preventDefault();

    let project_id = $('#project_id').val();
    let currentMonth = moment().format('MM'); // Get the current month

    // Show loading indicator
    showLoading();

    // Fetch report data
    axios.get('/api/v1/report-patroli-project', {
        params: {
            project_id: project_id,
            month: currentMonth // Pass the current month as a parameter
        }
    })
    .then(response => {
        const { point = [], point_green: point2 = [], jml_point, jml_shift, jumlah_hari } = response.data;
        const total = ((jml_point * jml_shift) * 3) * jumlah_hari;

        // Initialize or update FullCalendar here
        updateCalendar(point, point2, project_id);
        analityc(project_id, response.data, total);
        $("#jml_shift").text(jml_shift);
        $("#jml_point").text(jml_point);
        $("#total_patrol_per_month").text(total);
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    })
    .finally(() => {
        // Hide loading indicator after request completes
        hideLoading();
    });
});


function updateCalendar(point, point2, project_id) {
    // Initialize FullCalendar
    var Draggable = FullCalendar.Draggable;
    var calendarEl = document.getElementById('fullcalendar');
    var containerEl = document.getElementById('external-events');

    // Initialize the calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: "prev,today,next", // Handle navigation buttons
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        editable: true,
        droppable: true, // Allows things to be dropped onto the calendar
        fixedWeekCount: true,
        initialView: 'dayGridMonth',
        timeZone: 'UTC',
        hiddenDays: [],
        navLinks: true,
        dayMaxEvents: 2,
        events: [], // Leave empty initially
        eventSources: [], // Dynamic event sources will be added later
        datesSet: function(info) {
            // This function runs when the user navigates to a different date range
            
            // Get the start date (which is the beginning of the visible month)
            let start = moment(info.start).format('YYYY-MM-DD');
            let end = moment(info.end).format('YYYY-MM-DD');
            let year = moment(info.start).format('YYYY');
            let month = moment(info.start).format('MM');

            console.log('Start of the selected month:', start);

            // Fetch new data based on the visible range (i.e., the currently visible month)
            axios.get('/api/v1/report-patroli-project', {
                params: {
                    project_id: project_id,
                    bulan: `${year}-${month}`
                }
            })
            .then(response => {
                // Update the event sources dynamically
                const calendarEvents = {
                    id: 1,
                    backgroundColor: 'rgba(1,104,250, .15)',
                    borderColor: '#0168fa',
                    events: response.data.point || []
                };

                const birthdayEvents = {
                    id: 2,
                    backgroundColor: 'rgba(16,183,89, .25)',
                    borderColor: '#10b759',
                    events: response.data.point_green || []
                };

                const holidayEvents = {
                    id: 3,
                    backgroundColor: 'rgba(241,0,117,.25)',
                    borderColor: '#f10075',
                    events: point || [] // From the report
                };

                const discoveredEvents = {
                    id: 4,
                    backgroundColor: 'rgba(0,204,204,.25)',
                    borderColor: '#00cccc',
                    events: point2 || [] // From the report
                };

                const meetupEvents = {
                    id: 5,
                    backgroundColor: 'rgba(91,71,251,.2)',
                    borderColor: '#5b47fb',
                    events: []
                };

                const otherEvents = {
                    id: 6,
                    backgroundColor: 'rgba(253,126,20,.25)',
                    borderColor: '#fd7e14',
                    events: []
                };

                // Remove the old event sources and add new ones
                calendar.removeAllEventSources();
                calendar.addEventSource(calendarEvents);
                calendar.addEventSource(birthdayEvents);
                calendar.addEventSource(holidayEvents);
                calendar.addEventSource(discoveredEvents);
                calendar.addEventSource(meetupEvents);
                calendar.addEventSource(otherEvents);
            })
            .catch(error => {
                console.error('There was an error fetching new events:', error);
            });
        },
        eventClick: function(info) {
            var eventObj = info.event;
            const date = new Date(eventObj.start);
            const year = date.getFullYear();
            let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Ensure month is 2 digits
            let day = date.getDate().toString().padStart(2, '0'); // Ensure day is 2 digits

            const formattedDate = `${year}-${month}-${day}`;

            axios.get(`/api/v1/patroli-report-detail/${eventObj.id}/${formattedDate}`)
                .then(response => {
                    const reports = response.data.report;
                    $('#tanggal_report').text(formattedDate);
                    $('#body_data').empty();
                    let reportHTML = '';
                    let nomor = 0;

                    reports.forEach(report => {
                        nomor += 1;
                        let label_status = report.kondisi === "Baik" ? "Kondisi Baik" : "Kondisi Tidak Baik";
                        reportHTML += `
                            <tr>
                                <td>${nomor}</td>
                                <td>${report.point_name}</td>
                                <td>${report.kondisi}</td>
                                <td><img src="${report.photo}" alt="Report Photo"></td>
                                <td>${report.petugas}<br/>${report.tanggal}</td>
                            </tr>`;
                    });

                    $('#body_data').append(reportHTML);
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });

            $('#modalTitle1').html(eventObj.title);
            $('#modalBody1').html(eventObj._def.extendedProps.description);
            $('#eventUrl').attr('href', eventObj.url);
            $('#fullCalModal').modal("show");
        }
    });

    calendar.render();
}



function analityc(project,data,total){
    var donutOptions = {
        series: [data.patroli_ok,total-data.patroli_ok],
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
                colors:["#0cb3ddb3","#ff0000b3"],
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
        categories: JSON.parse(data.dates)
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
}
</script>

<script>
  function handleDownload() {
    var project = $("#project_id").val();
    let id_project =0;
    if (project !== undefined && project !== null && project !== '') {
      id_project=project;
    }
    $.ajax({
      url: '/api/v1/export-report?project='+id_project,
      method: 'GET',
      success: function(data) {
        console.log(data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('There was a problem with the AJAX request:', textStatus, errorThrown);
      }
    });
  }

    

    document.getElementById('printButton').addEventListener('click', function() {
        // Get the content of the section to print
        var printContent = document.getElementById('printSection').innerHTML;

        // Store the original content of the document
        var originalContent = document.body.innerHTML;

        // Replace the body's content with the content to print
        document.body.innerHTML = printContent;

        // Trigger the print dialog
        window.print();

        // Restore the original content after printing
        document.body.innerHTML = originalContent;

        // Optionally, restore the event listeners or reload the page if necessary
        location.reload(); // Optional: reload the page to restore everything
    });

    
    

</script>
@endpush