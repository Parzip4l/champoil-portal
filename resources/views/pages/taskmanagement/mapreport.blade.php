
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
@endpush
@push('style')
<style>
        #map {
            height: 650px;
        }
        .leaflet-div-icon {
            background: none;
            border: none;
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
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
  
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    // Initialize Feather icons
    feather.replace();

    // Initialize the map with a center point and zoom level
    var map = L.map('map').setView([-6.200, 106.816], 5);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Get locations data from PHP as a JSON string and parse it
    var locationsJson = '<?php echo json_encode($records) ?>';
    var locations = JSON.parse(locationsJson);

    // Define custom icons using images
    var startIcon = L.divIcon({
        className: 'leaflet-div-icon-start',
        html: '<img src="<?php echo asset('iconmap.png'); ?>" class="start-icon" />', // Image for start
        iconSize: [15, 15]
    });

    var endIcon = L.divIcon({
        className: 'leaflet-div-icon-end',
        html: '<img src="<?php echo asset('iconmap.png'); ?>" class="end-icon" />', // Image for end
        iconSize: [15, 15]
    });

    // Store each route's control in an array to ensure multiple routes are handled
    var routeControls = [];

    // Loop through each location and add the route and markers
    locations.forEach(function(location) {
        // Check if start and end points are the same
        if (location.lat_start === location.lat_end && location.lng_start === location.lng_end) {
            // Add a small offset to the end coordinates
            location.lat_end = parseFloat(location.lat_end) + 0.0001;
            location.lng_end = parseFloat(location.lng_end) + 0.0001;
        }

        // Add start marker with popup showing start time
        var startMarker = L.marker([location.lat_start, location.lng_start], { icon: startIcon })
            .addTo(map)
            .bindPopup(location.popup_start); // Popup for start point

        // Add end marker with popup showing end time
        var endMarker = L.marker([location.lat_end, location.lng_end], { icon: endIcon })
            .addTo(map)
            .bindPopup(location.popup_end); // Popup for end point

        // Create a new route control for each subtask
        var control = L.Routing.control({
            waypoints: [
                L.latLng(location.lat_start, location.lng_start),
                L.latLng(location.lat_end, location.lng_end)
            ],
            createMarker: function() { return null; }, // Remove default markers
            lineOptions: {
                styles: [{ color: 'blue', weight: 5 }]
            },
            show: false, // Hide the control UI
            addWaypoints: false
        }).addTo(map);

        // Store the route control instance
        routeControls.push(control);

        // Listen for the routesfound event to display the distance
        control.on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;

            // Calculate the distance in kilometers and format to 2 decimal places
            var distance = (summary.totalDistance / 1000).toFixed(2); 

            // Update end marker popup with distance information
            endMarker.bindPopup(location.popup_end + '<br>Jarak Tempuh: ' + distance + ' Km' + 
            '<br>Total Waktu: ' + location.time_diff).openPopup();
        });
    });
</script>

@endpush
