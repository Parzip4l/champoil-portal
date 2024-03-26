@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-4 grid-margin">
        <div class="card">
            <div class="card-header">
            <h4>Detail Task</h4>
            </div>
            <div class="card-body">
            <form action="{{ route('taskg.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="unit_bisnis" class="form-label">Unit Bisnis</label>
                            <select name="unit_bisnis" id="unit_bisnis" class="form-control">
                            <option value="Champoil" {{ isset($master->unit_bisnis) && $master->unit_bisnis == 'Champoil' ? 'selected' : '' }}>Champoil</option>
                            <option value="Kas" {{ isset($master->unit_bisnis) && $master->unit_bisnis == 'Kas' ? 'selected' : '' }}>KAS</option>

                            </select>
                        </div>
                        <div class="col-md-12 mb-2" id="div_project">
                            <label for="project" class="form-label">Project</label>
                            <select name="project" class="form-control">
                            <option value="0" {{ isset($master->project) && $master->project == 0 ? 'selected' : '' }}>All Projct</option>

                                @foreach($project as $row)
                                <option value="{{ $row->id }}" {{ isset($master->project) && $master->project == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="task_name" class="form-label">Nama Task</label>
                            <input type="text" class="form-control" value="{{$master->task_name}}" name="task_name" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="employeeSelect" class="form-label">Employee</label>
                            <select class="form-control employeeSelect" id="employeeSelect" name="assign[]" multiple>
                                <!-- Add options for employees here -->
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Mengulang</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="repeat_task" 
                                       value="1" 
                                       {{ isset($master->repeat_task) && $master->repeat_task == 1 ? 'checked' : '' }}
                                       id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">Hanya Satu Kali</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="repeat_task" 
                                       value="2" 
                                       {{ isset($master->repeat_task) && $master->repeat_task == 2 ? 'checked' : '' }}
                                       id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">Harian</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="repeat_task" 
                                       value="3" 
                                       {{ isset($master->repeat_task) && $master->repeat_task == 3 ? 'checked' : '' }}
                                       id="flexRadioDefault3">
                                <label class="form-check-label" for="flexRadioDefault3">Mingguan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="repeat_task" 
                                       value="4" 
                                       {{ isset($master->repeat_task) && $master->repeat_task == 4 ? 'checked' : '' }}
                                       id="flexRadioDefault4">
                                <label class="form-check-label" for="flexRadioDefault4">Bulanan</label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-header">
                <h4>Checklist Item</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('save-task-item') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_master" value="{{$master->id}}">
                <table class="table table-bordered" id="item">
                    <thead>
                        <tr>
                            <td width="20">No</td>
                            <td>Task</td>
                            <td width="40">File Upload</td>
                            <td width="40">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if($records)
                            @php 
                                $nomor=1;
                            @endphp
                            @foreach($records as $item)
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td>
                                    <textarea name="task_name[]" class="form-control">{{$item->task_name}}</textarea>
                                    <input type="hidden" name="id[]" value="{{$item->id}}">
                                </td>
                                <td>
                                    <select name="file_upload[]" class="form-control">
                                        <option value="0"
                                                {{ isset($item->upload_file) && $item->upload_file == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1"
                                                {{ isset($item->upload_file) && $item->upload_file == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>

                            @php 
                                $nomor++;
                            @endphp
                            @endforeach
                        @endif
                        
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary text-white mt-3" style="float:right">Submit</button>
                <a href="javascript:void(0)" class="btn btn-warning text-white mt-3" id="btn_add" style="float:right;margin-right:10px !important">Add Task</a>
                
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
        $('#btn_add').click(function(event) {
            var nomor = $('#item tbody tr').length + 1;
            $('#item tbody').append('<tr id="'+nomor+'"><td>'+ nomor +'</td><td>\
                                <textarea name="task_name[]" class="form-control"></textarea>\
                                <input type="hidden" name="id[]" value="0">\
                            </td>\
                            <td>\
                                <select name="file_upload[]" class="form-control">\
                                    <option value="0">No</option>\
                                    <option value="1">Yes</option>\
                                </select>\
                            </td>\
                            <td>\
                            <a href="javascript:void(0)" onClick="remove_row('+nomor+')" class="btn btn-danger">Remove</a>\
                            </td></tr>');
        });

        remove_row=function(id){
            $('#'+id).remove();
        }
    });
</script>
<script>
    $('#div_project').hide();
    var id_unit = "{{$master->unit_bisnis}}";
    if(id_unit==='Champoil'){
        $('#div_project').hide();
    }else{
        $('#div_project').show();
    }
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