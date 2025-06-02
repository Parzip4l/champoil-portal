@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@php 
    $user = Auth::user();
    $dataLogin = json_decode($user->permission); 
    $employee = \App\Employee::where('nik', $user->name)->first(); 

    $project_id = '';
    if ($employee->jabatan == 'CLIENT') {
        $project_id = $user->project_id;
    }

    if ($employee->organisasi == 'FRONTLINE OFFICER') {
        $get_project = \App\ModelCG\Schedule::where('employee', $user->name)
            ->where('tanggal', date('Y-m-d'))
            ->first();
        $project_id = $get_project->project;
    }
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                <h5 class="mb-0">Data Tamu</h5>
                <div class="d-flex gap-2">
                @if ($employee->organisasi == 'Management Leaders' || $employee->organisasi == 'MANAGEMENT LEADERS')
                    <select id="projectFilter" class="form-select select2">
                        <option value="">All Projects</option>
                        @foreach(project_data('Kas') as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                @endif

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Foto Identitas</th>
                                <th>Nama Lengkap</th>
                                <th>Tujuan</th>
                                <th>Tanggal</th>
                                <th>Keperluan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be dynamically loaded via DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
@endpush

@push('custom-scripts')
  <script>
    $(document).ready(function() {
        const table = $('#dataTableExample').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/v1/tamu',
                data: function(d) {
                    d.project_id = $('#projectFilter').val() || '{{$project_id}}'; // Pass selected project ID as a parameter
                },
                dataSrc: function(json) {
                    return json.data; // Extract data array from the response
                }
            },
            columns: [
                { 
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Display row number
                    }
                },
                { 
                    data: 'foto_ktp',
                    render: function(data) {
                        if (data) {
                            return `<img src="https://data.cityservice.co.id/log/public/storage/${data}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                        }
                        return 'N/A';
                    }
                },
                { data: 'nama_tamu' },
                { data: 'tujuan' },
                { data: 'tanggal' },
                { data: 'keperluan' },
                { 
                    data: null,
                    render: function(data) {
                        return `<button class="btn btn-success btn-sm view-details" data-id="${data.id}">View Details</button>`;
                    }
                }
            ],
            paging: true,
            serverMethod: 'GET',
            pageLength: 10
        });

        $('.select2').select2();
        $('#searchBar').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#projectFilter').on('change', function() {
            table.ajax.reload(); // Reload the table when the filter changes
        });

        // Handle "View Details" button click
        $('#dataTableExample').on('click', '.view-details', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/api/v1/tamu/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#modalContent').html(`
                        <p><strong>Nama Lengkap:</strong> ${response.nama_tamu}</p>
                        <p><strong>Tujuan:</strong> ${response.tujuan}</p>
                        <p><strong>Tanggal:</strong> ${response.tanggal}</p>
                        <p><strong>Foto Identitas:</strong></p>
                        <img src="https://data.cityservice.co.id/log/public/storage/${response.foto_ktp}" alt="Foto" class="img-thumbnail" style="width: 150px; height: 150px;">
                    `);
                    $('#detailModal').modal('show');
                },
                error: function() {
                    alert('Failed to fetch details. Please try again.');
                }
            });
        });
    });
  </script>
@endpush