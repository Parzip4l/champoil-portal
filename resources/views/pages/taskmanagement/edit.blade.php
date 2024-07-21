@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Add other plugin styles if needed -->
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
            <a href="{{ route('task-management.index') }}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="head-card d-flex justify-content-between">
                    <div class="header-title align-self-center">
                        <h6 class="card-title align-self-center mb-0">Edit Task</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('task-management.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-2">
                        <label class="form-label" for="title">Judul Task</label>
                        <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" required>{{ $task->description }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label class="form-label" for="due_date">Due Date</label>
                                <input type="date" class="form-control" name="due_date" value="{{ $task->due_date }}" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label class="form-label" for="attachments">File</label>
                                <input type="file" class="form-control" name="attachments">
                                <small class="form-text text-muted">Leave empty if you don't want to change the file.</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label class="form-label" for="priority">Prioritas</label>
                                <select name="priority" class="form-control">
                                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="user">Assign User</label>
                        <div class="custom-select-wrapper">
                            <select name="user[]" class="form-control select2" multiple>
                                @foreach ($users as $dataUser)
                                    <option value="{{ $dataUser->nik }}" {{ in_array($dataUser->nik, $assignedUsers->pluck('nik')->toArray()) ? 'selected' : '' }}>
                                        {{ $dataUser->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <button class="btn btn-primary w-100" type="submit">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <!-- Add other plugin scripts if needed -->
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <!-- Add other custom scripts if needed -->
@endpush
