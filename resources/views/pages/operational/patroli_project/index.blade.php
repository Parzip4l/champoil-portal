@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@php 
    $user = Auth::user();
    $dataLogin = json_decode($user->permission); 
    $employee = \App\Employee::where('nik', $user->name)->first(); 

    $project_id = '';
    if ($employee->jabatan == 'CLIENT') {
        $project_id = $user->project_id;
    }

    if ($employee->organisasi == 'FRONTLINE OFFICER') {
        $get_project = \App\ModelCG\Schedule::where('employee', $user->name)
            ->where('tanggal', date('Y-m-d'))
            ->first();
        $project_id = $get_project->project;
    }
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Data Patroli Project</h6>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-2">
                            @if ($employee->organisasi == 'Management Leaders' || $employee->organisasi == 'MANAGEMENT LEADERS')
                                <select id="filterProject" class="form-select select2" style="width: 250px;">
                                    <option value="">All Projects</option>
                                    @foreach(project_data('Kas') as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-icon p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px align-self-center" data-feather="align-justify"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center me-2" href="#" id="addProjectBtn">
                                    <i data-feather="plus" class="icon-sm me-2"></i> Tambah List
                                </a>
                                <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#download">
                                    <i data-feather="download" class="icon-sm me-2"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-striped table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Project ID</th>
                            <th>Judul</th>
                            <th>Unix Code</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('pages.operational.patroli_project.modals.add_edit')
@include('pages.operational.patroli_project.modals.qr_code')
@include('pages.operational.patroli_project.modals.download')

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function () {
        // Initialize Select2
        $('#filterProject').select2({
            placeholder: "Select a project",
            allowClear: true,
            theme: "bootstrap-5"
        });

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: function (data, callback) {
                const projectId = $('#filterProject').val() || '{{ $project_id }}';
                axios.get('/api/v1/patroli-projects-get', { params: { project_id: projectId } })
                    .then(response => {
                        const formattedData = response.data.map((item, index) => ({
                            id: index + 1,
                            project_id: item.project_id,
                            judul: item.judul,
                            unix_code: item.unix_code,
                            created_at: item.created_at,
                            actions: `
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${item.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}">Delete</button>
                                <button class="btn btn-info btn-sm" onClick="downloadQr('${item.unix_code}')">Download QR</button>
                            `
                        }));
                        callback({ data: formattedData });
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire('Error', 'Failed to load data', 'error');
                    });
            },
            columns: [
                { data: 'id', title: '#' },
                { data: 'project_id', title: 'Project ID' },
                { data: 'judul', title: 'Judul' },
                { data: 'unix_code', title: 'Unix Code' },
                { data: 'created_at', title: 'Created At' },
                { data: 'actions', title: 'Actions', orderable: false, searchable: false }
            ]
        });

        $('#filterProject').change(function () {
            table.ajax.reload();
        });

        // Add Project
        $('#addProjectBtn').click(() => {
            $('#projectForm')[0].reset();
            $('#projectId').val('');
            $('#projectModalLabel').text('Add Project');
            $('#projectModal').modal('show');
        });

        // Save Project
        $('#saveProjectBtn').click(() => {
            const id = $('#projectId').val();
            const data = {
                project_id: $('#project_id').val(),
                judul: $('#judul').val()
            };

            const request = id
                ? axios.put(`/api/v1/patroli-projects-insert/${id}`, data)
                : axios.post('/api/v1/patroli-projects-insert', data);

            request
                .then(() => {
                    $('#projectModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Success', `Project ${id ? 'updated' : 'added'} successfully`, 'success');
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', `Failed to ${id ? 'update' : 'add'} project`, 'error');
                });
        });

        // Edit Project
        $('#dataTable').on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            axios.get(`/api/v1/patroli-projects/${id}`)
                .then(response => {
                    const { project_id, judul } = response.data;
                    $('#project_id').val(project_id);
                    $('#judul').val(judul);
                    $('#projectId').val(id);
                    $('#projectModalLabel').text('Edit Project');
                    $('#projectModal').modal('show');
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Failed to fetch project data', 'error');
                });
        });

        // Delete Project
        $('#dataTable').on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/v1/patroli-projects/${id}`)
                        .then(() => {
                            table.ajax.reload();
                            Swal.fire('Deleted!', 'Project has been deleted.', 'success');
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error', 'Failed to delete project', 'error');
                        });
                }
            });
        });
    });

    function downloadQr(unixCode) {
        axios.get(`/api/v1/patroli-projects/${unixCode}/download`, { responseType: 'blob' })
            .then(response => {
                const fileURL = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = fileURL;
                const contentDisposition = response.headers['content-disposition'];
                const filename = contentDisposition 
                    ? contentDisposition.split('filename=')[1].replace(/"/g, '') 
                    : `qr_code_${unixCode}.pdf`;
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })
            .catch(error => {
                console.error('Error downloading the QR code:', error);
                alert('Failed to download QR code.');
            });
    }
</script>
@endpush
