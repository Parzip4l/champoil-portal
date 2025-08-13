@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 align-self-center">
                    Apps Versions
                    <a href="#" 
                       class="btn btn-sm btn-primary ml-3" 
                       data-bs-toggle="modal" 
                       data-bs-target="#taskModel" 
                       style="float:right">New Version</a>
                </h5>
                
                
            </div>
            
            <div class="card-body">
                <div id="versions-container">
                    <!-- Data will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data FNG -->
<div class="modal fade" id="taskModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Adjusted modal size to large -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Version</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form id="versionForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3"> <!-- Added spacing between fields -->
                        <div class="col-md-6">
                            <label for="app_name" class="form-label">App Name</label>
                            <input type="text" class="form-control" name="app_name" maxlength="100" placeholder="TRUEST" value="TRUEST" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="platform_id" class="form-label">Platform</label>
                            <select class="form-control" name="platform_id" required>
                                <option value="" disabled selected>Select platform</option>
                                <option value="1">Android</option>
                                <option value="2">iOS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="version_code" class="form-label">Version Code</label>
                            <input type="number" class="form-control" name="version_code" placeholder="Enter version code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="version_name" class="form-label">Version Name</label>
                            <input type="text" class="form-control" name="version_name" maxlength="20" placeholder="Enter version name" required>
                        </div>
                        <div class="col-md-12">
                            <label for="changelog" class="form-label">Changelog</label>
                            <textarea class="form-control" id="changelog-editor" name="changelog" rows="5" placeholder="Enter changelog"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="release_type" class="form-label">Release Type</label>
                            <select class="form-control" name="release_type" required>
                                <option value="beta">Beta</option>
                                <option value="stable" selected>Stable</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="released_at" class="form-label">Released At</label>
                            <input type="datetime-local" class="form-control" name="released_at" readonly value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary w-100" type="button" onclick="submitVersionForm()">Save Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>

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

    document.addEventListener('DOMContentLoaded', function () {
        fetch('{{ url("api/v1/version/list") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('versions-container');
                if (data && data.length > 0) {
                    let html = '';
                    data.forEach(platform => {
                        const platformId = platform.app_platform.replace(/\s+/g, '-').toLowerCase();
                        html += `<div class="mb-5">
                                    <h4 class="text-primary" style="cursor: pointer;" onclick="toggleVisibility('${platformId}')">
                                        ${platform.app_platform} <span id="${platformId}-toggle" class="text-muted">(Show)</span>
                                    </h4>
                                    <div id="${platformId}-content" class="timeline" style="display: none;">`;
                        if (platform.versions && platform.versions.length > 0) {
                            platform.versions.forEach(version => {
                                html += `<div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="fw-bold">${version.version_name}</h6>
                                                <p><strong>Changelog:</strong> ${version.changelog}</p>
                                                <p><strong>Release Type:</strong> ${version.release_type}</p>
                                                <p><strong>Released At:</strong> ${new Date(version.released_at).toLocaleDateString()}</p>
                                            </div>
                                         </div>`;
                            });
                        } else {
                            html += '<p class="text-muted">No versions available.</p>';
                        }
                        html += `</div>
                                </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-muted">No data available.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching versions:', error);
                document.getElementById('versions-container').innerHTML = '<p class="text-danger">Error loading data.</p>';
            });
    });

    function toggleVisibility(platformId) {
        const content = document.getElementById(`${platformId}-content`);
        const toggleText = document.getElementById(`${platformId}-toggle`);
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggleText.textContent = '(Hide)';
        } else {
            content.style.display = 'none';
            toggleText.textContent = '(Show)';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#changelog-editor',
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
            menubar: false,
            height: 300,
            content_style: `
                  body {
                      font-family: 'Poppins', sans-serif;
                      font-size: 14px;
                      color: #212529;
                  }
                  a {
                      color: #007bff;
                  }
              `
        });
    });

    function submitVersionForm() {
        const form = document.getElementById('versionForm');
        const formData = new FormData(form);

        axios.post('/api/v1/version/store', formData, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(response => {
            const data = response.data;
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Version saved successfully!',
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred while saving the version.',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving the version.',
            });
        });
    }
</script>
<style>
    .timeline {
        position: relative;
        padding: 1rem 0;
        list-style: none;
        border-left: 2px solid #007bff;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 2rem;
        text-align: left; /* Align content to the left */
    }
    .timeline-marker {
        position: absolute;
        left: -0.5rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        background-color: #007bff;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    .timeline-content {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .timeline-content h6 {
        margin-bottom: 0.5rem;
    }
    .timeline-content p {
        margin-bottom: 0.25rem;
    }
</style>
@endpush