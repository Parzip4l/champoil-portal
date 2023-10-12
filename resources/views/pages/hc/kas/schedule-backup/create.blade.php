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
                <h5 class="mb-0 align-self-center">Create Backup Schedule</h5>
            </div>
            <div class="card-body">
                <form action="{{route('backup-schedule.store')}}" method="POST">
                    @csrf
                    <div class="schedule-backup-wrap">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control tanggal" name="tanggal[]" id="tanggal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Man Power</label>
                                    <select class="js-example-basic-single form-select employee" data-width="100%" name="employee[]" id="employee">
                                        <option disabled selected>Select Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Periode</label>
                                    <input type="text" class="form-control" name="periode[]" value="{{$current_month}}-{{$current_year}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Project</label>
                                    <select class="js-example-basic-single form-select" data-width="100%" name="project[]">
                                        <option disabled selected>Select Project</option>
                                        @foreach($project as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Shift</label>
                                    <select class="js-example-basic-single form-select" data-width="100%" name="shift[]">
                                        <option disabled selected>Select Shift</option>
                                        <option value="BCK-P">Backup Pagi</option>
                                        <option value="BCK-M">Backup Middle</option>
                                        <option value="BCK-ML">Backup Malam</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="AddEmployee" class="btn btn-success mt-1 mb-3">Tambah Schedule Baru</button>
                    <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
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
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    
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
<script>
    function addProductRow() {
        const newRow = `
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="" class="form-label">Tanggal</label>
                    <input type="date" class="form-control tanggal" name="tanggal[]" id="tanggal">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="" class="form-label">Man Power</label>
                    <select class="js-example-basic-single form-select employee" data-width="100%" name="employee[]" id="employee">
                        <option disabled selected>Select Employee</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="" class="form-label">Periode</label>
                    <input type="text" class="form-control" name="periode[]" value="{{$current_month}}-{{$current_year}}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="" class="form-label">Project</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="project[]">
                        <option disabled selected>Select Project</option>
                        @foreach($project2 as $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="" class="form-label">Shift</label>
                    <select class="js-example-basic-single form-select form-control" data-width="100%" name="shift[]">
                        <option disabled selected>Select Shift</option>
                        <option value="BCK-P">Backup Pagi</option>
                        <option value="BCK-M">Backup Middle</option>
                        <option value="BCK-ML">Backup Malam</option>
                    </select>
                </div>
            </div>
        </div>
        `;
        document.querySelector('.schedule-backup-wrap').insertAdjacentHTML('beforeend', newRow);
        const newRowElement = document.querySelector('.schedule-backup-wrap .tanggal:last-child');
        newRowElement.addEventListener('change', function() {
            updateEmployeeOptions();
        });
    }
    document.getElementById('AddEmployee').addEventListener('click', addProductRow);

    $('.tanggal').change(function() {
        updateEmployeeOptions();
    });

    // Panggil saat tombol "Add Employee" diklik
    $('#AddEmployee').click(function() {
        updateEmployeeOptions();
    });

</script>
<script>
    function updateEmployeeOptions() {
        var selectedDate = document.querySelector('.tanggal').value;

        // AJAX Request
        $.ajax({
            url: "{{ route('getEmployeesWithDayOff.backup') }}",
            type: "GET",
            data: {
                tanggal: selectedDate
            },
            success: function(response) {
                var selectEmployee = $('.employee');
                selectEmployee.empty().append('<option disabled selected>Select Employee</option>');
                $.each(response.employees, function(key, value) {
                    selectEmployee.append('<option value="' + value.nik + '">' + value.nama + '</option>');
                });
            }
        });
    }

    // Panggil saat tanggal berubah
    $('.tanggal').change(function() {
        updateEmployeeOptions();
    });
</script>
@endpush