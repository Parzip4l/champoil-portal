@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    .timeline {
      max-width: 100% !important;
    }

    th {
      text-align: center !important;
      vertical-align: middle;
    }
  </style>
@endpush

@section('content')
@php 
    if($proj){
        $prjk = $proj;
    }else{
        $proj = $_GET["project_id"] ?? "";
    }
@endphp
@csrf
<div class="row">
    <div class="col-md-12">
        
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0 align-self-center">Filter Report </h5>
                    </div>
                    <div class="card-body">
                        <form class="row g-3">
                        @if($client==NULL)
                            <div class="col-auto">
                                <label for="staticEmail2" class="visually-hidden">Project</label>
                                <select name="project_id" class="form-control select2">
                                    <option value="">-- Select Project --</option>
                                    @if($project)
                                        @foreach($project as $pr)
                                            @php
                                                $selected = ($project_id == $pr->id) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $pr->id }}" {{ $selected }}>{{ $pr->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            <div class="col-auto">
                                <label for="staticEmail2" class="visually-hidden">Periode</label>
                                <select name="periode" class="form-control select2">
                                    <option value="">-- Select Periode --</option>
                                    @if(bulan())
                                        @foreach(bulan() as $key=>$value)
                                            @php
                                                $selected = ($periode == $value) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $value.'-'.date('Y') }}" {{ $selected }}>{{ $value }}</option>
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
        
        
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">{{ @$detail_project->name }}</h6>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th colspan="17" class="text-center">
                                    {{ @$detail_project->name }}
                                </th>
                            </tr>
                            <tr>
                                <th width="5" rowspan="2">No</th>
                                <th rowspan="2">Checkpoint</th>
                                <th rowspan="2">Sub Point</th>
                                @if(tanggal_bulan(date('Y'),date('m',strtotime($periode))))
                                    @foreach(tanggal_bulan(date('Y'),date('m',strtotime($periode))) as $tanggal )
                                        <th rowspan="2">{{ $tanggal }}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($report)
                                @foreach($report as $row)
                                    <tr>
                                        <td rowspan="{{ $row->jml_sub + 1 }}"></td>
                                        <td rowspan="{{ $row->jml_sub + 1 }}">{!! insert_line_breaks($row->judul,30) !!}</td>
                                    </tr>
                                    @if($row->sub_task)
                                        @foreach($row->sub_task as $sub)
                                            <tr>
                                                <td>{!! insert_line_breaks($sub->task,30) !!}</td>
                                                @if(tanggal_bulan(date('Y'),date('m',strtotime($periode))))
                                                    @foreach(tanggal_bulan(date('Y'),date('m',strtotime($periode))) as $tanggal )
                                                        <td>
                                                            @if($schedule)
                                                                @foreach($schedule as $scdl)
                                                                    <a href="javascript:void(0)" 
                                                                       onclick="get_detail('{{$sub->id}}','{{ $tanggal }}','{{ $scdl->shift }}','{{ $proj }}')"
                                                                       class="btn btn-xs btn-outline-primary mr-3">
                                                                        {{ $scdl->shift }} 
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr> 
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-xl" id="detail-patrol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
          
              <table id="patrolTable" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Task ID</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Time</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody id="patrolTableBody">
                    <!-- Table rows will be dynamically added here -->
                </tbody>
            </table>
                <!-- Modal content will be loaded dynamically via AJAX -->
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    // Function to open the detail patrol modal and load content via AJAX
    var modalBody = $('#detail-patrol .modal-body');
    var tableBody = document.getElementById("patrolTableBody");
    tableBody.innerHTML = "";
    function get_detail(id_task,tanggal, shift, project) {
        $('#detail-patrol .modal-body #patrolTableBody').empty();
        $('#detail-patrol').modal('show'); // Show the modal
        // tableBody.clear();
        $.ajax({
            url: '/api/v1/patroli-report-dash',
            type: 'POST',
            data: {
                tanggal: tanggal,
                shift: shift,
                project: project,
                id_task:id_task
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(response) {
                // Example: Append new content dynamically
                var dataPatrol = response.data_patrol;
                for (var i = 0; i < dataPatrol.length; i++) {
                    var row = `<tr>
                                    <td>${i+1}</td>
                                    <td>${dataPatrol[i].task_name}</td>
                                    <td>${dataPatrol[i].label_status}</td>
                                    <td>${dataPatrol[i].description}</td>
                                    <td>${dataPatrol[i].format_tanggal}</td>
                                    <td>${dataPatrol[i].petugas}</td>
                                </tr>`;
                    tableBody.innerHTML += row;
                }
                // Handle the response from the server
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                // Handle any errors
            }
        });
    }

    // Initialize the FullCalendar
    $(document).ready(function() {
        var calendarEl = document.getElementById('fullcalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // Configuration options for FullCalendar
            headerToolbar: {
                left: "prev,today,next",
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            events: [] // Your events data here
        });

        calendar.render();
    });
</script>
@endpush
