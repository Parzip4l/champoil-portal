
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush
@push('style')
<style>
        #map {
            height: 500px;
        }
        .leaflet-div-icon {
            background: none;
            border: none;
        }
        .leaflet-div-icon-start i {
            font-size: 24px;
        }
        .leaflet-marker-icon.leaflet-div-icon-start.leaflet-zoom-animated.leaflet-interactive {
            color : #27ae60;
        }

        .leaflet-marker-icon.leaflet-div-icon-end.leaflet-zoom-animated.leaflet-interactive {
            color : #c0392b;
        }
    </style>
@endpush

@section('content')
<div id="map"></div>
<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
        // Initialize Feather icons
        feather.replace();

        var map = L.map('map').setView([-6.200, 106.816], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var locationsJson = '<?php echo json_encode($records) ?>';
        var locations = JSON.parse(locationsJson);

        // Define custom icons using Feather icons
        var startIcon = L.divIcon({
            className: 'leaflet-div-icon-start',
            html: '<i data-feather="map-pin" class="start"></i>', // Feather icon for start
            iconSize: [24, 24]
        });

        var endIcon = L.divIcon({
            className: 'leaflet-div-icon-end',
            html: '<i data-feather="flag" class="end"></i>', // Feather icon for end
            iconSize: [24, 24]
        });

        locations.forEach(function(location) {
            // Add start marker with popup showing start time
            L.marker([location.lat_start, location.lng_start], { icon: startIcon })
                .addTo(map)
                .bindPopup(location.popup_start); // Popup for start point

            // Add end marker with popup showing end time
            L.marker([location.lat_end, location.lng_end], { icon: endIcon })
                .addTo(map)
                .bindPopup(location.popup_end); // Popup for end point

            // Add a line between start and end points
            L.polyline([
                [location.lat_start, location.lng_start],
                [location.lat_end, location.lng_end]
            ], { color: 'blue' }).addTo(map);
        });
    </script>
@endpush
