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

<div class="modal fade" id="download" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Filter Download</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="download_file">
                    @csrf
                    <input type="hidden" name="project_id" id="project_id" value="{{ $project_id }}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="tanggal_report" class="form-label">Filter Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal_report" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="jam" class="form-label">Filter Jam</label>
                            <input type="time" class="form-control" name="jam1" id="jam1" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="jam2" class="form-label">Sampai Jam</label>
                            <input type="time" class="form-control" name="jam2" id="jam2" required>
                        </div>
                        <div id="project_list"></div>
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-primary w-100" id="download_file_patrol" onclick="downloadFile()">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadFile() {
        const formData = new FormData(document.getElementById('download_file'));
        const params = new URLSearchParams();

        formData.forEach((value, key) => {
            params.append(key, value);
        });

        // Add project_id parameter
        const projectId = document.querySelector('#project_id')?.value || '';
        if (projectId) {
            params.append('project_id', projectId);
        }

        fetch(`/api/v1/patroli-activity-download?${params.toString()}`, { // Changed to GET with query parameters
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Expect JSON response
        })
        .then(data => {
            if (data.path) {
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = data.path; // Use the file path from the response
                a.download = data.file_name || 'patrol_file.xlsx'; // Use the file name from the response
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(data.path);
            } else {
                console.error('File path not found in the response');
            }
        })
        .catch(error => {
            console.error('There was a problem with the download request:', error);
        });
    }
</script>
