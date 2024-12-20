@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Patroli Project</h4>
                <button id="addProjectBtn" class="btn btn-primary mb-3">Add Project</button>
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectModalLabel">Add/Edit Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="projectForm">
          <div class="mb-3">
            <label for="project_id" class="form-label">Project ID</label>
            <input type="number" class="form-control" id="project_id" name="project_id" value="582307" readonly="readonly" required>
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
      <div class="modal-header">
        <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div id="qrContainer"></div>
        <p id="qrProjectTitle" class="mt-3"></p>
        <button id="downloadQrBtn" class="btn btn-success mt-3">Download QR Code</button>
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
@endpush

@push('custom-scripts')
  <script>
    $(document).ready(function () {
        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: function (data, callback) {
                axios.get('/api/v1/patroli-projects-get')
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
    });

    function downloadQr(unixCode) {
        axios.get(`/api/v1/patroli-projects/${unixCode}/download`, { responseType: 'blob' })
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
                document.body.removeChild(link); // Clean up
            })
            .catch(error => {
                console.error('Error downloading the QR code:', error);
                alert('Failed to download QR code.');
            });
    }

  </script>
@endpush
