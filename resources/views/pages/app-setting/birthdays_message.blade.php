@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/fullcalendar/index.global.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 align-self-center">
                    Birthdays Messages
                </h5>
            </div>
            <div class="card-body">
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal" tabindex="-1" id="fullCalModal"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle1">
            Settings Message Birthday <span id="tanggal"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <div class="modal-body">
          <form action="{{ route('save-messages') }}" method="POST">
            @csrf
            <div class="col">
                <label for="name" class="form-label">Date</label>
                <input class="form-control" name="tanggal_tahun" type="text" id="tanggal_tahun" readonly="readonly">
            </div>
            <div class="col">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control"  name="message"  id="message"  style="height:150px"></textarea>
            </div>
          
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Submit</button>
        </div>
        </form>
    </div>
  </div>
</div>

<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var calendarEl = document.getElementById('fullcalendar');

      // Birthday Events Source
      var curYear = moment().format('YYYY');
      var curMonth = moment().format('MM');
      
      var birthdayEvents = {
        id: 2,
        backgroundColor: 'rgba(16,183,89, .25)',
        borderColor: '#10b759',
        events: [
          {
            id: '7',
            start: `${curYear}-${curMonth}-01T18:00:00`,
            end: `${curYear}-${curMonth}-01T23:30:00`,
            title: 'Jensen Birthday',
            description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
          },
          {
            id: '8',
            start: `${curYear}-${curMonth}-21T15:00:00`,
            end: `${curYear}-${curMonth}-21T21:00:00`,
            title: 'Carl\'s Birthday',
            description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
          },
          {
            id: '9',
            start: `${curYear}-${curMonth}-23T15:00:00`,
            end: `${curYear}-${curMonth}-23T21:00:00`,
            title: 'Yaretzi\'s Birthday',
            description: 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis az pede mollis...'
          }
        ]
      };

      // Initialize FullCalendar
      var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: "prev,today,next",
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        editable: true,
        droppable: true,
        fixedWeekCount: true,
        initialView: 'dayGridMonth',
        timeZone: 'UTC',
        dayMaxEvents: 2,
        events: [birthdayEvents], // Ensure you add more event sources if they exist
        eventClick: function(info) {
          var eventObj = info.event;
          $('#modalTitle1').html(eventObj.title);
          $('#modalBody1').html(eventObj.extendedProps.description);
          $('#fullCalModal').modal("show");
        },
        dateClick: function(info) {
          console.log(info.dateStr);
          $("#tanggal_tahun").attr('value',info.dateStr);
          $('#fullCalModal').modal("show");
        }
      });

      calendar.render();
    });
  </script>
@endpush
