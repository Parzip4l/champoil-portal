@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    #loadingBackdrop {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        text-align: center;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    .spinner {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div id="loadingBackdrop">
    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
        <div>
            <i data-feather="loader" class="spinner"></i>
            <br/>
            Loading, please wait...
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">ATTENDANCE TRAINING </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align:center">Employee Name</th>
                                <th rowspan="2" style="text-align:center">Project</th>
                                <th colspan="5" style="text-align:center">Attendance Training</th>
                            </tr>
                            <tr>
                                <th style="text-align:center">SENIN</th>
                                <th style="text-align:center">SELASA</th>
                                <th style="text-align:center">RABU</th>
                                <th style="text-align:center">KAMIS</th>
                                <th style="text-align:center">JUMAT</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data will be populated by DataTables -->
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
        // Show the loading backdrop
        $('#loadingBackdrop').show();

        axios.get('/api/v1/skip-training')
            .then(response => {
                const records = response.data.data;

                // Clear the table body before populating it
                $('#tableBody').empty();

                // Loop through each record and add it to the table body
                records.forEach(record => {
                    let row = '';

                    // Check if 'training' is not empty and loop through training records
                    if (record.recruitments_id !== null) {
                        if(record.jumlah_training < 5){
                            row += `
                            <tr>
                                <td>${record.nama || ''}<br/>${record.nik || ''}</td>
                                <td>${record.project || ''}</td>
                                <td style="text-align:center">${record.training['MONDAY']}</td>
                                <td style="text-align:center">${record.training['TUESDAY']}</td>
                                <td style="text-align:center">${record.training['WEDNESDAY']}</td>
                                <td style="text-align:center">${record.training['THURSDAY']}</td>
                                <td style="text-align:center">${record.training['FRIDAY']}</td>
                            </tr>
                            `;
                        }
                    } else {
                        // If no training data, just add empty cells with a message
                        row += `
                            <tr>
                                <td>${record.nama || ''}<br/>${record.nik || ''}</td>
                                <td>${record.project || ''}</td>
                                <td colspan="5">NIK TIDAK DITEMUKAN DI RECRUITMENTS </td>
                                
                            </tr>
                        `;
                    }

                    // Close the row and append it to the table
                    
                    $('#tableBody').append(row);
                    feather.replace();

                });

                // Initialize DataTables for the table after adding rows
                if ($.fn.dataTable.isDataTable('#dataTableExample')) {
                    $('#dataTableExample').DataTable().clear().destroy();
                }
                $('#dataTableExample').DataTable();

                // Hide the loading backdrop once the data is loaded
                $('#loadingBackdrop').hide();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error fetching the training data.');
                // Hide the loading backdrop in case of error
                $('#loadingBackdrop').hide();
            });
    });
</script>
@endpush
