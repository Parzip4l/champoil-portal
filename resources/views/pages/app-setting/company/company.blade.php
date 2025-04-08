@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush

@section('content')
<!-- Top Bar -->
<div class="row mb-3">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('company')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<!-- End -->
<div class="row desktop">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-6 ps-0">
                        <img src="{{ asset('images/company_logo/' . $company->logo) }}" alt="" style="width:150px; object-fit:cover;">                 
                        <h5 class="mt-5 mb-2 text-muted">{{$company->company_name}}</h5>
                        <p>{{$company->company_address}}</p>
                    </div>
                    <div class="col-lg-4 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2"></h4>
                        <h6 class="text-end text-danger mb-5 pb-4">*Company Data</h6>
                    </div>
                </div>
                <hr>
                <div class="data-company p-3">
                    <div class="row">
                        <div class="col-lg-12 ps-0">
                            <form method="POST" action="{{ route('company-settings.update', $company->id) }}" id="form-setting-global">
                                @csrf
                                @method('PUT')

                                <div class="accordion" id="settingAccordion">
                                    <!-- General Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="generalHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#generalSection" aria-expanded="true" aria-controls="generalSection">
                                                Features
                                            </button>
                                        </h2>
                                        <div id="generalSection" class="accordion-collapse collapse" aria-labelledby="generalHeading" data-bs-parent="#settingAccordion">
                                            <div class="accordion-body row g-3">

                                                <div class="col-md-12">
                                                    <a href="{{route('companymenu.set', $company->id)}}" class="btn btn-sm btn-primary w-100" target="_blank">Lihat Setting Feature</a>
                                                </div>

                                                
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Absensi Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="absensiHeading">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#absensiCollapse" aria-expanded="false" aria-controls="absensiCollapse">
                                                Absensi
                                            </button>
                                        </h2>
                                        <div id="absensiCollapse" class="accordion-collapse collapse show" aria-labelledby="absensiHeading" data-bs-parent="#settingAccordion">
                                            <div class="accordion-body row g-3">
                                                <!-- Fitur -->
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" id="toggleSchedule" type="checkbox" name="use_schedule" value="1"
                                                            {{ isset($settings['use_schedule']) && $settings['use_schedule'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="toggleSchedule">Gunakan Schedule</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" id="toggleShift" type="checkbox" name="use_shift" value="1"
                                                            {{ isset($settings['use_shift']) && $settings['use_shift'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="toggleShift">Gunakan Shift</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="use_multilocation" value="0">
                                                        <input class="form-check-input" type="checkbox" name="use_multilocation" id="toggleMultiLocation" value="1"
                                                            {{ isset($settings['use_multilocation']) && $settings['use_multilocation'] ? 'checked' : '' }}>
                                                        <label class="form-check-label">Gunakan Multi Lokasi</label>
                                                    </div>
                                                    <small class="text-muted">Aktifkan untuk mengatur lokasi kerja berdasarkan cabang/proyek.</small>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="use_radius" value="1"
                                                            {{ isset($settings['use_radius']) && $settings['use_radius'] ? 'checked' : '' }}>
                                                        <label class="form-check-label">Gunakan Radius Presensi</label>
                                                    </div>
                                                </div>

                                                <!-- Titik Koordinat -->
                                                @php
                                                    $coordinates = is_string($settings['gps_coordinates'] ?? null)
                                                        ? json_decode($settings['gps_coordinates'], true)
                                                        : ($settings['gps_coordinates'] ?? []);
                                                @endphp

                                                <div class="row g-3 mt-2" id="radius-settings" style="display: none;">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $coordinates['latitude'] ?? '') }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $coordinates['longitude'] ?? '') }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Radius (KM)</label>
                                                        <input type="number" name="radius" class="form-control" value="{{ old('radius', $settings['radius_value'] ?? '') }}">
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Preview Lokasi Kerja</label>
                                                            <div id="map" style="height: 350px;"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Cuti -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Cuti Tahunan (hari)</label>
                                                    <input type="number" name="annual_leave_quota" class="form-control"
                                                        value="{{ $settings['annual_leave_quota'] ?? 12 }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Maksimal Akumulasi Cuti (tahun)</label>
                                                    <input type="number" name="max_leave_accumulation" class="form-control"
                                                        value="{{ $settings['max_leave_accumulation'] ?? 1 }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check form-switch mt-4">
                                                        <input id="allowLeaveConversion" class="form-check-input" type="checkbox" name="allow_leave_conversion" value="1"
                                                            {{ isset($settings['allow_leave_conversion']) && $settings['allow_leave_conversion'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="allowLeaveConversion">Cuti Tidak Terpakai Bisa Diuangkan</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3" id="leaveConversionAmountGroup" style="display: none;">
                                                    <label class="form-label">Nominal per Hari Cuti (Rp)</label>
                                                    <input type="number" name="leave_conversion_amount" class="form-control"
                                                        value="{{ $settings['leave_conversion_amount'] ?? '' }}">
                                                </div>



                                                <!-- Jam Kerja -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Jam Masuk Default</label>
                                                    <input type="time" name="default_in_time" class="form-control" value="{{ $settings['default_in_time'] ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Jam Pulang Default</label>
                                                    <input type="time" name="default_out_time" class="form-control" value="{{ $settings['default_out_time'] ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Toleransi Telat (menit)</label>
                                                    <input type="number" name="grace_period" class="form-control" value="{{ $settings['grace_period'] ?? 0 }}">
                                                </div>

                                                <!-- Hari Kerja -->
                                                <div class="col-md-12">
                                                    <label class="form-label">Hari Kerja Aktif</label>
                                                    @php
                                                        $workdays_raw = $settings['workdays'] ?? '[]';
                                                        $workdays = collect(is_array($workdays_raw) ? $workdays_raw : json_decode($workdays_raw, true) ?? explode(',', $workdays_raw))
                                                            ->map(fn($day) => trim($day))
                                                            ->all();

                                                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                                    @endphp
                                                    <div class="row">
                                                        @foreach($days as $day)
                                                            <div class="col-md-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="workdays[]" value="{{ $day }}"
                                                                        {{ in_array($day, $workdays) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">{{ $day }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Potongan Telat -->
                                                <div class="col-md-12">
                                                    <hr>
                                                    <strong class="form-label">Potongan Telat</strong>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="late_cut_enabled" id="toggleLateCut" value="1"
                                                            {{ isset($settings['late_cut_enabled']) && $settings['late_cut_enabled'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="toggleLateCut">Aktifkan</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Mulai dari menit ke-</label>
                                                    <input type="number" name="late_minutes_threshold" class="form-control"
                                                        value="{{ $settings['late_minutes_threshold'] ?? 0 }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Jumlah Potongan (per menit)</label>
                                                    <input type="number" name="late_cut_amount" class="form-control"
                                                        value="{{ $settings['late_cut_amount'] ?? 0 }}">
                                                </div>

                                                <!-- Potongan Pulang Cepat -->
                                                <div class="col-md-12">
                                                    <hr>
                                                    <strong class="form-label">Potongan Pulang Cepat</strong>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="early_leave_enabled" value="1"
                                                            {{ isset($settings['early_leave_enabled']) && $settings['early_leave_enabled'] ? 'checked' : '' }}>
                                                        <label class="form-check-label">Aktifkan</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Jumlah Potongan (per menit)</label>
                                                    <input type="number" name="early_leave_cut_amount" class="form-control"
                                                        value="{{ $settings['early_leave_cut_amount'] ?? 0 }}">
                                                </div>

                                                <!-- Schedule Type -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Tipe Schedule</label>
                                                    <select name="schedule_type" class="form-select">
                                                        <option value="monthly" {{ ($settings['schedule_type'] ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                                        <option value="weekly" {{ ($settings['schedule_type'] ?? '') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Payroll Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="payrollHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payrollCollapse" aria-expanded="false" aria-controls="payrollCollapse">
                                                Payroll
                                            </button>
                                        </h2>
                                        <div id="payrollCollapse" class="accordion-collapse collapse" aria-labelledby="payrollHeading" data-bs-parent="#settingAccordion">
                                            <div class="accordion-body row g-3">

                                                <div class="col-md-6">
                                                    <label class="form-label">Jenis Payroll</label>
                                                    <select name="payroll_type" class="form-select">
                                                        <option value="monthly" {{ ($settings['payroll_type'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                        <option value="weekly" {{ ($settings['payroll_type'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Struktur Payroll</label>
                                                    <select name="payroll_structure" class="form-select">
                                                        <option value="sama" {{ ($settings['payroll_structure'] ?? '') == 'sama' ? 'selected' : '' }}>Sama</option>
                                                        <option value="berbeda" {{ ($settings['payroll_structure'] ?? '') == 'berbeda' ? 'selected' : '' }}>Berbeda per organisasi</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Cutoff Start</label>
                                                    <input type="number" name="cutoff_start" class="form-control"
                                                        value="{{ $settings['cutoff_start'] ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Cutoff End</label>
                                                    <input type="number" name="cutoff_end" class="form-control"
                                                        value="{{ $settings['cutoff_end'] ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input id="pph21-switch" class="form-check-input" type="checkbox" name="use_pph21" value="1"
                                                            {{ isset($settings['use_pph21']) && $settings['use_pph21'] ? 'checked' : '' }}>
                                                        <label class="form-check-label">Gunakan PPh21</label>
                                                    </div>
                                                </div>

                                                <div id="pph21-options" style="display: none;">
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Metode PPh21</label>
                                                        <select name="pph21_method" class="form-select">
                                                            <option value="gross" {{ ($settings['pph21_method'] ?? '') == 'gross' ? 'selected' : '' }}>Gross</option>
                                                            <option value="gross_up" {{ ($settings['pph21_method'] ?? '') == 'gross_up' ? 'selected' : '' }}>Gross Up</option>
                                                            <option value="nett" {{ ($settings['pph21_method'] ?? '') == 'nett' ? 'selected' : '' }}>Nett</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="npwp_required" value="1"
                                                                {{ isset($settings['npwp_required']) && $settings['npwp_required'] ? 'checked' : '' }}>
                                                            <label class="form-check-label">NPWP Wajib</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                </div>
                            </form>


                        </div>
                    </div>
                    @php
                        $latestUpdate = \App\Company\CompanySetting::where('company_id', $company->id)
                            ->orderBy('updated_at', 'desc')
                            ->with('updatedBy')
                            ->first();

                    @endphp

                    @if($latestUpdate)
                        <div class="alert alert-danger mt-3">
                            <small>
                                Terakhir diubah oleh:
                                <strong >{{ $latestUpdate->updatedBy->nama ?? 'User tidak ditemukan' }}</strong>
                                pada
                                <strong>{{ $latestUpdate->updated_at->format('d M Y H:i') }}</strong>
                            </small>
                        </div>
                    @endif

                    
                </div>
                <!-- Company Statistic -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <style>
        @media(min-width: 678px){
            .mobile {
                display : none;
            }

            .desktop {
                display : block;
            }
        }

        @media(max-width: 678px){
            .mobile {
                display : block;
            }

            .desktop {
                display : none;
            }
        }

        
    </style>
    <script>
        function togglePPH21Options() {
            if ($('#pph21-switch').is(':checked')) {
                $('#pph21-options').slideDown();
            } else {
                $('#pph21-options').slideUp();
            }
        }

        $(document).ready(function() {
            togglePPH21Options(); // Initial state on load
            $('#pph21-switch').on('change', togglePPH21Options);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('toggleLateCut');
            const fields = document.querySelectorAll('.late-cut-settings');
            const toggleSchedule = document.getElementById('toggleSchedule');
            const fieldsSchedule = document.querySelectorAll('.schedule-setting');

            function toggleLateFields() {
                fields.forEach(field => {
                    field.style.display = toggle.checked ? 'block' : 'none';
                });
            }

            function toggleSchedulefields() {
                fieldsSchedule.forEach(field => {
                    field.style.display = toggleSchedule.checked ? 'block' : 'none';
                });
            }

            toggle.addEventListener('change', toggleLateFields);
            toggleSchedule.addEventListener('change', toggleSchedulefields);

            // Jalankan saat load awal
            toggleLateFields();
            toggleSchedulefields();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const radiusToggle = document.querySelector('input[name="use_radius"]');
            const radiusSettings = document.getElementById("radius-settings");

            function toggleRadiusFields() {
                if (radiusToggle.checked) {
                    radiusSettings.style.display = 'flex';
                } else {
                    radiusSettings.style.display = 'none';
                }
            }

            // Initial check on page load
            toggleRadiusFields();

            // Toggle on change
            radiusToggle.addEventListener('change', toggleRadiusFields);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('allowLeaveConversion');
            const amountGroup = document.getElementById('leaveConversionAmountGroup');

            function toggleAmountField() {
                amountGroup.style.display = checkbox.checked ? 'block' : 'none';
            }

            checkbox.addEventListener('change', toggleAmountField);
            toggleAmountField();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleShift = document.getElementById('toggleShift');
            const toggleSchedule = document.getElementById('toggleSchedule');

            toggleShift.addEventListener('change', function () {
                if (this.checked && !toggleSchedule.checked) {
                    this.checked = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Bisa Aktifkan Shift',
                        text: 'Aktifkan Schedule terlebih dahulu sebelum mengaktifkan Shift .',
                        confirmButtonText: 'OK',
                        timer: 3000
                    });
                }
            });

            toggleSchedule.addEventListener('change', function () {
                if (!this.checked && toggleShift.checked) {
                    toggleShift.checked = false;
                    Swal.fire({
                        icon: 'info',
                        title: 'Schedule Dinonaktifkan',
                        text: 'Schedule otomatis dimatikan karena Shift dinonaktifkan.',
                        confirmButtonText: 'OK',
                        timer: 3000
                    });
                }
            });
        });
    </script>
<script>
document.getElementById('toggleMultiLocation').addEventListener('change', function () {
    if (this.checked) {
        // Tampilkan konfirmasi SweetAlert
        Swal.fire({
            title: 'Multi Lokasi Diaktifkan',
            text: "Ingin langsung setup lokasi kerja sekarang?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setup Sekarang',
            cancelButtonText: 'Nanti Saja'
        }).then((result) => {
            // Tambahkan input hidden agar controller tahu user mau redirect atau tidak
            let form = document.getElementById('form-setting-global');
            let redirectInput = document.createElement('input');
            redirectInput.setAttribute('type', 'hidden');
            redirectInput.setAttribute('name', 'redirect_to_location');
            redirectInput.setAttribute('value', result.isConfirmed ? '1' : '0');
            form.appendChild(redirectInput);

            // Submit form
            form.submit();
        });
    }
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latInput = document.querySelector('input[name="latitude"]');
        const lngInput = document.querySelector('input[name="longitude"]');

        let lat = parseFloat(latInput.value) || -6.2;
        let lng = parseFloat(lngInput.value) || 106.8;
        const radius = parseFloat(document.querySelector('input[name="radius"]').value) || 100;

        const map = L.map('map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        const circle = L.circle([lat, lng], {
            color: 'blue',
            fillColor: '#3c8dbc',
            fillOpacity: 0.2,
            radius: radius
        }).addTo(map);

        function updateInputs(e) {
            const newLatLng = e.latlng || marker.getLatLng();
            latInput.value = newLatLng.lat.toFixed(6);
            lngInput.value = newLatLng.lng.toFixed(6);
            marker.setLatLng(newLatLng);
            circle.setLatLng(newLatLng);
        }

        // drag marker
        marker.on('dragend', function (e) {
            updateInputs(e);
        });

        // klik di peta
        map.on('click', function (e) {
            updateInputs(e);
        });

        // 🔍 Tambahkan kontrol pencarian alamat
        L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
            const center = e.geocode.center;
            map.setView(center, 16);
            updateInputs({ latlng: center });
        })
        .addTo(map);
    });
</script>
    
@endpush