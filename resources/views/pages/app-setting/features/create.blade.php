@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col">
        <form action="{{ route('features-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card custom-card2">
                <div class="card-header">
                    <h4 class="card-title">Add Menu</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="username" class="form-label">Menu Name</label>
                                <input type="text" name="title" id="username" class="form-control" placeholder="Menu Name" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="prodemail" class="form-label">Icon <span>(List Icon Click <a href="https://feathericons.com/">Here</a></span>)</label>
                            <input type="text" name="icon" id="icon" class="form-control" placeholder="icon">
                        </div>
                        <div class="col-lg-4">
                            <label for="prodemail" class="form-label">Routes</label>
                            <input type="text" name="url" id="url" class="form-control" placeholder="Your Routes">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="username" class="form-label">Parent Menu</label>
                                <select class="form-select" name="parent_id" id="example-select">
                                    <option value="">Select parent if this child menu</option>
                                    @foreach($menuData as $parent)
                                    <option value="{{$parent->id}}">{{$parent->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="prodemail" class="form-label">Is Active</label>
                                <select class="form-select" name="is_active" id="example-select">
                                    <option value="1">Active</option>
                                    <option value="0">Non Active</option>
                                </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="prodemail" class="form-label">Order Menu</label>
                            <input type="number" name="order" id="order" class="form-control" placeholder="Order menu" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="roles" class="form-label">Select Roles</label>
                            <select class="form-control select2" name="role_ids[]" id="roles" multiple data-width="100%">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button class="btn btn-primary w-100" type="submit">Create Menu</button>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-outline-secondary w-100" type="reset">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script>
    $(document).ready(function() {
        $('#roles').select2({
            placeholder: "Select Roles",
            allowClear: true
        });
    });
</script>
@endpush