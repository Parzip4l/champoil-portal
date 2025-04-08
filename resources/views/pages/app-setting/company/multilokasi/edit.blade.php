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
                <h5>Edit Lokasi Kerja</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('company.work-locations.update', [$companyId, $location->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name">Nama Lokasi</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $location->name) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $location->latitude) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $location->longitude) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Preview Lokasi Kerja</label>
                        <div id="map" style="height: 300px;"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="radius">Radius (meter)</label>
                        <input type="number" name="radius" class="form-control" value="{{ old('radius', $location->radius) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="monthly_salary">Gaji Bulanan (Opsional)</label>
                        <input type="number" name="monthly_salary" class="form-control" value="{{ old('monthly_salary', $location->monthly_salary) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="daily_rate">Rate Harian (Opsional)</label>
                        <input type="number" name="daily_rate" class="form-control" value="{{ old('daily_rate', $location->daily_rate) }}">
                    </div>

                    <button class="btn btn-primary">Perbarui Data</button>
                    <a href="{{ route('company.work-locations.index', $companyId) }}" class="btn btn-secondary">Batal</a>
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
@endpush

