@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Data Task</h5>
                <div class="btn-group">
                    <a href="#" class="btn btn-success">Report</a>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#knowledgeModal">Tambah Task</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Project</th>
                                <th>User</th>
                                <th>Mengulang</th>
                                <!-- <th>Upload Photo</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($records as $record)
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td>{{ $record->task_name }}</td>
                                <td>{{ $record->project }}</td>
                                <td>{{ $record->assign }}</td>
                                <td>{{ $record->repeat_task }}</td>
                                <!-- <td>{{ $record->upload_file }}</td> -->
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item d-flex align-items-center" 
                                               href="{{ route('task_edit',['id'=>$record->id]) }}">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                                <span class="">Details</span>
                                            </a>
                                            @if($record->count_cek > 0)
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('start_class', ['id' => $record->id]) }}">
                                                    <i data-feather="book" class="icon-sm me-2"></i>
                                                    <span class="">Start Class </span>
                                                </a>
                                            @endif
                                            <form action="{{ route('taskg.destroy', $record->id) }}" method="POST" id="delete_contact" class="contactdelete"> 
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
                            @php 
                                $nomor++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data FNG -->
<div class="modal fade" id="knowledgeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
            <form
                    action="{{route('taskg.store')}}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Unit Bisnis</label>
                            <select name="unit_bisnis" id="unit_bisnis" class="form-control">
                                <option value="Champoil">CHAMPOIL</option>
                                <option value="Kas">KAS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2" id="div_project">
                            <label for="" class="form-label">Project</label>
                            <select name="project" class="form-control">
                                <option value="0">All Project</option>
                                @foreach($project as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Nama Task</label>
                            <input type="text" class="form-control" name="task_name" required="required"></div>
                            <div class="col-md-12 mb-2">
                                <!-- <label for="" class="form-label">Upload Photo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="upload_file" value="1" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        YES
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="0" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        NO
                                    </label>
                                </div> -->
                                
                                Employee
                                <select class="form-control employeeSelect" id="employeeSelect" name="assign[]" multiple>
                                    <!-- Add options for employees here -->
                                </select>

                                <label for="" class="form-label">Mengulang</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="repeat_task" value="1" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Hanya Satu Kali
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="repeat_task" value="2"  id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Harian
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="repeat_task" value="3"  id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Mingguan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="repeat_task" value="4"  id="flexRadioDefault3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        Bulanan
                                    </label>
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

<!-- End -->
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>

  <script>
        $(document).ready(function() {
            // Event listener for changes in the UnitBisnis select
            $('#unit_bisnis').change(function() {
                // Get the selected unit bisnis
                const selectedUnitBisnis = $(this).val();

                // Update the list of employees based on the selected unit bisnis
                updateEmployeeOptions(selectedUnitBisnis);
            });

            // Function to update the list of employees based on unit bisnis
            function updateEmployeeOptions(unitBisnis) {
    const employeeSelect = $('.employeeSelect');

    // Perform an AJAX request to fetch employees based on the unit bisnis
    $.ajax({
        url: "{{ route('employee.unit') }}", // Adjust the URL accordingly
        method: 'GET',
        data: { unit_bisnis: unitBisnis },
        success: function(response) {

            // Clear previous options
            employeeSelect.empty();

            // Add options for employees
            if (Array.isArray(response.employees)) {
                $.each(response.employees, function(key, value) {
                    employeeSelect.append('<option value="' + value.nik + '">' + value.nama + '</option>');
                });
            } else {
                console.error('Invalid response format: employees is not an array.');
            }

            // Initialize Select2 after updating options
            employeeSelect.select2({
                dropdownCss: {
                    'z-index': 1000 // Adjust the value as needed
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching employees:', error);
        }
    });
}

// Assuming your modal has an ID of 'yourModal'
$('#yourModal').on('shown.bs.modal', function () {
    // Update employee options after the modal is fully shown
    updateEmployeeOptions(yourUnitBisnisValue);
});


            // Event listener to select all employees
            $('#selectAll').click(function() {
                $('.employeeSelect option').prop('selected', true);
                $('.employeeSelect').trigger('change');
            });

            // Event listener to deselect all employees
            $('#deselectAll').click(function() {
                $('.employeeSelect option').prop('selected', false);
                $('.employeeSelect').trigger('change');
            });
        });
    </script>
  <script>
        $('#div_project').hide();
        $('#unit_bisnis').on('change', function(){
            var unit_bisni = $(this).val();
            if(unit_bisni === "Kas"){
                $('#div_project').show();
            }else{
                $('#div_project').hide();
            }
            // Add your custom logic here
        });
  </script>

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
                const deleteUrl = "{{ route('taskg.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Contact Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Contact Failed to Delete',
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
</script>
@endpush