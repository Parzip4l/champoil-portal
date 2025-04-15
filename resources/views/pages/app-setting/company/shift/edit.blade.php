@extends('layout.master')

@section('content')
<div class="card custom-card2">
    <div class="card-header">
        <h5>Edit Shift</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('company.shifts.update', [$companyId, $shift->id]) }}" method="POST" onsubmit="return validateShiftTime(event)">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Nama Shift</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $shift->name) }}" required>
            </div>

            <div class="form-group mb-2">
                <label>Kode Shift</label>
                <input type="text" name="code" class="form-control" placeholder="eg; ML or PG or MDL" value="{{ old('name', $shift->code) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="start_time">Jam Masuk</label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $shift->start_time) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="end_time">Jam Keluar</label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $shift->end_time) }}" required>
            </div>

            @if($useMultilocation)
            <div class="form-group mb-3">
                <label for="work_location_id">Lokasi Kerja</label>
                <select name="work_location_id" class="form-control">
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ $shift->work_location_id == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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