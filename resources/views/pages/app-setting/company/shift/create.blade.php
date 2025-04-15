@extends('layout.master')

@section('content')
<div class="card custom-card2">
    <div class="card-header"><h5>Tambah Shift</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('company.shifts.store', $companyId) }}" onsubmit="return validateShiftTime(event)">
            @csrf
            <div class="form-group mb-2">
                <label>Nama Shift</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-2">
                <label>Kode Shift</label>
                <input type="text" name="code" class="form-control" placeholder="eg; ML or PG or MDL" required>
            </div>

            <div class="form-group mb-2">
                <label>Jam Masuk</label>
                <input type="time" name="start_time" id="start_time" class="form-control" required>
            </div>

            <div class="form-group mb-2">
                <label>Jam Keluar</label>
                <input type="time" name="end_time" id="end_time" class="form-control" required>
            </div>

            @if($useMultilocation)
                <div class="form-group mb-2">
                    <label>Lokasi Kerja</label>
                    <select name="location_id" class="form-control">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('company.shifts.index', $companyId) }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

@push('custom-scripts')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function validateShiftTime(event) {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        if (!startTime || !endTime) return true;

        const [startHour, startMinute] = startTime.split(':').map(Number);
        const [endHour, endMinute] = endTime.split(':').map(Number);

        const start = startHour * 60 + startMinute;
        const end = endHour * 60 + endMinute;

        // Valid: end > start (hari yang sama) atau shift malam (end < start dan end < jam 12 siang)
        const isValid = (end > start) || (start > end && end < 720);

        if (!isValid) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Waktu tidak valid',
                text: 'Jam keluar harus lebih besar dari jam masuk atau merupakan shift malam.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        return true;
    }
</script>
@endpush
