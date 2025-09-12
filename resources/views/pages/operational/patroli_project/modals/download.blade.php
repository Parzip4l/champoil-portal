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

<div id="loadingBackdrop" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white;">
        Loading...
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#loadingBackdrop').hide(); // Ensure the loading backdrop is hidden initially

        $('#download_file_patrol').on('click', function() {
            $('#loadingBackdrop').show(); // Show the loading backdrop only when the button is clicked
            
            var project = "{{ $project_id ?? '' }}";
            let project_id = project || $("#project_id").val();

            const params = {
                tanggal: $("#tanggal_report").val(),
                project_id: project_id,
                jam1: $("#jam1").val(),
                jam2: $("#jam2").val()
            };

            axios.get('/api/v1/patroli-activity-download', { params })
                .then(function(response) {
                    const jobId = response.data.job_id;

                    // Start polling to check job status every 1 minute
                    const interval = setInterval(() => {
                        axios.get('/api/v1/report_job_status/' + jobId)
                            .then(function(res) {
                                const status = res.data.status;

                                if (status === 'done') {
                                    clearInterval(interval);
                                    $('#loadingBackdrop').hide();

                                    const files = res.data.files; // Array or single file path
                                    if (files) {
                                        if (typeof files === 'string') {
                                            // Handle single file
                                            downloadFile(files);
                                        } else if (Array.isArray(files)) {
                                            // Handle multiple files
                                            files.forEach((path) => downloadFile(path));
                                        }
                                        alert('File downloaded successfully');
                                    } else {
                                        alert('No files available for download.');
                                    }
                                } else if (status === 'failed') {
                                    clearInterval(interval);
                                    $('#loadingBackdrop').hide();
                                    alert('Failed to generate report: ' + res.data.error);
                                }
                                // If pending/processing, continue polling
                            })
                            .catch(function(err) {
                                clearInterval(interval);
                                $('#loadingBackdrop').hide();
                                console.error('Error:', err); // Log the error for debugging
                                alert('An error occurred while checking job status. Please try again.');
                            });
                    }, 60000); // Every 1 minute
                })
                .catch(function(error) {
                    alert('Request Timeout');
                    $('#loadingBackdrop').hide();
                });    
        });
    });

    function downloadFile(filePath) {
        const link = document.createElement('a');
        link.href = filePath;
        link.target = '_blank';
        const fileName = filePath.split('/').pop();
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
