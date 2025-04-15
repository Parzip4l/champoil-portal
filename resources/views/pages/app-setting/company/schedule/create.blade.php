@extends('layout.master')

@section('content')
<div class="card custom-card2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Tambah Jadwal Karyawan</h5>
        <a href="{{ route('company.schedules.index', $companyId) }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>

    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('company.schedules.store', $companyId) }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-select" required>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="year" class="form-label">Tahun</label>
                    <input type="number" name="year" id="year" class="form-control" value="{{ $year }}" required />
                </div>
                <div class="col-md-4">
                    <label for="location_id" class="form-label">Lokasi Kerja</label>
                    <select name="location_id" id="location_id" class="form-select select2">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="employee_id" class="form-label">Pilih Karyawan</label>
                    <select id="employee_id" name="employee_id" class="form-select select2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->nik }})</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label">Terapkan Shift ke Semua Tanggal</label>
                <div class="d-flex gap-2">
                    <select id="global_shift" class="form-select" style="max-width: 250px;">
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary" onclick="applyShiftToAll()">Terapkan</button>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%">Tanggal</th>
                            <th>Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $startDate = $cutoffStart->copy(); @endphp
                        @while($startDate <= $cutoffEnd)
                            <tr>
                                <td>{{ $startDate->translatedFormat('d M Y (l)') }}</td>
                                <td>
                                    <select name="schedules[{{ $startDate->format('Y-m-d') }}][shift_id]" class="form-select shift-select" required>
                                        <option value="">-- Pilih Shift --</option>
                                        @foreach($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @php $startDate->addDay(); @endphp
                        @endwhile
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menerapkan shift ke semua tanggal
    function applyShiftToAll() {
        let shiftId = document.getElementById('global_shift').value;
        if (!shiftId) {
            // Gunakan SweetAlert untuk menampilkan pesan peringatan
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Silakan pilih shift terlebih dahulu.'
            });
            return;
        }

        // Gunakan SweetAlert untuk konfirmasi sebelum menerapkan shift
        Swal.fire({
            title: 'Terapkan shift ini ke semua tanggal?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.shift-select').forEach(select => {
                    select.value = shiftId;
                });
                Swal.fire('Shift diterapkan!', '', 'success');
            }
        });
    }

    // Fungsi untuk memuat ulang tabel berdasarkan bulan dan tahun yang dipilih
    function updateDates() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        const url = new URL(window.location.href);
        
        // Update query string dengan bulan dan tahun yang dipilih
        url.searchParams.set('month', month);
        url.searchParams.set('year', year);
        
        // Mengarahkan ulang ke URL dengan bulan dan tahun yang baru
        window.location.href = url.toString();
    }

    // Event listener untuk perubahan bulan dan tahun
    document.getElementById('month').addEventListener('change', updateDates);
    document.getElementById('year').addEventListener('change', updateDates);

    // Inisialisasi Select2 untuk dropdown
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

@endpush
