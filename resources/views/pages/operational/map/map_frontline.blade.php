
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link href="{{ asset('assets/js/maps-routing/dist/leaflet-routing-machine.css') }}" rel="stylesheet" />
@endpush
@push('style')
<style>
        #map { height: 500px; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-body">
                <h4 class="card-title">Filter Frontline</h4>
                <form class="row g-3">
                    <div class="col-auto">
                        <label for="staticEmail2" class="visually-hidden">Project </label>
                        <select name="project_id" class="form-control select2">
                            <option value="">-- Select Project -- </option>
                            @if($project)
                                
                                @foreach($project as $pr)
                                    @php
                                        if($project_id==$pr->id){
                                            $selected="selected";
                                        }else{
                                            $selected="";
                                        }
                                    @endphp
                                    <option value="{{ $pr->id }}" {{$selected}}>{{ $pr->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3">Filter</button>
                    </div>
                </form>
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>
<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="{{ asset('assets/js/maps-routing/dist/leaflet-routing-machine.js') }}"></script>
  
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        var project_id = "<?php echo $project_id; ?>";

        let defaultCoordinates = [-6.200, 106.816];
        let titik = defaultCoordinates;

        if (project_id != 0) {
            titik = [<?php echo $lat; ?>, <?php echo $long; ?>];
        }

        var map = L.map('map').setView(titik, 12);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="truest.co.id">TRUEST</a> 2024'
        }).addTo(map);

        // Define custom icons
        var redIcon = L.icon({
            iconUrl: '<?php echo asset('kantor.png'); ?>',
            iconSize: [41, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        var blueIcon = L.icon({
            iconUrl: '<?php echo asset('security.png'); ?>',
            iconSize: [40, 40],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        var locationsJson = '<?php echo json_encode($records); ?>';
        var locations = JSON.parse(locationsJson);

        // Array to hold waypoints
        let waypoints = [];

        locations.forEach(function(location) {
            let icon = location.project == 1 ? redIcon : blueIcon;
            waypoints.push(L.latLng(location.lat, location.lng));
        });

        // Adding a routing control with waypoints
        L.Routing.control({
        waypoints: waypoints,
        routeWhileDragging: true,
        createMarker: function(i, waypoint, n) {
            let location = locations[i];
            let icon = location.project == 1 ? redIcon : blueIcon;
            return L.marker(waypoint.latLng, {
                icon: icon
            }).bindPopup(location.popup);
        }
    }).addTo(map);

    </script>
@endpush
