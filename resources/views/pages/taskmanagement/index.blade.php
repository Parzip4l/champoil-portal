@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row mb-2">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{route('setting.pa')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row desktop">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Task Management</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <a href="{{route('task-management.create')}}" class="btn btn-primary">Create Task</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @foreach(['Completed' => 'bg-success', 'In Progress' => 'bg-warning', 'TO DO' => 'bg-primary'] as $status => $badgeClass)
                    @if(isset($groupedTasks[$status]) && $groupedTasks[$status]->isNotEmpty())
                        <h4>{{ $status }}</h4>
                        <table class="table mb-4">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Progress</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Subtask</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedTasks[$status] as $data)
                                <tr>
                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#ModalDetails{{$data->id}}">{{ $data->title }}</a></td>
                                    <td>
                                        @if($data->progress > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->progress }}%;" aria-valuenow="{{ $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($data->progress) }}%
                                            </div>
                                        </div>
                                        @else 
                                            0%
                                        @endif  
                                    </td>
                                    <td>
                                        @if($data->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                                        @elseif($data->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <a href="" data-bs-toggle="modal" data-bs-target="#subtaskModalDetails{{ $data->id }}">
                                            @if($data->total_subtasks > 0)
                                                {{ $data->completed_subtasks }}/{{ $data->total_subtasks }}
                                            @else
                                                0/0
                                            @endif
                                        </a>
                                        <a href="" data-bs-toggle="modal" data-bs-target="#subtaskModal" data-task-id="{{ $data->id }}" class="btn btn-subtask">
                                            <i data-feather="plus" class="icon-sm"></i>
                                        </a>
                                    </td>
                                    <td class="d-flex">
                                        @foreach($data->assignedUsers as $user)
                                        <div class="data-user d-flex">
                                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $user->gambar) }}" alt="{{ $user->nama }}">
                                            <div class="tooltips-name">
                                                <p class="text-muted">{{ $user->nama }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('task-management.edit', $data->id) }}">
                                                    <i data-feather="edit-2" class="icon-sm me-2"></i>
                                                    <span>Edit</span>  
                                                </a>
                                                <form action="{{ route('asset.destroy', $data->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                                    @csrf @method('DELETE') 
                                                    <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                                                        <i data-feather="trash" class="icon-sm me-2"></i>
                                                        <span>Delete</span>
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Add Subtaks -->
<div class="modal fade" id="subtaskModal" tabindex="-1" role="dialog" aria-labelledby="subtaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subtaskModalLabel">Tambah Subtask</h5>
            </div>
            <div class="modal-body">
                <form id="subtaskForm" action="{{ route('subtasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="form-group mb-2">
                        <label class="form-label" for="title">Judul Subtask</label>
                        <input type="text" name="title[]" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea name="description[]" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="due_date">Due Date</label>
                        <input type="date" name="due_date[]" class="form-control">
                    </div>
                    <div id="subtasks-container"></div>
                    <button type="button" class="btn btn-secondary" onclick="addSubtask()">Tambah Subtask</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="document.getElementById('subtaskForm').submit()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Detail Subtask -->
