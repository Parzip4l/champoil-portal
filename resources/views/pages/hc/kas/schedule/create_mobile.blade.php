@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Data Schedule</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('schedule.store') }}" method="POST">
                    @csrf
                    <div class="wrap-schedule">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Project</label>
                                        <select name="project" 
                                                id="" 
                                                class="form-control"
                                                onchange="handleSelectChange(this)">
                                            @if(!empty($project))
                                            @foreach($project as $projectd)
                                                @php 
                                                    $selected="";
                                                    if(@$_GET['project_id']==$projectd->id){
                                                        $selected="selected";
                                                    }
                                                @endphp
                                                <option value="{{@$projectd->id}}" {{$selected}}>{{$projectd->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Pilih Periode</label>
                                <select class="form-control zindex-fix select2" 
                                        data-width="100%" 
                                        name="periode" 
                                        onchange="periode_filter(this)"
                                        id="periode">
                                    @foreach(bulan() as $bln)
                                        @php 
                                            $selected="";
                                            if(@$_GET['periode']==strtoupper($bln)){
                                                $selected="selected";
                                            }
                                        @endphp
                                        <option value="{{ strtoupper($bln) }}" {{$selected}}>{{ strtoupper($bln) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                        @if(!empty($filter_project))
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
                        @endif
                    </div>

                    <div id="list">

                    </div>
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Pilih Karyawan</label>
                            <select class="form-control zindex-fix select2" 
                                    data-width="100%" 
                                    name="employee[]" 
                                    id="list_employee"
                                    multiple>
                                @foreach($employee as $data)
                                    <option value="{{$data->nik}}">{{$data->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Add other input fields as needed -->

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-lg" tabindex="-1" id="createEventModal"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle1">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="data_employee">
        <input type="hidden" name="project" value="{{ @$_GET['project_id'] }}">
        <div class="modal-body">
            <select name="shift" class="form-control">
                @if($data_shift)
                    @foreach($data_shift as $shift_p)
                        <option value="{{ $shift_p['code'] }}">{{ $shift_p['title'] }}</option>
                    @endforeach
                @endif
            </select>
            @if($employee_proj)
            @php 
                $no=1;
            @endphp
                @foreach($employee_proj as $emp)
                
                <div class="form-check">
                {{ $no }}.
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{$emp->nama}}
                    </label>
                </div>
                @php 
                    $no++;
                @endphp
                @endforeach
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script>
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('shift.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Shift Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Shift Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Shift Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
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
      right: ''
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
    eventSources: [calendarEvents, birthdayEvents],
    drop: function(info) {
        // remove the element from the "Draggable Events" list
        // info.draggedEl.parentNode.removeChild(info.draggedEl);
    },
    eventClick: function(info) {
      var eventObj = info.event;
      $('#modalTitle1').html(eventObj.title);
      $('#modalBody1').html(eventObj._def.extendedProps.description);
      $('#eventUrl').attr('href',eventObj.url);
      $('#fullCalModal').modal("show");
    },
    dateClick: function(info) {
        var eventObj = info.event;
        $('#modalTitle1').html(info.dateStr);
    //   $('#modalBody1').html(eventObj._def.extendedProps.description);
    //   $('#eventUrl').attr('href',eventObj.url);
      $("#createEventModal").modal("show");
      console.log(info);
    },
  });

  calendar.render();
});

function handleSelectChange(selectElement) {
    const selectedValue = selectElement.value;
    window.location.href = '?project_id='+selectedValue;
    // You can add more logic here to handle the change event
}
function periode_filter(selectElement) {
    const selectedValue = selectElement.value;
    var project_id = "{{ @$_GET['project_id'] }}";
    window.location.href = '?project_id='+project_id+'&periode='+selectedValue;
    // You can add more logic here to handle the change event
}
</script>
@endpush