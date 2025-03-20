@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
        <form action="{{ route('features-management.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card custom-card2">
                <div class="card-header">
                    <h4 class="card-title">Update Features</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="roles" class="form-label">Select Roles</label>
                            <select class="form-select select2" name="role_ids[]" id="roles" data-choices multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" 
                                    @if(is_array($menu->roles) && in_array($role->name, $menu->roles)) 
                                        selected 
                                    @elseif(is_string($menu->roles) && in_array($role->name, json_decode($menu->roles))) 
                                        selected 
                                    @endif
                                >
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Menu Name</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Menu Name" value="{{ old('title', $menu->title) }}" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="icon" class="form-label">Icon <span>(List Icon Click <a href="https://icon-sets.iconify.design/solar/page-100.html">Here</a>)</span></label>
                            <input type="text" name="icon" id="icon" class="form-control" placeholder="icon" value="{{ old('icon', $menu->icon) }}">
                        </div>
                        <div class="col-lg-4">
                            <label for="url" class="form-label">Url</label>
                            <input type="text" name="url" id="url" class="form-control" placeholder="Your Routes" value="{{ old('url', $menu->url) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent Menu</label>
                                <select class="form-select" name="parent_id" id="parent_id">
                                    <option value="">Select parent if this child menu</option>
                                    @foreach($menuData as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="is_active" class="form-label">Is Active</label>
                            <select class="form-select" name="is_active" id="is_active">
                                <option value="1" {{ old('is_active', $menu->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $menu->is_active) == 0 ? 'selected' : '' }}>Non Active</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="order" class="form-label">Order Menu</label>
                            <input type="number" name="order" id="order" class="form-control" placeholder="Order menu" value="{{ old('order', $menu->order) }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button class="btn btn-primary w-100" type="submit">Update Features</button>
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
@endpush