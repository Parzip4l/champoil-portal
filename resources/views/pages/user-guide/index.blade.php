@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
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

<div class="card">
    <div class="card-body">
        <h4 class="card-title">User Guide List</h4>
        <button class="btn btn-success mb-3" data-bs-toggle="offcanvas" data-bs-target="#createUserGuideCanvas" aria-controls="createUserGuideCanvas">
            Create
        </button>
        <div class="table-responsive">
            <table id="userGuideTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Link</th>
                        <th>Platform</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Example data, replace with dynamic content --}}
                    <tr>
                        <td>1</td>
                        <td>Dashboard</td>
                        <td><a href="#">dashboard-link</a></td>
                        <td>Web</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm">View</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Settings</td>
                        <td><a href="#">settings-link</a></td>
                        <td>Mobile</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm">View</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Offcanvas for Create User Guide -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="createUserGuideCanvas" aria-labelledby="createUserGuideCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="createUserGuideCanvasLabel">Create User Guide</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="" method="POST">
            @csrf
            <div class="mb-3">
                <label for="menu" class="form-label">Menu</label>
                <input type="text" class="form-control" id="menu" name="menu" required>
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Link</label>
                <input type="url" class="form-control" id="link" name="link" required>
            </div>
            <div class="mb-3">
                <label for="platform" class="form-label">Platform</label>
                <select class="form-select" id="platform" name="platform" required>
                    <option value="Web">Web</option>
                    <option value="Mobile">Mobile</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Ensure Bootstrap JS bundle -->
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    $(document).ready(function() {
        $('#userGuideTable').DataTable();
    });
  </script>
  <style>
    a.link-settings {
        color : #555!important;
    }
  </style>
@endpush