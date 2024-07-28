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
    events: [
      {
        id: '1',
        start: curYear+'-'+curMonth+'-08T08:30:00',
        end: curYear+'-'+curMonth+'-08T13:00:00',
        title: 'Google Developers Meetup',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },{
        id: '2',
        start: curYear+'-'+curMonth+'-10T09:00:00',
        end: curYear+'-'+curMonth+'-10T17:00:00',
        title: 'Design/Code Review',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },{
        id: '3',
        start: curYear+'-'+curMonth+'-13T12:00:00',
        end: curYear+'-'+curMonth+'-13T18:00:00',
        title: 'Lifestyle Conference',
        description: 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi...'
      },{
        id: '4',
        start: curYear+'-'+curMonth+'-15T07:30:00',
        end: curYear+'-'+curMonth+'-15T15:30:00',
        title: 'Team Weekly Trip',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },{
        id: '5',
        start: curYear+'-'+curMonth+'-17T10:00:00',
        end: curYear+'-'+curMonth+'-19T15:00:00',
        title: 'DJ Festival',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },{
        id: '6',
        start: curYear+'-'+curMonth+'-08T13:00:00',
        end: curYear+'-'+curMonth+'-08T18:30:00',
        title: 'Carl Henson\'s Wedding',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      }
    ]
  };

  // Birthday Events Source
  var birthdayEvents = {
    id: 2,
    backgroundColor: 'rgba(16,183,89, .25)',
    borderColor: '#10b759',
    events: [
      {
        id: '7',
        start: curYear+'-'+curMonth+'-01T18:00:00',
        end: curYear+'-'+curMonth+'-01T23:30:00',
        title: 'Jensen Birthday',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },
      {
        id: '8',
        start: curYear+'-'+curMonth+'-21T15:00:00',
        end: curYear+'-'+curMonth+'-21T21:00:00',
        title: 'Carl\'s Birthday',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      },
      {
        id: '9',
        start: curYear+'-'+curMonth+'-23T15:00:00',
        end: curYear+'-'+curMonth+'-23T21:00:00',
        title: 'Yaretzi\'s Birthday',
        description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
      }
    ]
  };


  var holidayEvents = {
    id: 3,
    backgroundColor: 'rgba(241,0,117,.25)',
    borderColor: '#f10075',
    events: [
      {
        id: '10',
        start: curYear+'-'+curMonth+'-04',
        end: curYear+'-'+curMonth+'-06',
        title: 'Feast Day'
      },
      {
        id: '11',
        start: curYear+'-'+curMonth+'-26',
        end: curYear+'-'+curMonth+'-27',
        title: 'Memorial Day'
      },
      {
        id: '12',
        start: curYear+'-'+curMonth+'-28',
        end: curYear+'-'+curMonth+'-29',
        title: 'Veteran\'s Day'
      }
    ]
  };

  var discoveredEvents = {
    id: 4,
    backgroundColor: 'rgba(0,204,204,.25)',
    borderColor: '#00cccc',
    events: [
      {
        id: '13',
        start: curYear+'-'+curMonth+'-17T08:00:00',
        end: curYear+'-'+curMonth+'-18T11:00:00',
        title: 'Web Design Workshop Seminar'
      }
    ]
  };

  var meetupEvents = {
    id: 5,
    backgroundColor: 'rgba(91,71,251,.2)',
    borderColor: '#5b47fb',
    events: [
      {
        id: '14',
        start: curYear+'-'+curMonth+'-03',
        end: curYear+'-'+curMonth+'-05',
        title: 'UI/UX Meetup Conference'
      },
      {
        id: '15',
        start: curYear+'-'+curMonth+'-18',
        end: curYear+'-'+curMonth+'-20',
        title: 'Angular Conference Meetup'
      }
    ]
  };


  var otherEvents = {
    id: 6,
    backgroundColor: 'rgba(253,126,20,.25)',
    borderColor: '#fd7e14',
    events: [
      {
        id: '16',
        start: curYear+'-'+curMonth+'-06',
        end: curYear+'-'+curMonth+'-08',
        title: 'My Rest Day'
      },
      {
        id: '17',
        start: curYear+'-'+curMonth+'-29',
        end: curYear+'-'+curMonth+'-31',
        title: 'My Rest Day'
      }
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

    
    fetch('https://hris.truest.co.id/api/v1/patroli-report-detail/'+eventObj.id+'/'+formattedDate)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      const reports = data.report;
      $('#tanggal_report').text(formattedDate);
      $('#list').empty();
      // Loop through each report
      reports.forEach(report => {
        let reportHTML = 
            '<ul class="timeline mb-3" style="background:#ffffff !important">'+
              '<li class="event">'+
                '<h3 class="title">'+report.judul+'</h3>';
          
          if (report.patroli.length > 0) {
            reportHTML += '<ul class="timeline mt-10">';
            report.patroli.forEach(patrol => {
              reportHTML += 
                '<li class="event mb-15">'+
                  '<h3 class="title">'+patrol.task+'</h3>';
                  reportHTML += '<ul class="timeline mt-10">';
                      patrol.daily.forEach(daily => {
                        let label_status="";
                        if(daily.status==0){
                          label_status="Kondisi Baik";
                        }else{
                          label_status="Kondisi Tidak Baik";
                        }
                        reportHTML += 
                          '<li class="event mb-15">'+
                            '<h3 class="title"> Petugas : '+daily.petugas+'</h3>'+
                            '<p>Tanggal : '+daily.tanggal+'</p>'+
                            '<p>Status : '+label_status+'</p>'+
                            '<p>Keterangan : '+daily.deskripsi+'</p>';

                            reportHTML +='</li>';

                      });
                  reportHTML += '</ul>';
                  
                  reportHTML +='</li>';
            });
            reportHTML += '</ul>';
          }

          reportHTML += 
              '</li>'+
            '</ul>';
          
          $('#list').append(reportHTML);
      });
    })
    .catch(error => {
      console.error('There has been a problem with your fetch operation:', error);
    });
  },
});

calendar.render();


});

  </script>
@endpush