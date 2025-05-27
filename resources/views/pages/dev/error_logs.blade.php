@extends('layout.master')

@push('plugin-styles')
<!-- Add SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Add jQuery CDN (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Add DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />

<style>
    /* Ensure table cells wrap text after 50 characters */
    table.table td {
        word-break: break-word;
        white-space: normal;
        max-width: 50ch; /* Limit to 50 characters */
        overflow-wrap: break-word;
    }
    .card-header {
        font-size: 1.2rem;
        font-weight: bold;
    }
    .card-body p {
        font-size: 2rem;
    }
    .table th {
        vertical-align: middle;
        text-align: center; /* Keep header centered */
    }
    .filter-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .card {
        border-radius: 8px;
    }
    .table-container {
        overflow-x: auto;
    }
    .table thead th {
        background-color: #343a40;
        color: #fff;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filter Logs</h5>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label for="date-range-picker" class="form-label">Select Date Range</label>
                        <input type="text" id="date-range-picker" class="form-control shadow-sm" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                    </div>
                    <div class="col-md-3 text-end">
                        <button id="filter-button" type="button" class="btn btn-primary shadow-sm w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white text-center">ERROR</div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-danger"></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info shadow-sm">
            <div class="card-header bg-info text-white text-center">INFO</div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-info"></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning shadow-sm">
            <div class="card-header bg-warning text-dark text-center">RASIO</div>
            <div class="card-body text-center">
                <p class="card-text display-4 fw-bold text-warning"></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Error Messages</h5>
            </div>
            <div class="card-body p-0 table-container">
                <table id="error-messages-table" class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Message</th>
                            <th>File Locations</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        const filterButton = $('#filter-button');
        const dateRangePicker = $('#date-range-picker');
        const errorMessagesTable = $('#error-messages-table').DataTable({
            pageLength: 10, // Show 10 rows per page
            lengthChange: false, // Disable changing the number of rows per page
            searching: false, // Disable search functionality
            ordering: false, // Disable column ordering
            info: false, // Disable table info
        });

        // Initialize Flatpickr for date range
        flatpickr(dateRangePicker[0], {
            mode: 'range',
            dateFormat: 'Y-m-d',
            onClose: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2 && selectedDates[0].getTime() !== selectedDates[1].getTime()) {
                    const startDate = selectedDates[0].toISOString().split('T')[0];
                    const endDate = selectedDates[1].toISOString().split('T')[0];
                    dateRangePicker.val(`${startDate} to ${endDate}`);
                } else if (selectedDates.length === 1) {
                    // Clear the input if only one date is selected
                    dateRangePicker.val('');
                }
            }
        });

        const fetchData = (startDate, endDate) => {
            // Show loading indicator
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching data, please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/api/v1/dev-stats?start=${startDate}&end=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Close the loading indicator

                    const levels = Object.keys(data.count.levelCounts);
                    const counts = data.count.levelCounts;

                    let totalLogs = 0;
                    let errorCount = 0;

                    levels.forEach(level => {
                        const count = counts[level] || 0;
                        totalLogs += count;

                        if (level === 'ERROR') {
                            errorCount = count;
                            $('.card.border-danger .card-text').text(count.toLocaleString());
                        } else if (level === 'INFO') {
                            $('.card.border-info .card-text').text(count.toLocaleString());
                        }
                    });

                    // Calculate the ratio for WARNING
                    const warningRatio = totalLogs > 0 ? ((errorCount / totalLogs) * 100).toFixed(2) : 0;
                    $('.card.border-warning .card-text').text(`${warningRatio}%`);

                    errorMessagesTable.clear(); // Clear existing rows
                    Object.values(data.list).forEach((item, index) => {
                        errorMessagesTable.row.add([
                            (index + 1).toLocaleString(),
                            item.date,
                            item.message,
                            item.controller,
                            item.count.toLocaleString()
                        ]);
                    });
                    errorMessagesTable.draw(); // Redraw the table
                })
                .catch(error => {
                    Swal.close(); // Close the loading indicator
                    console.error('Error fetching data:', error);
                    Swal.fire('Error', 'Failed to fetch data. Please try again later.', 'error');
                });
        };

        filterButton.on('click', function () {
            const dateRange = dateRangePicker.val().split(' to ');
            if (dateRange.length === 2) {
                const startDate = dateRange[0];
                const endDate = dateRange[1];
                fetchData(startDate, endDate);
            } else {
                Swal.fire('Warning', 'Please select a valid date range.', 'warning');
            }
        });

        // Fetch initial data with default range (Last 7 Days)
        const today = new Date();
        const last7Days = new Date();
        last7Days.setDate(today.getDate() - 7);
        const defaultStartDate = last7Days.toISOString().split('T')[0];
        const defaultEndDate = today.toISOString().split('T')[0];
        dateRangePicker.val(`${defaultStartDate} to ${defaultEndDate}`);
        fetchData(defaultStartDate, defaultEndDate);
    });
</script>
@endpush