@foreach ($taskData as $data)
<div class="modal fade modal-subtask" id="subtaskModalDetails{{ $data->id }}" tabindex="-1" aria-labelledby="subtaskModalDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subtaskModalDetailsLabel">Detail Subtask</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Due Date</th>
                            <th>Tracking</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->subtasks as $subtask)
                            <tr>
                                <td>
                                    <input type="checkbox" class="status-switch" data-id="{{ $subtask->id }}" @if ($subtask->status == 'Completed') checked @endif>
                                </td>
                                <td>{{ $subtask->title }}</td>
                                <td>
                                    @if($subtask->status == 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ $subtask->status }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning">{{ $subtask->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $subtask->description }}</td>
                                <td>{{ $subtask->due_date }}</td>
                                <td>
                                    @if($subtask->time_start == null)
                                        <form action="{{ route('subtasks.start', $subtask->id) }}" method="POST" id="form-tracking">
                                            @csrf
                                            <input type="hidden" name="latitude_start" id="latitude_start">
                                            <input type="hidden" name="longitude_start" id="longitude_start">
                                            <button type="submit" id="btn-tracking" class="btn btn-sm btn-success"><i class="icon-sm" data-feather="play-circle"></i></button>
                                        </form>
                                    @else
                                        @if($subtask->time_end == null)
                                            <form action="{{ route('subtasks.stop', $subtask->id) }}" method="POST" id="form-tracking-stop">
                                                @csrf
                                                <input type="hidden" name="latitude_stop" id="latitude_stop">
                                                <input type="hidden" name="longitude_stop" id="longitude_stop">
                                                <button type="submit" id="btn-tracking-stop" class="btn btn-sm btn-danger"><i class="icon-sm" data-feather="stop-circle"></i></button>
                                            </form>
                                        @else
                                            <span>
                                                {{ \Carbon\Carbon::parse($subtask->time_start)->diffForHumans(\Carbon\Carbon::parse($subtask->time_end), true) }}
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('subtask.destroy', $subtask->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Details -->
@foreach ($taskData as $data)
    <div class="modal fade" id="ModalDetails{{ $data->id }}" tabindex="-1" aria-labelledby="modalDetailsLabel{{ $data->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailsLabel{{ $data->id }}">Detail Task - {{ $data->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="mb-2">{{$data->title}}</h5>
                    <p class="text-muted mb-4">{{$data->description}}</p>
                    
                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted" style="width:100px;">Priority</h6>
                        @if($data->priority == 'Low')
                            <span class="badge rounded-pill bg-primary">{{ $data->priority }}</span>
                        @elseif($data->priority == 'Medium')
                            <span class="badge rounded-pill bg-warning">{{ $data->priority }}</span>
                        @elseif($data->priority == 'High')
                            <span class="badge rounded-pill bg-danger">{{ $data->priority }}</span>
                        @endif
                    </div>

                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted align-self-center" style="width:100px;">Assign To</h6>
                        @foreach($data->assignedUsers as $user)
                        <div class="data-user d-flex">
                            <img class="wd-30 ht-30 rounded-circle image-task" src="{{ asset('images/' . $user->gambar) }}" alt="{{ $user->nama }}">
                            <div class="tooltips-name">
                                <p class="text-muted">{{ $user->nama }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted align-self-center" style="width:100px;">Status</h6>
                        <span class="badge rounded-pill {{ $data->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">{{ $data->status }}</span>
                    </div>
                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted align-self-center" style="width:100px;">Due Date</h6>
                        @php
                            // Define Indonesian day names and month names
                            $daysInId = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
                            $monthsInId = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
                        @endphp
                        @if($data->due_date)
                            @php
                                $date = \Carbon\Carbon::parse($data->due_date);
                                $day = $daysInId[$date->format('D')];
                                $month = $monthsInId[$date->format('m')];
                                $formattedDate = "{$day}, {$date->format('d')} {$month} {$date->format('Y')}";
                            @endphp
                            <h6>
                                {{ $formattedDate }}
                                @if ($date->isPast())
                                    <span class="text-danger"> - Expired Due Date</span>
                                @endif
                            </h6>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </div>
                    @if($data->status == 'Completed')
                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted align-self-center" style="width:100px;">Completed At</h6>
                        <h6>{{$data->updated_at->format('d M Y')}}</h6>
                    </div>
                    @endif
                    <div class="item-details d-flex mb-3">
                        <h6 class="text-muted align-self-center" style="width:100px;">Attachments</h6>
                        <a href="{{route('task.download',$data->id)}}"><i class="me-2 icon-lg" data-feather="download-cloud"></i>Download</a>
                    </div>
                    <hr>
                    <div class="title-header-data d-flex justify-content-between">
                        <h6 class="align-self-center">Subtasks</h6>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#subtaskModal" data-task-id="{{ $data->id }}" class="btn btn-sm btn-primary mobile">Tambah Subtask</a>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Time Tracking</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->subtasks as $subtask)
                                    <tr>
                                        <td>{{ $subtask->title }}</td>
                                        <td>
                                            <span class="badge {{ $subtask->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">{{ $subtask->status }}</span>
                                        </td>
                                        <td>
                                        @if($subtask->time_start && !$subtask->time_end)
                                            Tracking Sedang Berjalan
                                        @elseif($subtask->time_start && $subtask->time_end)
                                            {{ \Carbon\Carbon::parse($subtask->time_start)->diffForHumans(\Carbon\Carbon::parse($subtask->time_end), true) }}
                                        @else
                                            Tidak di Track
                                        @endif
                                        </td>
                                        <td>{{ $subtask->description ?? 'N/A' }}</td>
                                        <td>{{ $subtask->due_date}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No subtasks available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Mobile View -->
@php 
    $user = Auth::user(); 
    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first(); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
@endphp
<div class="row mobile mb-2">
    <div class="col-md-12 grid-margin stretch-card">
       
    </div>
</div>

<!-- \Filter Data -->
<div class="row mobile">
    <div class="col-md-12">
        <div class="tombol-mobile-task-wrap d-flex justify-content-between">
            <div class="filters">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Filter
                    </button>
                    <div class="dropdown-menu p-4" aria-labelledby="filterDropdown">
                        <a class="dropdown-item filter-btn mb-2" data-status="All">All</a>
                        <a class="dropdown-item filter-btn mb-2" data-status="Low">Low</a>
                        <a class="dropdown-item filter-btn mb-2" data-status="Medium">Medium</a>
                        <a class="dropdown-item filter-btn mb-2" data-status="High">High</a>
                    </div>
                </div>
            </div>
            <div class="add-data">
                <a href="{{route('task-management.create')}}" class="btn btn-primary">Create Task</a>
            </div>
        </div>
    </div>
</div>

<div class="row mobile">
    <div class="col-md-12 grid-margin stretch-card">
        <div id="taskCarousel" class="owl-carousel owl-theme owl-basic">
            @foreach ($groupedTasks as $status => $tasks)
                @foreach ($tasks as $task)
                    @if($task->status !== 'Completed')
                        <div class="card custom-card2 task-data" data-status="{{ $task->priority }}">
                            <div class="card-body {{ strtolower($task->priority) }}">
                                <div class="header-data-wrap d-flex justify-content-between">
                                    <div class="priority-data mb-3">
                                        @if($task->priority == 'Low')
                                            <span class="badge rounded-pill bg-primary">{{ $task->priority }}</span>
                                        @elseif($task->priority == 'Medium')
                                            <span class="badge rounded-pill bg-warning">{{ $task->priority }}</span>
                                        @elseif($task->priority == 'High')
                                            <span class="badge rounded-pill bg-danger">{{ $task->priority }}</span>
                                        @endif
                                    </div>
                                    <div class="status">
                                        <a href="" data-bs-toggle="modal" data-bs-target="#ModalDetails{{$task->id}}" class="details-mobile"><i class="icon-lg" data-feather="arrow-up-right"></i></a>
                                    </div>
                                </div>
                                @php
                                    $date = \Carbon\Carbon::parse($task->due_date);
                                @endphp
                                <h3 class="two-lines">{{ $task->title }}</h3>
                                <p class="text-muted mt-2">{{ $date->format('D, d M Y') }}</p>
                                <p class="mt-2">Task Completed : <a href="" data-bs-toggle="modal" data-bs-target="#subtaskModalDetails{{ $task->id }}">
                                    @if($task->total_subtasks > 0)
                                        {{ $task->completed_subtasks }}/{{ $task->total_subtasks }}
                                    @else
                                        0/0
                                    @endif
                                    </a>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="footer-data-wrap">
                                    <div class="row">
                                        <div class="col">
                                            <div class="assigned-users d-flex">
                                                @foreach ($task->assignedUsers->take(2) as $index => $user)
                                                    <img src="{{ asset('images/' . $user->gambar) }}" class="avatar-small" alt="{{ $user->nama }}">
                                                @endforeach
                                                @if ($task->assignedUsers->count() > 2)
                                                    <h5 class="additional-users align-self-center text-muted">+{{ $task->assignedUsers->count() - 2 }}</h5>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col align-self-center">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $task->progress }}%;" aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ round($task->progress) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>
<div class="row mobile">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Recent Task</h5>
            </div>
            <div class="card-body">
                <div class="item-wrap-data">
                    @foreach ($groupedTasks as $status => $tasks)
                        @foreach ($tasks as $task)
                            @if($task->status == 'Completed')
                                <div class="item-completed mb-4">
                                    <a href="" data-bs-toggle="modal" data-bs-target="#ModalDetails{{$task->id}}">
                                        <div class="title-wrap-data-complete d-flex justify-content-between">
                                            <div class="title-data-mobile">
                                                <h5 class="mb-1">{{$task->title}}</h5>
                                                <p class="text-muted">{{$task->updated_at->format('D, d M Y')}}</p>
                                            </div>
                                            <div class="progress">
                                                <div class="priority-data mb-3">
                                                    <span class="badge rounded-pill bg-success">{{ $task->status }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- End Mobile View -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script>
$(document).ready(function(){
  $(".owl-carousel").owlCarousel({
      items: 1.5, // Number of items to display
      loop: true, // Loop through items
      margin: 10, // Margin between items
      nav: false, // Show next/prev buttons
      dots: false, // Show dots navigation
      autoplay: false, 
      responsive: {
          0: {
              items: 1.5
          },
          600: {
              items: 2
          },
          1000: {
              items: 3
          }
      }
  });

  // Filter functionality
  $('.filter-btn').click(function() {
      var status = $(this).attr('data-status');
      $('.filter-btn').removeClass('active');
      $(this).addClass('active');
      filterProjects(status);
  });

  function filterProjects(status) {
      $('.item').each(function() {
          if ($(this).attr('data-status') === status || status === 'All') {
              $(this).show();
          } else {
              $(this).hide();
          }
      });

      // Reinitialize Owl Carousel after filtering
      $(".owl-carousel").trigger('refresh.owl.carousel');
  }
});
</script>
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
                const deleteUrl = "{{ route('kategori-pa.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Data Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Data Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Data Failed to Delete',
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
    function addSubtask() {
        const container = document.getElementById('subtasks-container');
        const subtaskHTML = `
            <div class="form-group mb-2">
                <label class="form-label" for="title">Judul Subtask</label>
                <input type="text" name="title[]" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea name="description[]" class="form-control"></textarea>
            </div>
            <div class="form-group mb-2">
                <label class="form-label" for="due_date">Tanggal Jatuh Tempo</label>
                <input type="date" name="due_date[]" class="form-control">
            </div>
        `;
        const subtaskDiv = document.createElement('div');
        subtaskDiv.innerHTML = subtaskHTML;
        container.appendChild(subtaskDiv);
    }

    $('#subtaskModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var taskId = button.data('task-id') // Extract info from data-* attributes
        var modal = $(this)
        modal.find('.modal-body #task_id').val(taskId)
    })
</script>

<script>
    document.querySelectorAll('.status-switch').forEach(function(switchElem) {
    switchElem.addEventListener('change', function() {
        var id = this.getAttribute('data-id');
        var isActive = this.checked ? 'Completed' : 'To Do';

        fetch(`/subtask/update-status/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_active: isActive })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Status updated successfully',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update status',
                });
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred',
            });
        });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all modals
        document.querySelectorAll('.modal-subtask').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                location.reload(); // Refresh the page
            });
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const tasks = document.querySelectorAll(".task-data");
    const noTasksMessage = document.querySelector(".no-tasks-message");

    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            const status = button.getAttribute("data-status");
            let visibleTasks = 0;

            tasks.forEach(task => {
                if (status === "All" || task.getAttribute("data-status") === status) {
                    task.style.display = "flex";
                    task.closest('.owl-item').classList.remove('d-none');
                    visibleTasks++;
                } else {
                    task.style.display = "none";
                    task.closest('.owl-item').classList.add('d-none');
                }
            });

            if (visibleTasks === 0) {
                noTasksMessage.style.display = "flex";
            } else {
                noTasksMessage.style.display = "none";
            }
        });
    });

    // Trigger initial filter to show all tasks and hide no tasks message
    filterButtons[0].click();
});
</script>
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btn-tracking').on('click', function (e) {
            e.preventDefault(); // Prevent the default behavior of the link

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude_start').val(position.coords.latitude);
                    $('#longitude_start').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-tracking').submit();
                }, function (error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        // Pengguna menolak izin lokasi
                        alert('Anda perlu memberikan izin lokasi untuk menggunakan fitur ini');
                    }
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btn-tracking-stop').on('click', function (e) {
            e.preventDefault(); // Prevent the default behavior of the link

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude_stop').val(position.coords.latitude);
                    $('#longitude_stop').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-tracking-stop').submit();
                }, function (error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        // Pengguna menolak izin lokasi
                        alert('Anda perlu memberikan izin lokasi untuk menggunakan fitur ini');
                    }
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
@endpush