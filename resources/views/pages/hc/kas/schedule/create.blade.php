@extends('layout.master')
<style>
.select2-container {
    z-index: 10000; /* Atur sesuai kebutuhan */
}

.select2-container--open {
    z-index: 10000; /* Atur sesuai kebutuhan */
}

/* Optional: Atur z-index dari elemen yang menghalangi */
.zindex-fix {
    position: relative; /* Pastikan elemen ini berada di atas elemen lain */
    z-index: 1000; /* Atur sesuai kebutuhan */
}
</style>
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
                                            @foreach($project as $projectd)
                                                <option value="{{$projectd->id}}">{{$projectd->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                </div>
                            </div>
                            <div class="col-md-4 d-none">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Periode</label>
                                    <input type="text" class="form-control" name="periode" value="{{ $current_month }}-{{ $current_year }}" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @if(!empty($filter_project))
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
                        @endif
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
            <div class="row">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Pilih Periode</label>
                    <select class="form-control zindex-fix select2" 
                            data-width="100%" 
                            name="periode" 
                            id="periode">
                        @foreach(bulan() as $bln)
                            <option value="{{ strtoupper($bln) }}">{{ strtoupper($bln) }}</option>
                        @endforeach
                    </select>
                </div>
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
            right: false
        },
        editable: true,
        droppable: true, // This allows things to be dropped onto the calendar
        fixedWeekCount: true,
        initialView: 'dayGridMonth',
        timeZone: 'UTC',
        hiddenDays: [],
        navLinks: true,
        dayMaxEvents: 2,
        events: [],
        eventSources: [calendarEvents],
        drop: function(info) {
            // Remove the element from the "Draggable Events" list
            // info.draggedEl.parentNode.removeChild(info.draggedEl);
        },
        eventClick: function(info) {
            var eventObj = info.event;
            console.log(info);
            $('#modalTitle1').html(eventObj.title);
            $('#modalBody1').html(eventObj._def.extendedProps.description);
            $('#eventUrl').attr('href', eventObj.url);
            $('#fullCalModal').modal("show");
        },
        dateClick: function(info) {
            $('#modalTitle1').html("Form Schedules");
            $("#createEventModal").modal("show");
        },
        // Set the initial date to the first day of the current month
        initialDate: moment().startOf('month').format('YYYY-MM-DD')
    });

    calendar.render();
});

function handleSelectChange(selectElement) {
    const selectedValue = selectElement.value;
    window.location.href = '?project_id='+selectedValue;
    // You can add more logic here to handle the change event
}
</script>
@endpush