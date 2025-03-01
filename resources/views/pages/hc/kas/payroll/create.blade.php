@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="head-card d-flex justify-content-between mb-3">
            <h6 class="card-title align-self-center mb-0">Payroll Frontline Officer {{$start_date2}} - {{$end_date2}}</h6>
        </div>
        <hr>
        <form action="{{route('payroll-kas.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="form-label">Payroll Periode</label>
                        <select name="month" id="month" class="form-control" required>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Year</label>
                        <select name="year" class="form-control">
                            <option value="2024" {{ date('Y') == '2024' ? 'selected' : '' }}>2024</option>
                            <option value="2025" {{ date('Y') == '2025' ? 'selected' : '' }}>2025</option>
                        </select>
                        <input type="hidden" name="periode" id="periode" value="{{ $start_date2 }} - {{ $end_date2 }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Unit Bisnis</label>
                        <select name="unit_bisnis" id="UnitBisnis" class="form-control">
                            <option disabled selected>Pilih Unit Bisnis</option>
                            <option value="Kas">Kas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Select Employee</label>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="EmployeeTable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Select All/Deselect All</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control employeeSelect" id="employeeSelect" name="employee_code[]" multiple>
                                            <!-- Add options for employees here -->
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" id="selectAll">Select All</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="deselectAll">Deselect All</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Run Payroll</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Event listener for changes in the UnitBisnis select
            $('#UnitBisnis').change(function() {
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
                        employeeSelect.select2();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching employees:', error);
                    }
                });
            }

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
    <style>
        .select2-container {
            width : 100%!important;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.getElementById('month');
        const yearInput = document.getElementById('year');
        const periodeInput = document.getElementById('periode');

        const updatePeriode = () => {
            const month = monthSelect.value;
            const year = parseInt(yearInput.value);
            const monthsMap = {
                "Januari": 1,
                "Februari": 2,
                "Maret": 3,
                "April": 4,
                "Mei": 5,
                "Juni": 6,
                "Juli": 7,
                "Agustus": 8,
                "September": 9,
                "Oktober": 10,
                "November": 11,
                "Desember": 12
            };

            const selectedMonth = monthsMap[month];
            let startDate = new Date(year, selectedMonth - 1, 21);
            let endDate = new Date(year, selectedMonth, 20);

            // Adjust endDate if it rolls into the next year
            if (selectedMonth === 12) {
                endDate = new Date(year + 1, 0, 20);
            } else if (selectedMonth === 2) {
                // Handle February edge case if next month's 20th doesn't exist (like in non-leap years)
                endDate = new Date(year, selectedMonth, 0); // Last day of February
                endDate = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 20);
            } else {
                endDate = new Date(year, selectedMonth, 20);
            }

            const formatDate = (date) => {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                return `${day}-${month}-${date.getFullYear()}`;
            };

            periodeInput.value = `${formatDate(startDate)} - ${formatDate(endDate)}`;
        };

        monthSelect.addEventListener('change', updatePeriode);
        updatePeriode(); // Initial call to set default values
    });
</script>
@endpush
