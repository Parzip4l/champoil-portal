@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
<div class="row mb-4">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{route('task-management.index')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Task Detail</h5>
            </a>
        </div>
    </div>
</div>
<div class="row mb-6">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="title-detail-wrap mb-4">
                    <div class="row">
                        <div class="col-8">
                            <h3>{{$task->title}}</h3>
                        </div>
                        <div class="col-4 align-self-center">
                            <div class="badge rounded-pill bg-primary">Progress {{$task->progress}} %</div>
                        </div>
                    </div>
                </div>
                <h5 class="mb-2">Deskripsi</h5>
                <p class="text-muted mb-4">{{$task->description}}</p>
                <div class="meta-task-wrap">
                    <div class="row">
                        <div class="col-7">
                            <h5 class="mb-2">Assigned To</h5>
                            <div class="assigned-users mb-2">
                                @foreach ($task->assignedUsers as $index => $user)
                                    <div class="image-wrap d-flex mb-2">
                                        <img src="{{ asset('images/' . $user->gambar) }}" class="avatar-small me-2" alt="{{ $user->nama }}" style="width:45px; heigh:45px; object-fit:cover;">
                                        <h6 class="align-self-center">{{$user->nama}}</h6>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-5">
                            <h5 class="mb-2">Priority</h5>
                            @if($task->priority == 'Low')
                                <span class="badge rounded-pill mb-3 bg-primary">{{ $task->priority }}</span>
                            @elseif($task->priority == 'Medium')
                                <span class="badge rounded-pill mb-3 bg-warning">{{ $task->priority }}</span>
                            @elseif($task->priority == 'High')
                                <span class="badge rounded-pill mb-3 bg-danger">{{ $task->priority }}</span>
                            @endif
                            <h5 class="mb-2">Due Date</h5>
                            @php
                                // Define Indonesian day names and month names
                                $daysInId = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
                                $monthsInId = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
                            @endphp
                            @if($task->due_date)
                                @php
                                    $date = \Carbon\Carbon::parse($task->due_date);
                                    $day = $daysInId[$date->format('D')];
                                    $month = $monthsInId[$date->format('m')];
                                    $formattedDate = "{$day}, {$date->format('d')} {$month} {$date->format('Y')}";
                                @endphp
                                <p>
                                    {{ $formattedDate }}
                                    @if ($date->isPast())
                                        <span class="text-danger"> - Expired Due Date</span>
                                    @endif
                                </p>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{route('task.download',$task->id)}}" class="btn btn-primary btn-sm w-100 mt-2">Download Attachment</a>

                <div class="comment-wrap mb-2 mt-4 pb-4">
                    <div class="title-wrap d-flex justify-content-between">
                        <h5 class="">Activities</h5>
                        <a href="">Hide Activities</a>
                    </div>
                    <div class="card custom-card2 mt-4">
                        <div class="card-body">
                            @foreach ($task->comments as $comment)
                                @if($comment != null)
                                <div class="head-comment-wrap d-flex">
                                    <div class="ava-comment me-2">
                                        <img class="wd-45 ht-45 rounded-circle" src="{{ asset('images/' . $comment->commenter_photo) }}" alt="{{$comment->commenter_name}}">
                                    </div>
                                    <div class="user-comment me-2 align-self-center">
                                        <h6>{{$comment->commenter_name}}</h6>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="comment-data mt-2">
                                    <p class="text-muted">{{ $comment->content }}</p>
                                </div>
                                @else
                                <h6>Tidak Ada Komentar</h6>
                                @endif
                            @endforeach
                            <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="form-group mb-2">
                                    <textarea name="content" id="comment-textarea" class="form-control mention" placeholder="Add a comment" required></textarea>
                                    <div id="mention-menu" style="display: none; position: absolute; border: 1px solid #ccc; background: #fff; z-index: 1000;"></div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Add Comment</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="comment-wrap mb-2 mt-4">
                    <div class="title-wrap d-flex justify-content-between">
                        <h5 class="">Subtask</h5>
                    </div>
                    <div class="subtask-wrap mt-4">
                        @foreach($task->subtasks as $data)
                        <div class="card custom-card2 mb-2">
                            <div class="card-body">
                                <div class="title-wrap d-flex justify-content-between">
                                    <h5>{{$data->title}}</h5>
                                    @if($data->status == 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ $data->status }}</span>
                                    @elseif($data->status == 'In Progress')
                                        <span class="badge rounded-pill bg-warning">{{ $data->status }}</span>
                                    @elseif($data->status == 'Over Due')
                                        <span class="badge rounded-pill bg-danger">{{ $data->status }}</span>
                                    @elseif($data->status == 'To Do')
                                        <span class="badge rounded-pill bg-primary">{{ $data->status }}</span>
                                    @endif
                                </div>
                                
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
                                    <p>
                                        {{ $formattedDate }}
                                        @if ($date->isPast())
                                            <span class="text-danger"> - Expired Due Date</span>
                                        @endif
                                    </p>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
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
@endpush