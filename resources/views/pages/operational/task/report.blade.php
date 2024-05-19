@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
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
                    </div>
                </div>                
            </div>
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
                        <div class="card-body">
                            <div id='fullcalendar'></div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Detail</h6>
                
            </div>
        </div>
    </div>
</div>



<div id="fullCalModal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="modalTitle1" class="modal-title"></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">close</span></button>
      </div>
      <div id="modalBody1" class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Event Page</button>
      </div>
    </div>
  </div>
</div>

<!-- End -->
@endsection

@push('plugin-scripts')

<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>

  
@endpush

@push('custom-scripts')
<script>
    // npm package: fullcalendar
// github link: https://github.com/fullcalendar/fullcalendar

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
  events: [
    
  ]
};

var holidayEvents = {
  id: 3,
  backgroundColor: 'rgba(241,0,117,.25)',
  borderColor: '#f10075',
  events: []
};
// Fetch data from the API
fetch('http://127.0.0.1:8000/api/v1/patroli-report?project_id=558661')
    .then(response => response.json())
    .then(data => {
        data.records.forEach(record => {
            var eventDate = `${curYear}-${curMonth}-${String(record.tanggal).padStart(2, '0')}`;
            holidayEvents.events.push({
                id: record.tanggal,
                start: eventDate,
                end: eventDate,
                title: record.label
            });
        });
    })
    .catch(error => console.error('Error fetching data:', error));
var discoveredEvents = {
  id: 4,
  backgroundColor: 'rgba(0,204,204,.25)',
  borderColor: '#00cccc',
  events: [
    
  ]
};

var meetupEvents = {
  id: 5,
  backgroundColor: 'rgba(91,71,251,.2)',
  borderColor: '#5b47fb',
  events: [
    
  ]
};


var otherEvents = {
  id: 6,
  backgroundColor: 'rgba(253,126,20,.25)',
  borderColor: '#fd7e14',
  events: [
   
  ]
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
  // weekNumbers: true,
  // weekNumberFormat: {
  //   week:'numeric',
  // },
  dayMaxEvents: 2,
  events: [],
  eventSources: [calendarEvents, birthdayEvents, holidayEvents, discoveredEvents, meetupEvents, otherEvents],
  drop: function(info) {
      // remove the element from the "Draggable Events" list
      // info.draggedEl.parentNode.removeChild(info.draggedEl);
  },
  eventClick: function(info) {
    var eventObj = info.event;
    console.log(info);
    $('#modalTitle1').html(eventObj.title);
    $('#modalBody1').html(eventObj._def.extendedProps.description);
    $('#eventUrl').attr('href',eventObj.url);
    $('#fullCalModal').modal("show");
  },
  dateClick: function(info) {
    $("#createEventModal").modal("show");
    console.log(info);
  },
});

calendar.render();


});
</script>
@endpush