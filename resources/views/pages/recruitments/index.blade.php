@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    .backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        display: none; /* Hide by default */
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>
@endpush

@section('content')
<div id="loading-backdrop" class="backdrop">
    <div class="spinner"></div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Job Applicant</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Melamar</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Detail</th>
                                <th>Status</th>
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
</div>

<form id="myForm" enctype="multipart/form-data">
    <!-- Your form fields go here -->
</form>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTableExample').DataTable({
            processing: true,
            columns: [
                {
                    data: null,
                    title: '#',
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Return row index + 1 for 1-based index
                    }
                }, // Use 'null' to auto-generate row number
                {
                    data: 'tanggal',
                    title: 'Tanggal Melamar',
                    render: function(data) {
                        var date = new Date(data);
                        var options = { day: '2-digit', month: 'long', year: 'numeric' };
                        return date.toLocaleDateString('id-ID', options); // Format date to 'd F Y'
                    }
                },
                { data: 'nik', title: 'NIK' },
                { data: 'nama', title: 'Nama Lengkap', render: function(data, type, row) {
                    var genderIcon = row.jenis_kelamin === 'Pria' ? 
                        '<img src="https://hris.truest.co.id/images/male.png" style="width:25px">' : 
                        '<img src="https://hris.truest.co.id/images/female.png" style="width:25px">'; // Use female image for non-PRIA
                    return `${genderIcon} ${data}`; // Combine icon with name
                }},
                { data: 'nomor_wa', title: 'Detail', render: function(data, type, row) {
                    return `
                        Nomor WA: ${data}<br>
                        TB: ${row.tb} ${row.lolos_tb}<br>
                        BB: ${row.bb}<br>
                        USIA: ${row.usia} ${row.lolos_usia}<br>
                        BMI: ${row.bmi} ${row.lolos_bmi}`;
                    }
                },
                { data: 'status', title: 'Status' }
            ]
        });

        // Show loading spinner
        $('#loading-backdrop').show();

        // Fetch data
        axios.get('https://data.cityservice.co.id/cs/public/api/v2/job-aplicant')
            .then(function(response) {
                console.log(response.data);

                // Hide loading spinner
                $('#loading-backdrop').hide();

                // Clear and populate DataTable
                table.clear().rows.add(response.data.records).draw();
            })
            .catch(function(error) {
                console.error(error);

                // Hide loading spinner
                $('#loading-backdrop').hide();

                // Show error message
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            });
    });
   
</script>

<script>
    flatpickr("#daterange_picker", {
        mode: "range",
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            console.log(dateStr); // Date range in 'Y-m-d to Y-m-d' format
        }
    });
</script>
@endpush
