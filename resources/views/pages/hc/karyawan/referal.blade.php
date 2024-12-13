@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Employee Referral Data</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
             <div class="table-responsive">
                <table id="dataTableExample" class="table table-bordered">
                    <thead>
                    <tr>
                        <th rowspan="2" valign="middle" style="text-align:center">#</th>
                        <th rowspan="2" valign="middle" style="text-align:center">Name</th>
                        <th rowspan="2" valign="middle" style="text-align:center">Employee Code</th>
                        <th rowspan="2" valign="middle" style="text-align:center">Gender</th>
                        <th rowspan="2" valign="middle" style="text-align:center">Organization</th>
                        <th rowspan="2" valign="middle" style="text-align:center">Position</th>
                        <th colspan="4" valign="middle" style="text-align:center">Referral Data</th>
                    </tr>
                    <tr>
                        <th valign="middle" style="text-align:center">Referral Used</th>
                        <th valign="middle" style="text-align:center">Referral Paid</th>
                        <th valign="middle" style="text-align:center">Referral Resigned</th>
                    </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Data Rows will be injected here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function () {
        // Get the bearer token from the Blade template
        var bearerToken = "{{ session('barrier') }}";

        // Only make the API request if the token exists
        if (bearerToken) {
            axios.get('/api/v1/referal-list', {
                headers: {
                    'Authorization': `Bearer ${bearerToken}`
                }
            })
            .then(response => {
                console.log('Response:', response.data);

                // Initialize DataTable with the response data
                var table = $('#dataTableExample').DataTable({
                    data: response.data.data, // Assuming response data contains 'records'
                    columns: [
                        { 
                            // Custom column for the row number
                            render: function (data, type, row, meta) {
                                return meta.row + 1; // Automatically generates row number starting from 1
                            },
                            title: '#', // Header for the row number column
                            orderable: false // Disable ordering for this column
                        }, // Replace with actual field name
                        { data: 'nama' }, // Replace with actual field name
                        { data: 'nik' }, // Replace with actual field name
                        { data: 'nik' }, // Replace with actual field name
                        { data: 'organisasi' }, // Replace with actual field name
                        { data: 'jabatan' }, // Replace with actual field name
                        { data: 'referal_used' }, // Replace with actual field name
                        { data: 'referal_paid' }, // Replace with actual field name
                        { data: 'referal_resign' }, // Replace with actual field name
                    ]
                });
            })
            .catch(error => {
                console.error('Error:', error);
                // Optionally, show an error message
                Swal.fire('Error', 'Failed to load referral data.', 'error');
            });
        } else {
            console.error('Bearer token is not available.');
            // Optionally, show an error message
            Swal.fire('Error', 'Token is missing.', 'error');
        }
    });
</script>
@endpush
