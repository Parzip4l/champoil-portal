@extends('layout.master')
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
                                    <button type="submit" class="btn btn-primary mb-3">Filter</button>
                                </div>
                                <div class="col-auto">
                                  <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="handleDownload()">Download</a> -->
                                  <a href="{{ route('analityc') }}" class="btn btn-primary">View Analityc</a>
                                </div>
                            </form>
                            <div id='fullcalendar'></div>
                        </div>
                        </div>
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

    // Show loading indicator
    showLoading();

    axios.get('/api/v1/report-patroli-project', {
        params: {
            project_id: project_id
        }
    })
    .then(response => {
        point = response.data.point || [];
        point2 = response.data.point_green || [];
       
        
        // Initialize or update FullCalendar here
        updateCalendar(point,point2);  
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    })
    .finally(() => {
        // Hide loading indicator after request completes
        hideLoading();
    });
});

function updateCalendar(point,point2){
        // Initialize FullCalendar
        var Draggable = FullCalendar.Draggable;
        var calendarEl = document.getElementById('fullcalendar');
        var containerEl = document.getElementById('external-events');
        
        var curYear = moment().format('YYYY');
        var curMonth = moment().format('MM');

        // Calendar Event Sources
        var calendarEvents = {
            id: 1,
            backgroundColor: 'rgba(1,104,250, .15)',
            borderColor: '#0168fa',
            events: []
        };

        var birthdayEvents = {
            id: 2,
            backgroundColor: 'rgba(16,183,89, .25)',
            borderColor: '#10b759',
            events: []
        };

        var holidayEvents = {
            id: 3,
            backgroundColor: 'rgba(241,0,117,.25)',
            borderColor: '#f10075',
            events: point // Default to empty array if undefined
        };

        var discoveredEvents = {
            id: 4,
            backgroundColor: 'rgba(0,204,204,.25)',
            borderColor: '#00cccc',
            events: point2 // Default to empty array if undefined
        };

        var meetupEvents = {
            id: 5,
            backgroundColor: 'rgba(91,71,251,.2)',
            borderColor: '#5b47fb',
            events: []
        };

        var otherEvents = {
            id: 6,
            backgroundColor: 'rgba(253,126,20,.25)',
            borderColor: '#fd7e14',
            events: []
        };

        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText
                };
            }
        });

        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: "prev,today,next",
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            editable: true,
            droppable: true, // Allows things to be dropped onto the calendar
            fixedWeekCount: true,
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            hiddenDays: [],
            navLinks: 'true',
            dayMaxEvents: 2,
            events: [],
            eventSources: [calendarEvents, birthdayEvents, holidayEvents, discoveredEvents, meetupEvents, otherEvents],
            drop: function(info) {
                // Optional: Remove the element from the "Draggable Events" list
                // info.draggedEl.parentNode.removeChild(info.draggedEl);
            },
            eventClick: function(info) {
                var eventObj = info.event;
                const date = new Date(eventObj.start);
                const year = date.getFullYear();
                let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Ensure month is 2 digits
                let day = date.getDate().toString().padStart(2, '0'); // Ensure day is 2 digits

                const formattedDate = `${year}-${month}-${day}`;

                fetch('/api/v1/patroli-report-detail/' + eventObj.id + '/' + formattedDate)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const reports = data.report;
                    $('#tanggal_report').text(formattedDate);
                    $('#body_data').empty();
                    let reportHTML = '';
                    let nomor = 0;

                    reports.forEach(report => {
                        nomor += 1;
                        let label_status = report.kondisi === "Baik" ? "Kondisi Baik" : "Kondisi Tidak Baik";
                        reportHTML += '<tr>' +
                                      '<td>' + nomor + '</td>' +
                                      '<td>' + report.point_name + '</td>' +
                                      '<td>' + report.kondisi + '</td>' +
                                      '<td><img src="' + report.photo + '" alt="Report Photo"></td>' +
                                      '<td>' + report.petugas + '<br/> ' + report.tanggal + '</td>' +
                                      '</tr>';
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
</script>
@endpush