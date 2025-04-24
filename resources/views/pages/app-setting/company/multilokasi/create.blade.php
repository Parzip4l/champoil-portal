@extends('layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Tambah Lokasi Kerja</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('company.work-locations.store', $companyId) }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Nama Lokasi</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $location->name ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $location->latitude ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $location->longitude ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Preview Lokasi Kerja</label>
                        <div id="map" style="height: 450px;"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="radius">Radius (KM)</label>
                        <input type="number" name="radius" class="form-control" value="{{ old('radius', $location->radius ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <div id="salary-by-position">
                            <div class="row mb-2 position-salary-group">
                                <div class="col-md-6">
                                    <label for="monthly_salary">Jabatan</label>
                                    <select name="position_salaries[0][position_id]" class="form-control select2" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control format-rupiah" placeholder="Gaji Bulanan" />
                                    <input type="hidden" name="position_salaries[0][monthly_salary]" class="rupiah-value" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control format-rupiah" placeholder="Rate Harian" />
                                    <input type="hidden" name="position_salaries[0][daily_rate]" class="rupiah-value" />
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addPositionSalary">+ Tambah Jabatan</button>
                    </div>



                    <button class="btn btn-success">Simpan Lokasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('plugin-scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@endpush
@push('custom-scripts')
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
<script>
    const positions = @json($positions); // kirim daftar jabatan ke JS

    function generatePositionOptions() {
        return positions.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
    }
    
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function unformatRupiah(angka) {
        return angka.replace(/\./g, '');
    }

    function handleRupiahInput(input) {
        input.addEventListener('input', function () {
            const raw = unformatRupiah(input.value);
            if (!isNaN(raw)) {
                input.value = formatRupiah(raw);
                const hiddenInput = input.parentElement.querySelector('.rupiah-value');
                if (hiddenInput) {
                    hiddenInput.value = raw;
                }
            }
        });
    }

    function initRupiahFormatting() {
        document.querySelectorAll('.format-rupiah').forEach(function (input) {
            handleRupiahInput(input);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initRupiahFormatting();

        document.getElementById('addPositionSalary').addEventListener('click', function () {
            const container = document.getElementById('salary-by-position');
            const count = container.querySelectorAll('.position-salary-group').length;

            const html = `
                <div class="row mb-2 position-salary-group align-items-end">
                    <div class="col-md-6">
                        <select name="position_salaries[${count}][position_id]" class="form-control" required>
                            <option value="">-- Pilih Jabatan --</option>
                            ${generatePositionOptions()}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control format-rupiah" placeholder="Gaji Bulanan" />
                        <input type="hidden" name="position_salaries[${count}][monthly_salary]" class="rupiah-value" />
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control format-rupiah" placeholder="Rate Harian" />
                        <input type="hidden" name="position_salaries[${count}][daily_rate]" class="rupiah-value" />
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-danger btn-sm remove-position">
                            &times;
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            initRupiahFormatting();
        });
    });

    $(document).on('click', '.remove-position', function () {
        $(this).closest('.position-salary-group').remove();
    });
</script>
@endpush