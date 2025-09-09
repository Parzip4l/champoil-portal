@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="head-card d-flex justify-content-between">
                    <div class="header-title align-self-center">
                        <h6 class="card-title align-self-center mb-0">Leader Report</h6>
                    </div>
                    <div class="tombol-pembantu d-flex">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Options
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item d-flex align-items-center" href="#" id="addProjectBtn">
                                    <i data-feather="plus" class="icon-sm me-2"></i> Add List
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#download">
                                    <i data-feather="download" class="icon-sm me-2"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (strcasecmp($employee->organisasi, 'MANAGEMENT LEADER') === 0 || strcasecmp($employee->organisasi, 'Management Leaders') === 0)
                <div class="alert alert-warning" role="alert">
                    Please filter the project to view the data.
                </div>
                <div class="mb-4">
                    <label for="projectFilter" class="form-label">Filter by Project</label>
                    <select id="projectFilter" class="form-control select2" onchange="filterProject()">
                        <option value="">Select a Project</option>
                        @foreach (project_data('Kas') as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <table id="dataTable" class="table table-hover table-bordered">
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

<!-- Modal for Add/Edit -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="projectModalLabel">Add/Edit POS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project ID</label>
                        <input type="number" class="form-control" id="project_id" name="project_id" value='{{$project_id}}' required>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <input type="hidden" id="projectId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProjectBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrContainer" class="mb-3"></div>
                <p id="qrProjectTitle" class="mt-3"></p>
                <button id="downloadQrBtn" class="btn btn-success mt-3">Download QR Code</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Download -->
<div class="modal fade" id="download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Filter Download</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="download_file">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="tanggal_report" class="form-label">Filter Tanggal</label>
                            <input type="text" class="form-control" name="tanggal" required id="tanggal_report">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jam" class="form-label">Filter Jam</label>
                            <input type="time" class="form-control" name="jam" required id="jam">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jam2" class="form-label">Filter Jam Akhir</label>
                            <input type="time" class="form-control" name="jam2" required id="jam2">
                        </div>
                        <div id="project_list"></div>
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary w-100" type="button" id="download_file_patrol">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script>
    $(document).ready(function () {
        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: function (data, callback) {
                let project = "{{ $project_id }}";
                @if (strcasecmp($employee->organisasi, 'MANAGEMENT LEADER') === 0 || strcasecmp($employee->organisasi, 'Management Leaders') === 0)
                project = $('#projectFilter').val() || ''; // Use filter for MANAGEMENT LEADER
                @endif

                axios.get(`/api/v1/leader-projects-get/${project}`)
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
                        callback({ data: [] }); // Return empty data on error
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

        window.filterProject = function () {
            table.ajax.reload(); // Reload table data when filter changes
        };

        // Add Project
        $('#addProjectBtn').click(() => {
            $('#projectForm')[0].reset();
            $('#projectId').val('');
            $('#projectModalLabel').text('Add Leader Report');

            // Set project_id dynamically for MANAGEMENT LEADER
            @if (strcasecmp($employee->organisasi, 'MANAGEMENT LEADER') === 0 || strcasecmp($employee->organisasi, 'Management Leaders') === 0)
            const selectedProject = $('#projectFilter').val();
            if (selectedProject) {
                $('#project_id').val(selectedProject).prop('readonly', true);
            }
            @endif

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
                ? axios.put(`/api/v1/leader-projects-insert/${id}`, data)  // Changed to 'lapsit-projects-insert'
                : axios.post('/api/v1/leader-projects-insert', data);  // Changed to 'lapsit-projects-insert'

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
            axios.get(`/api/v1/leader-projects/${id}`)  // Changed to 'lapsit-projects/{id}'
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
                    axios.delete(`/api/v1/leader-projects/${id}`)  // Changed to 'lapsit-projects/{id}'
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

        // Show QR Button
        $('#dataTable').on('click', '.qr-btn', function () {
        const unixCode = $(this).data('unix');
        const qrValue = unixCode;

        const qr = new QRious({
            element: document.getElementById('qrContainer'),
            value: qrValue,
            size: 200
        });

        $('#qrProjectTitle').text(`QR Code for Unix Code: ${unixCode}`);
        $('#qrModal').modal('show');

        
    });

        @if ($employee->organisasi == 'MANAGEMENT LEADER')
        $('#projectFilter').on('change', function () {
            const selectedProject = $(this).val();
            const table = $('#dataTable').DataTable();
            table.ajax.url(`/api/v1/leader-projects-get/${selectedProject}`).load();
        });
        @endif
    });

    function downloadQr(unixCode) {
        axios.get(`/api/v1/leader-projects/${unixCode}/download`, { responseType: 'blob' })  // Changed to 'lapsit-projects/{unixCode}/download'
            .then(response => {
                // Create a URL for the file
                const fileURL = window.URL.createObjectURL(new Blob([response.data]));

                // Create an anchor element to download the file
                const link = document.createElement('a');
                link.href = fileURL;

                // Get the filename from the response headers (you can customize this if needed)
                const contentDisposition = response.headers['content-disposition'];
                const filename = contentDisposition 
                    ? contentDisposition.split('filename=')[1].replace(/"/g, '') 
                    : `qr_code_${unixCode}.pdf`;  // Default filename

                link.setAttribute('download', filename); // Set the download attribute with the filename
                document.body.appendChild(link);
                link.click(); // Trigger the download
                document.body.removeChild(link);  // Remove the link from the DOM
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', 'Failed to download QR code', 'error');
            });
    }

    $(document).ready(function() {
        $('#loadingBackdrop').hide();
        $('#download_file_patrol').on('click', function() {
            // Define any parameters you want to send
            $('#loadingBackdrop').show();
            var project = 582307;
            let project_id='';
            if(project ===''){
                project_id  = $("#project_id_filter").val();
            }else{
                project_id  = project;
            }

            var jenis_file = $('input[name="filter_type"]:checked').val() || "pdf";
            var shift = $('input[name="shift"]:checked').val();
            
            const params = {
                tanggal: $("#tanggal_report").val(), // Example parameter
                project_id:  project_id, // Another example parameter
                jenis_file:jenis_file,
                shift:shift,
                jam1: $("#jam").val(),
                jam2: $("#jam2").val()
            };

            // Send GET request using Axios
            axios.get('/api/v1/leader-activity-download', { params })
                .then(function(response) {
                    // Handle success response
                    const paths = response.data.path; // Pastikan backend mengirimkan key `paths` berisi array
        
                    // Buat elemen link sementara
                    const link = document.createElement('a');
                    link.href = paths; // Set path dari array
                    link.target = '_blank'; // Buka di tab baru
                    
                    // Tambahkan atribut download (opsional untuk mengatur nama file)
                    link.setAttribute('download', response.data.file_name);

                    // Tambahkan ke body
                    document.body.appendChild(link);
                    
                    // Klik link untuk memulai unduhan
                    link.click();
                    
                    // Hapus link setelah digunakan
                    document.body.removeChild(link);

                    $('#loadingBackdrop').hide();
                    alert('File downloaded successfully');
                    // Optionally, you can handle the response, like redirecting to a download URL
                    // window.location.href = response.data.downloadUrl; 
                })
                .catch(function(error) {
                    // Handle error response
                    console.error('Error downloading file', error);
                    $('#loadingBackdrop').hide();
                });
        });
    });

    flatpickr("#tanggal_report", {
        mode: "range",
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0];
                const endDate = selectedDates[1];

                // Calculate the difference in time and then convert to days
                const timeDiff = endDate - startDate;
                const dayDiff = timeDiff / (1000 * 3600 * 24); // Convert milliseconds to days

                // Show alert if the difference is greater than 31 days
                if (dayDiff > 7) {
                    alert("MAKSIMAL 7 HARI");
                    // Optionally clear the selected dates
                    instance.clear(); // Uncomment if you want to clear the selection
                    return; // Exit the function if the alert is shown
                }
            }
        }
    }); 
</script>
@endpush
