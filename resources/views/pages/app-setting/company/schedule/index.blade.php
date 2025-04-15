@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="card custom-card2">
        <div class="card-body">

            {{-- Header + Button Create --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Jadwal Karyawan</h4>
                <a href="{{ route('company.schedules.create', $companyId) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
                </a>
            </div>

            {{-- Filter Form --}}
            <form method="GET" class="row g-3 align-items-end mb-4">
                <input type="hidden" name="company_id" value="{{ $companyId }}">

                <div class="col-md-3">
                    <label>Bulan</label>
                    <select name="month" class="form-select">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tahun</label>
                    <select name="year" class="form-select">
                        @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Lokasi Kerja</label>
                    <select name="location" class="form-select select2">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>

            {{-- Jadwal per Lokasi --}}
<div class="accordion" id="locationAccordion">
    @forelse($grouped as $locationId => $schedules)
        @php
            if(request('location') && $locationId != request('location')) continue;
            $employees = $schedules->groupBy('employee_id');
            $locationName = optional($schedules->first()->workLocation)->name ?? 'Tidak Diketahui';
        @endphp

        <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="headingLocation{{ $locationId }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseLocation{{ $locationId }}" aria-expanded="false"
                    aria-controls="collapseLocation{{ $locationId }}">
                    Lokasi: {{ $locationName }}
                </button>
            </h2>

            <div id="collapseLocation{{ $locationId }}" class="accordion-collapse collapse"
                aria-labelledby="headingLocation{{ $locationId }}" data-bs-parent="#locationAccordion">
                <div class="accordion-body">

                    {{-- Karyawan dalam lokasi --}}
                    <div class="accordion" id="employeeAccordion{{ $locationId }}">
                        @foreach($employees as $employeeSchedules)
                            @php $employee = $employeeSchedules->first()->employee; @endphp

                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="headingEmp{{ $employee->id }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseEmp{{ $employee->id }}"
                                        aria-expanded="false"
                                        aria-controls="collapseEmp{{ $employee->id }}">
                                        {{ $employee->nama ?? 'Tanpa Nama' }}
                                    </button>
                                </h2>

                                <div id="collapseEmp{{ $employee->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingEmp{{ $employee->id }}"
                                    data-bs-parent="#employeeAccordion{{ $locationId }}">
                                    <div class="accordion-body p-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm schedule-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Shift</th>
                                                        <th>Jam Masuk</th>
                                                        <th>Jam Keluar</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($employeeSchedules->sortBy('work_date') as $schedule)
                                                    <tr id="schedule-row-{{ $schedule->id }}">
                                                        <td>{{ $schedule->work_date }}</td>
                                                        <td>
                                                            <select class="form-select shift-select" data-schedule-id="{{ $schedule->id }}">
                                                                @foreach($shifts as $shift)
                                                                    <option value="{{ $shift->id }}" {{ $shift->id == $schedule->shift_id ? 'selected' : '' }}>
                                                                        {{ $shift->code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="start-time">{{ $schedule->shift->start_time ?? '-' }}</td>
                                                        <td class="end-time">{{ $schedule->shift->end_time ?? '-' }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary update-shift-btn" data-schedule-id="{{ $schedule->id }}">
                                                                Simpan
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Modal for Update Shift -->
                                        <div class="modal fade" id="updateShiftModal{{ $employee->id }}" tabindex="-1" aria-labelledby="updateShiftModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateShiftModalLabel">Update Shift - {{ $employee->nama }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="updateShiftForm{{ $employee->id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="shift_id" class="form-label">Pilih Shift Baru</label>
                                                                <select name="shift_id" id="shift_id{{ $employee->id }}" class="form-select" required>
                                                                    @foreach($shifts as $shift)
                                                                        <option value="{{ $shift->id }}">{{ $shift->code }} - {{ $shift->start_time }} to {{ $shift->end_time }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Update Shift</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    @empty
        <div class="text-muted">Tidak ada jadwal ditemukan untuk bulan ini.</div>
    @endforelse
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
<script>
    $(document).ready(function () {
        $('.schedule-table').DataTable({
            paging: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: true
        });

        $('.select2').select2({
            width: '100%',
            placeholder: 'Pilih Lokasi'
        });
    });
</script>
<script>
    document.querySelectorAll('.update-shift-btn').forEach(button => {
        button.addEventListener('click', function () {
            const scheduleId = this.dataset.scheduleId;
            const companyId = {{ $companyId }};
            const shiftSelect = document.querySelector(`.shift-select[data-schedule-id="${scheduleId}"]`);
            const shiftId = shiftSelect.value;

            fetch(`/company/${companyId}/schedules/${scheduleId}/update-shift`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ shift_id: shiftId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err });
                }
                return response.json();
            })
            .then(data => {
                // Update tampilan waktu shift
                const row = document.querySelector(`#schedule-row-${scheduleId}`);
                row.querySelector('.start-time').textContent = data.startTime;
                row.querySelector('.end-time').textContent = data.endTime;

                // Swal success
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Shift berhasil diperbarui!',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error:', error);

                let errorMsg = 'Gagal update shift';
                if (error?.errors?.shift_id) {
                    errorMsg = error.errors.shift_id[0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    html: `<pre>${error.message}</pre>`
                });
            });
        });
    });
</script>


@endpush
