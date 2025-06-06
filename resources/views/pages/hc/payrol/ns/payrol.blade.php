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
    <div class="card custom-card2">
      <div class="card-body">
        <form action="{{route('payrollns.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
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
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Week</label>
                        <select name="week" id="week" class="form-control" required>
                            <!-- Opsi week akan diisi otomatis menggunakan JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="{{ date('Y') }}" readonly>
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
                                    <th>Jam Lembur</th>
                                    <th>Uang Makan</th>
                                    <th>Uang Kerajinan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <button type="button" id="addProduct" class="btn btn-primary mt-1 mb-3">Tambah Employee</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Run Payroll</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Upload Excel -->
<div class="modal fade" id="ImportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Slack Webhooks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Import Data</label>
                            <input type="file" class="form-control" name="file" required>    
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
        // Memuat daftar minggu saat halaman pertama kali dimuat
        loadWeeks();

        // Memuat daftar minggu saat bulan dipilih
        $('#month').change(function() {
            loadWeeks();
        });

        function loadWeeks() {
            const selectedMonth = $('#month').val();

            // Kirim permintaan AJAX untuk mendapatkan daftar minggu berdasarkan bulan yang dipilih
            $.ajax({
                url: '/get-weeks',
                method: 'GET',
                data: { month: selectedMonth },
                success: function(response) {
                    const weeks = response.weeks;
                    const weekSelect = $('#week');
                    weekSelect.empty(); // Hapus opsi sebelumnya

                    // Tambahkan opsi week yang baru
                    for (const week of weeks) {
                        const matches = week.match(/Week \d+ \((\d{4}-\d{2}-\d{2}) - (\d{4}-\d{2}-\d{2})\)/);
                        if (matches && matches.length === 3) {
                            const weekStart = matches[1];
                            const weekEnd = matches[2];
                            weekSelect.append(`<option value="${weekStart} - ${weekEnd}">${week}</option>`);
                        } else {
                            console.error('Invalid week format:', week);
                        }
                    }
                }
            });
        }
    });

    </script>

    <script>
        $(document).ready(function() {
            $('#week').change(function() {
                const selectedWeek = $('#week').val();
                if (!selectedWeek) return;

                const [startDate, endDate] = selectedWeek.split(' - ');

                // Cek apakah ini minggu terakhir dalam bulan
                const isLastWeek = checkIfLastWeek(endDate);

                $.ajax({
                    url: '/check-attendance',
                    method: 'GET',
                    data: { start_date: startDate, end_date: endDate },
                    success: function(response) {
                        const data = response.data;

                        $('#EmployeeTable tbody tr').each(function() {
                            const employeeCode = $(this).find('select[name="employee_code[]"]').val();
                            if (data[employeeCode]) {
                                // Set nilai uang makan
                                $(this).find('input[name="uang_makan[]"]').val(data[employeeCode].uang_makan);

                                // Set nilai jam lembur
                                $(this).find('input[name="lembur_jam[]"]').val(data[employeeCode].total_lembur);

                                // Set Uang Kerajinan hanya jika minggu terakhir
                                if (isLastWeek) {
                                    $(this).find('input[name="uang_kerajinan[]"]').val(data[employeeCode].uang_kerajinan);
                                } else {
                                    $(this).find('input[name="uang_kerajinan[]"]').val(0);
                                }
                            }
                        });
                    }
                });
            });

            function checkIfLastWeek(endDate) {
                const end = new Date(endDate);
                const lastDayOfMonth = new Date(end.getFullYear(), end.getMonth() + 1, 0); // Ambil hari terakhir bulan
                return end.getDate() >= (lastDayOfMonth.getDate() - 6); // Jika dalam 7 hari terakhir, dianggap minggu terakhir
            }
        });

    </script>


    <!-- Payroll -->
    <script>
        function addProductRow() {
            const employeeTableBody = document.querySelector('#EmployeeTable tbody');

            @foreach ($payrol as $data)
                @php
                    $employee = \App\Employee::where('nik', $data->employee_code)
                            ->where('unit_bisnis', 'CHAMPOIL')
                            ->where('resign_status', 0)
                            ->first();
                @endphp

                @if ($employee) 
                    var newRow = `
                        <tr>
                            <td>
                                <select class="form-control" name="employee_code[]">
                                    <option value="{{ $data->employee_code }}">{{ $employee->nama }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="lembur_jam[]" placeholder="1" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="uang_makan[]" placeholder="1" class="form-control">  
                            </td> 
                            <td class="purchase-uom-td">
                                <input type="number" name="uang_kerajinan[]" placeholder="1" class="form-control">  
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">Hapus</button>
                            </td>
                        </tr>
                    `;

                    employeeTableBody.insertAdjacentHTML('beforeend', newRow);
                @endif
            @endforeach
        }



        function removeProductRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function selectAll() {
            const allRows = document.querySelectorAll('#EmployeeTable tbody tr');
            allRows.forEach(row => {
                const inputFields = row.querySelectorAll('input');
                inputFields.forEach(input => {
                    input.value = 0;
                });
            });
        }

        document.getElementById('addProduct').addEventListener('click', addProductRow);
        document.getElementById('addProduct').addEventListener('click', selectAll);
    </script>
@endpush