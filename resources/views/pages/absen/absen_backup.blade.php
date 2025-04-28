@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Backup Absensi</h4>
            <button class="btn btn-light btn-sm" id="refresh-button">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="filter-period" class="form-label fw-bold">Filter Periode</label>
                    <input type="text" id="filter-period" class="form-control monthpicker" placeholder="Pilih Bulan">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="filter-period-button">
                        <i class="fas fa-filter"></i> Filter Periode
                    </button>
                </div>
                <!-- <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-success w-100" id="export-button">
                        <i class="fas fa-file-export"></i> Export Data
                    </button>
                </div> -->
                
            </div>
            <div class="table-responsive">
                <table id="attendance-table" class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="col-1">No</th> <!-- Add a class to control width -->
                            <th>Nama</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-table-body">
                        <!-- Data will be dynamically populated -->
                    </tbody>
                </table>
            </div>
            <div id="pagination-container" class="mt-3 d-flex justify-content-center">
                <!-- Pagination controls will be dynamically populated -->
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function() {
        const table = $('#attendance-table').DataTable({
            paging: false, // Disable default pagination
            searching: true,
            ordering: true,
            info: true
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#filter-period').datepicker({
            format: 'yyyy-mm',
            viewMode: 'months',
            minViewMode: 'months',
            autoclose: true
        });

        let currentPage = 1; // Track the current page
        const perPage = 10; // Number of records per page

        function fetchData(page = 1) {
            showLoading();
            $.ajax({
                url: `/api/v1/backup-records?nik=98291238&page=${page}&per_page=${perPage}`,
                method: 'GET',
                success: function(response) {
                    hideLoading();
                    if (response.data && response.data.data && Array.isArray(response.data.data)) {
                        populateTable(response.data.data);
                        updatePagination(response.data);
                    } else {
                        console.error('Unexpected data format:', response);
                        Swal.fire('Error', 'Unexpected data format received from the server', 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    Swal.fire('Error', 'Failed to fetch data from the server', 'error');
                }
            });
        }

        function populateTable(data) {
            table.clear();
            data.forEach((item, index) => {
                let recordsHtml = '';
                if (item.backup && Array.isArray(item.backup)) {
                    item.backup.forEach(record => {
                        recordsHtml += `
                            <div class="card mb-2">
                                <div class="card-body p-2">
                                    <span class="badge ${record.status === 'Hadir' ? 'bg-success' : 'bg-danger'}">${record.tanggal} (${record.status})</span>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <strong>Shift:</strong> ${record.shift} <br>
                                            <strong>Project Pengganti:</strong> ${record.project_pengganti || 'N/A'} <br>
                                            <strong>Project Backup:</strong> ${record.project_backup || 'N/A'} <br>
                                            <strong>Digantikan:</strong> ${record.digantikan}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    console.warn('No backup data for item:', item);
                }

                table.row.add([
                    `<div class="text-center">${index + 1}</div>`,
                    item.nama,
                    recordsHtml || '<div class="text-muted">No data available</div>'
                ]).draw(false);
            });
        }

        function updatePagination(data) {
            const paginationContainer = $('#pagination-container');
            paginationContainer.empty();

            if (data.last_page > 1) {
                const buttonGroup = $('<div class="btn-group" role="group"></div>');

                // Add "Previous" button
                buttonGroup.append(`
                    <button type="button" class="btn btn-outline-primary ${currentPage === 1 ? 'disabled' : ''}" data-page="${currentPage - 1}">
                        Previous
                    </button>
                `);

                // Calculate start and end page numbers
                const maxVisible = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let endPage = Math.min(data.last_page, startPage + maxVisible - 1);

                if (endPage - startPage + 1 < maxVisible) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }

                // Add page buttons
                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = i === currentPage ? 'active' : '';
                    buttonGroup.append(`
                        <button type="button" class="btn btn-outline-primary ${activeClass}" data-page="${i}">
                            ${i}
                        </button>
                    `);
                }

                // Add "Next" button
                buttonGroup.append(`
                    <button type="button" class="btn btn-outline-primary ${currentPage === data.last_page ? 'disabled' : ''}" data-page="${currentPage + 1}">
                        Next
                    </button>
                `);

                paginationContainer.append(buttonGroup);
            }
        }

        $(document).on('click', '.btn-outline-primary', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                fetchData(page);
            }
        });

        fetchData();

        function showLoading() {
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function hideLoading() {
            Swal.close();
        }

        $('#filter-button').on('click', function() {
            const selectedDate = $('#filter-date').val();
            if (selectedDate) {
                showLoading();
                setTimeout(() => {
                    Swal.fire('Filter Applied', `Data filtered for date: ${selectedDate}`, 'success');
                    hideLoading();
                }, 1000);
            } else {
                Swal.fire('Error', 'Please select a date to filter', 'error');
            }
        });

        $('#filter-period-button').on('click', function() {
            const selectedPeriod = $('#filter-period').val();
            if (selectedPeriod) {
                showLoading();
                $.ajax({
                    url: `/api/v1/backup-records?nik=98291238&period=${selectedPeriod}`,
                    method: 'GET',
                    success: function(response) {
                        hideLoading();
                        if (response.data && response.data.data && Array.isArray(response.data.data)) {
                            populateTable(response.data.data);
                            updatePagination(response.data);
                            Swal.fire('Filter Applied', `Data filtered for period: ${selectedPeriod}`, 'success');
                        } else {
                            Swal.fire('Error', 'Unexpected data format received from the server', 'error');
                        }
                    },
                    error: function() {
                        hideLoading();
                        Swal.fire('Error', 'Failed to fetch data from the server', 'error');
                    }
                });
            } else {
                Swal.fire('Error', 'Please select a period to filter', 'error');
            }
        });

        $('#export-button').on('click', function() {
            showLoading();
            setTimeout(() => {
                Swal.fire('Exporting Data', 'Data is being exported...', 'info');
                hideLoading();
            }, 1000);
        });

        $('#refresh-button').on('click', function() {
            location.reload();
        });
    });
</script>
@endpush
