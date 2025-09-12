@extends('layout.master')
@php 
        $user = Auth::user();
        $dataLogin = json_decode(Auth::user()->permission); 
        $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
        $project_id = json_decode(Auth::user()->project_id);
    @endphp
<style>
  /* styles.css */
.loading-backdrop {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 9999; /* High z-index to ensure it covers other elements */
    align-items: center;
    justify-content: center;
    display: flex;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    color: #fff; /* White text color */
    border: 4px solid rgba(255, 255, 255, 0.3); /* Light border */
    border-radius: 50%;
    border-top: 4px solid #fff; /* White top border for spinner effect */
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite; /* Spin animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Data Patroli</h6>
                    
                </div>
                <div class="tombol-pembantu d-flex">
                    <div class="dropdown">
                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-lg text-muted pb-3px align-self-center" data-feather="align-justify"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#download">
                                <i data-feather="download" class="icon-sm me-2"></i> Download Data Patroli
                            </a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="{{ route('task-report') }}">
                                <i data-feather="file-text" class="icon-sm me-2"></i> Report
                            </a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#import-data">
                                <i data-feather="upload" class="icon-sm me-2"></i> Import Excel
                            </a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="#" data-bs-toggle="modal" data-bs-target="#taskModel">
                                <i data-feather="plus" class="icon-sm me-2"></i> Tambah Patrol
                            </a>
                            <a class="dropdown-item d-flex align-items-center me-2" href="{{ route('task-download-qr', ['id' => @$_GET['project_id'] ?: 1]) }}" target="_blank">
                                <i data-feather="download" class="icon-sm me-2"></i> Export QR
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
            
            <div class="card-body">
            @if($project_id==NULL)
            <form class="row g-3">
                <div class="col-auto">
                    <label for="staticEmail2" class="visually-hidden">Project </label>
                    <select name="project_id" class="form-control select2">
                        <option value="">-- Select Project -- </option>
                        @if($project)
                            
                            @foreach($project as $pr)
                                @php
                                    if($project_id==$pr->id){
                                        $selected="selected";
                                    }else{
                                        $selected="";
                                    }
                                @endphp
                                <option value="{{ $pr->id }}" {{$selected}}>{{ $pr->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">Filter</button>
                </div>
            </form>
            @endif
                
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Jadwal Per-shift</th>
                                
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($records as $record)
                                <?php 
                                    if($record->status == 0){
                                        $label = "Non Active";
                                        $class = "badge rounded-pill bg-primary";
                                    }else{
                                        $label = "Active";
                                        $class = "badge rounded-pill bg-success";
                                    }
                                ?>
                                @php
                                if($record->latitude && $record->longitude){
                                    $longlat ='Longlat : '. $record->longitude.' - '.$record->latitude;
                                }else{
                                    $longlat="Longlat : -   ";
                                }
                                @endphp
                            <tr>
                                <td> {{$nomor++}} </td>
                                <td> 
                                    {{ $record->judul }} <br/> {{ $record->project_name }}<br/>{{$longlat}}
                                </td>
                                <td></td>
                                <td><span class="<?php echo $class ?>">{{ $label }}</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('report-patrol', ['id' => $record->unix_code]) }}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('add_task', ['id' => $record->id]) }}" >
                                                <i data-feather="list" class="icon-sm me-2"></i>
                                                <span class="">Add List Task</span>
                                            </a>

                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('qrcode', ['id' => $record->unix_code]) }}" >
                                                <i data-feather="grid" class="icon-sm me-2"></i>
                                                <span class="">Show QR</span>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#longlat{{$record->unix_code}}">
                                                <i data-feather="map" class="icon-sm me-2"></i>
                                                <span class="">Add Longlat</span>
                                            </a>
                                          
                                            <form action="{{ route('task.destroy', $record->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
                                                @csrf @method('DELETE') 
                                                <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $record->id }}')">
                                                    <i data-feather="trash" class="icon-sm me-2"></i>
                                                    <span class="">Delete</span>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="longlat{{$record->unix_code}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $record->project_name }} - {{ $record->judul }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('task-update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="unix_code" value="{{$record->unix_code}}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control">    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control">    
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="import-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('import-excel-patroli')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">File</label>
                            <input type="file" class="form-control" name="file_excel" required>    
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data FNG -->
<div class="modal fade" id="taskModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('task.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Project</label>
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="">PILIH PROJECT</option>
                                
                                @foreach($project as $row)
                                @php
                                    if($project_id==$row->id){
                                        $selected="selected";
                                    }else{
                                        $selected="";
                                    }
                                @endphp
                                <option value="{{ $row->id }}" {{$selected}}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 1</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_1">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_1">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 2</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_2">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_2">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 3</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_3">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_3">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Shift 4</label>
                            <input type="time" class="form-control" name="jam_mulai_shift_4">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp</label>
                            <input type="time" class="form-control" name="jam_akhir_shift_4">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Download Data Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form id="download_file">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Filter Tanggal</label>
                            @if(empty($project_id))
                                <input type="text" class="form-control" name="tanggal" required id="tanggal_report">
                            @else 
                                <input type="date" class="form-control" name="tanggal" required id="tanggal_report">
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Filter Jam</label>
                            <input type="time" class="form-control" name="jam" required id="jam">    
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">&nbsp;</label>
                            <input type="time" class="form-control" name="jam2" required id="jam2">    
                        </div>

                        @if(empty($project_id))
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Pilih Jenis File</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filter_type" id="excel" value="excel" required>
                                    <label class="form-check-label" for="excel">Excel</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filter_type" id="pdf" value="pdf" required>
                                    <label class="form-check-label" for="pdf">PDF</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Pilih Shift</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shift" id="pagi" value="pagi" required>
                                    <label class="form-check-label" for="pagi">PAGI</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shift" id="midle" value="midle" required>
                                    <label class="form-check-label" for="midle">MIDLE</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shift" id="malam" value="malam" required>
                                    <label class="form-check-label" for="malam">MALAM</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div id="project_list"></div>
                        
                        
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="button" id="download_file_patrol">Download</button>
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
  
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
  
    
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Task Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
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
        $('#loadingBackdrop').hide(); // Ensure the loading backdrop is hidden initially

        $('#download_file_patrol').on('click', function() {
            $('#loadingBackdrop').show(); // Show the loading backdrop only when the button is clicked
            
            var project = "{{ $project_id ?? '' }}";
            let project_id = project || $("#project_id_filter").val();

            var jenis_file = $('input[name="filter_type"]:checked').val() || "pdf";
            var shift = $('input[name="shift"]:checked').val();

            const params = {
                tanggal: $("#tanggal_report").val(),
                project_id: project_id,
                jenis_file: jenis_file,
                shift: shift,
                jam1: $("#jam").val(),
                jam2: $("#jam2").val()
            };

            axios.get('/api/v1/download_file_patrol', { params })
                .then(function(response) {
                    const jobId = response.data.job_id;

                    // Mulai polling untuk cek status job setiap 3 detik
                    const interval = setInterval(() => {
                        axios.get('/api/v1/report_job_status/' + jobId)
                            .then(function(res) {
                                const status = res.data.status;

                                if (status === 'done') {
                                    clearInterval(interval);
                                    $('#loadingBackdrop').hide();

                                    // Download semua file
                                    const files = res.data.files; // Array file paths
                                    files.forEach((path) => {
                                        const link = document.createElement('a');
                                        link.href = path;
                                        link.target = '_blank';
                                        const fileName = path.split('/').pop();
                                        link.setAttribute('download', fileName);
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);
                                    });

                                    alert('File downloaded successfully');
                                } else if (status === 'failed') {
                                    clearInterval(interval);
                                    $('#loadingBackdrop').hide();
                                    alert('Failed to generate report: ' + res.data.error);
                                }
                                // Jika pending / processing, terus polling
                            })
                            .catch(function(err) {
                                clearInterval(interval);
                                $('#loadingBackdrop').hide();
                                alert('Error checking job status');
                            });
                    }, 1800000); // setiap 30 menit
                })
                .catch(function(error) {
                    alert('Request Timeout');
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


            var project = "{{ $project_id ?? '' }}"; // Default to empty string if undefined
            $("#project_list").empty();
            // Check if the project ID is empty
            if (!project) {
                $("#project_list").append(`
                    <div class="col-md-12 mb-2"> 
                        <label for="" class="form-label">Project</label>
                        <select name="project_id" id="project_id_filter" class="form-control select2">
                            <option value="">-- Select Project -- </option>
                            @if(isset($project) && count($project) > 0)
                                @foreach($project as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                @endforeach
                            @endif
                        </select> 
                    </div>
                `);

            }
            
        }
    }); 
</script>
@endpush