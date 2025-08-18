@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
    $user = Auth::user(); 
    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first(); 
@endphp
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">
            <i class="fas fa-mobile-alt"></i> Mobile Menu
        </h4>
        <!-- Create Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMobileMenuModal">
            <i class="fas fa-plus"></i> Create
        </button>
    </div>
    <div class="card-body">
        
        <div class="table-responsive">
            <table class="table table-bordered" id="menuTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Menu Name</th>
                        <th>Icon</th>
                        <th>Order</th>
                        <th>Maintenance</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="createMobileMenuModal" tabindex="-1" aria-labelledby="createMobileMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createMobileMenuForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createMobileMenuModalLabel">Create Mobile Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menu_name" class="form-label">Menu Name</label>
                        <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="icon" name="icon" required>
                    </div>
                    <div class="mb-3">
                        <label for="route_link" class="form-label">Route Link</label>
                        <input type="text" class="form-control" id="route_link" name="route_link" required>
                    </div>
                    <div class="mb-3">
                        <label for="urutan" class="form-label">Order</label>
                        <input type="number" class="form-control" id="urutan" name="urutan" required>
                    </div>
                    <div class="mb-3">
                        <label for="maintenance" class="form-label">Maintenance</label>
                        <select class="form-control" id="maintenance" name="maintenance" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveMenuButton" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal Form -->
<div class="modal fade" id="editMobileMenuModal" tabindex="-1" aria-labelledby="editMobileMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMobileMenuForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editMobileMenuModalLabel">Edit Mobile Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_menu_id" name="menu_id">
                    <div class="mb-3">
                        <label for="edit_menu_name" class="form-label">Menu Name</label>
                        <input type="text" class="form-control" id="edit_menu_name" name="menu_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_route_link" class="form-label">Route Link</label>
                        <input type="text" class="form-control" id="edit_route_link" name="route_link" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_urutan" class="form-label">Order</label>
                        <input type="number" class="form-control" id="edit_urutan" name="urutan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_maintenance" class="form-label">Maintenance</label>
                        <select class="form-control" id="edit_maintenance" name="maintenance" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="updateMenuButton" class="btn btn-primary">Update</button>
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
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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

    $(document).ready(function() {
        const table = $('#menuTable').DataTable();

        // Show loading indicator
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching menu data, please wait.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch data from API
        axios.get('/api/v1/mobile-menu/{{ $employeeDetails->unit_bisnis }}')
            .then(response => {
                const menus = response.data.data; // Access the 'data' array in the response
                menus.forEach((menu, index) => {
                    table.row.add([
                        index + 1,
                        menu.menu_name, // Use 'menu_name' for the menu name
                        menu.icon ? `<i data-feather="${menu.icon}"></i>` : 'N/A', // Render Feather icon or 'N/A'
                        menu.urutan, // Use 'urutan' for order
                        menu.maintenance === 1 ? 'Yes' : 'No', // Display 'Yes' for 1 and 'No' for 0
                        `<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" data-id="${menu.id}" ${menu.status === 1 ? 'checked' : ''}>
                        </div>`, // Switch-style toggle using Bootstrap's form-switch
                        menu.created_label,
                        `<button class="btn btn-sm btn-warning edit-menu" data-id="${menu.id}" data-menu='${JSON.stringify(menu)}'>
                            <i class="fas fa-edit"></i> Edit
                        </button>`
                    ]).draw(false);
                });
                feather.replace(); // Initialize Feather icons
                Swal.close(); // Close loading indicator
            })
            .catch(error => {
                console.error('Error fetching menu data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load menu data.'
                });
            });

        document.getElementById('saveMenuButton').addEventListener('click', function() {
            const form = document.getElementById('createMobileMenuForm');
            const formData = new FormData(form);

            // Show loading indicator
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while the menu is being saved.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/api/v1/mobile-menu', {
                menu_name: formData.get('menu_name'),
                route_link: formData.get('route_link'),
                urutan: formData.get('urutan'),
                icon: formData.get('icon'),
                maintenance: formData.get('maintenance'),
                _token: formData.get('_token')
            })
            .then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Menu created successfully!',
                }).then(() => {
                    location.reload(); // Reload the page to reflect changes
                });
            })
            .catch(error => {
                console.error('Error creating menu:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create menu. Please try again.',
                });
            });
        });

        $(document).on('change', '.status-toggle', function() {
            const menuId = $(this).data('id');
            const newStatus = $(this).is(':checked') ? 1 : 0;

            // Prevent toggling off for "Truest Fund"
            const menuName = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (menuName === 'Truest Fund' && newStatus === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Action Denied',
                    text: 'The "Truest Fund" menu cannot be disabled.',
                });
                $(this).prop('checked', true); // Revert the toggle to enabled
                return;
            }

            // Show loading indicator
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while the status is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/api/v1/mobile-menu/change-status', {
                menu_id: menuId,
                unit_bisnis: '{{ $employeeDetails->unit_bisnis }}',
                status: newStatus
            })
            .then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Status updated successfully!',
                });
            })
            .catch(error => {
                console.error('Error updating status:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update status. Please try again.',
                });
            });
        });

        $(document).on('click', '.edit-menu', function() {
            const menu = $(this).data('menu');
            $('#edit_menu_id').val(menu.id);
            $('#edit_menu_name').val(menu.menu_name);
            $('#edit_icon').val(menu.icon);
            $('#edit_route_link').val(menu.route_link);
            $('#edit_urutan').val(menu.urutan);
            $('#edit_maintenance').val(menu.maintenance);
            $('#editMobileMenuModal').modal('show');
        });

        document.getElementById('updateMenuButton').addEventListener('click', function() {
            const form = document.getElementById('editMobileMenuForm');
            const formData = new FormData(form);

            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while the menu is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.put(`/api/v1/mobile-menu/${formData.get('menu_id')}`, {
                menu_name: formData.get('menu_name'),
                route_link: formData.get('route_link'),
                urutan: formData.get('urutan'),
                icon: formData.get('icon'),
                maintenance: formData.get('maintenance'),
                _token: formData.get('_token')
            })
            .then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Menu updated successfully!',
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Error updating menu:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update menu. Please try again.',
                });
            });
        });
    });
  </script>
@endpush