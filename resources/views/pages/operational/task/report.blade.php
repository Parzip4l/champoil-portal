@extends('layout.master')

@section('content')
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
                            <form class="row g-3">
                                <div class="col-auto">
                                    <label for="staticEmail2" class="visually-hidden">Project</label>
                                    <select name="project_id" class="form-control select2">
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
  <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
@endpush

@push('custom-scripts')
  
  <script>
    $(function() {

// sample calendar events data

var Draggable = FullCalendar.Draggable;
var calendarEl = document.getElementById('fullcalendar');
var containerEl = document.getElementById('external-events');

var curYear = moment().format('YYYY');
var curMonth = moment().format('MM');

  // Calendar Event Source
  var calendarEvents = {
    id: 1,
    backgroundColor: 'rgba(1,104,250, .15)',
    borderColor: '#0168fa',
    events: []
  };

  // Birthday Events Source
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
    events: <?php echo $point ?>
  };

  var discoveredEvents = {
    id: 4,
    backgroundColor: 'rgba(0,204,204,.25)',
    borderColor: '#00cccc',
    events: <?php echo $point_green ?>
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
    events:[]
  };

new Draggable(containerEl, {
  itemSelector: '.fc-event',
  eventData: function(eventEl) {
    return {
      title: eventEl.innerText
    };
  }
});


// initialize the calendar
var calendar = new FullCalendar.Calendar(calendarEl, {
  headerToolbar: {
    left: "prev,today,next",
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
  },
  editable: true,
  droppable: true, // this allows things to be dropped onto the calendar
  fixedWeekCount: true,
  // height: 300,
  initialView: 'dayGridMonth',
  timeZone: 'UTC',
  hiddenDays:[],
  navLinks: 'true',

  dayMaxEvents: 2,
  events: [],
  eventSources: [calendarEvents, birthdayEvents, holidayEvents, discoveredEvents, meetupEvents, otherEvents],
  drop: function(info) {
      // remove the element from the "Draggable Events" list
      // info.draggedEl.parentNode.removeChild(info.draggedEl);
  },
  eventClick: function(info) {
    var eventObj = info.event;
    const date = new Date(eventObj.start);
    const year = date.getFullYear();
    let month = (date.getMonth() + 1).toString(); // Months are zero-indexed, so add 1
    let day = date.getDate().toString();

    // Pad month and day with leading zeros if necessary
    if (month.length < 2) {
      month = '0' + month;
    }
    if (day.length < 2) {
      day = '0' + day;
    }

    // Combine into the desired format
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
      let label_status = report.kondisi == "Baik" ? "Kondisi Baik" : "Kondisi Tidak Baik";
      reportHTML += '<tr>' +
                    '<td>' + nomor + '</td>' +
                    '<td>' + report.point_name + '</td>' +
                    '<td>' + report.kondisi + '</td>' +
                    '<td><img src=' + report.photo + '></td>' +
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
  },
});

calendar.render();


});

  </script>
@endpush