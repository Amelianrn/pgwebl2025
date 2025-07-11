@extends('layout.template')

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Create Point-->
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_points" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polyline-->
    <div class="modal fade" id="CreatePolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polyline</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('polylines.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polyline" name="geom_polyline" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_poilylines" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polygon-->
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('polygons.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygons" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        var map = L.map('map').setView([-6.1229663, 106.6580200], 16);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            // console.log(objectGeometry);


            // memunculkan modal create polyline

            if (type === 'polyline') {
                console.log("Create " + type);

                $('#geom_polyline').val(objectGeometry);

                $('#CreatePolylineModal').modal('show');

                // memunculkan modal create polygon

            } else if (type === 'polygon' || type === 'rectangle') {
                console.log("Create " + type);

                $('#geom_polygon').val(objectGeometry);

                $('#CreatePolygonModal').modal('show');

                // memunculkan modal create marker

            } else if (type === 'marker') {
                console.log("Create " + type);

                $('#geom_point').val(objectGeometry);

                $('#CreatePointModal').modal('show');


            } else {
                console.log('undefined');
            }

            drawnItems.addLayer(layer);
        });

        // GeoJSON Points
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('points.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('points.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent =
                "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Dibuat: " + feature.properties.created_at + "<br>" +
                "<img src='{{ asset('storage/images') }}/" + feature.properties.image + "' width='200' alt=''>" + "<br>" +
                "<a href='/points/" + feature.properties.id + "/edit' class='btn btn-warning btn-sm'><i class='fa-solid fa-pen-to-square'></i></a> " +
                "<form method='POST' action='" + routedelete + "' class='d-inline'>" +
                    '@csrf' +
                    '@method("DELETE")' +
                    "<button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin akan dihapus\")'>" +
                        "<i class='fa-solid fa-trash-can'></i>" +
                    "</button>" +
                "</form>" +
                 "<br>" + "<p>Dibuat Oleh: " + feature.properties.user_created + "</p>";
                layer.on({
                    click: function(e) {
                        point.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        point.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        // GeoJSON Polylines
        var polyline = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polylines.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polylines.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Panjang: " + feature.properties.length_km + " km<br>" +
                "Dibuat: " + feature.properties.created_at + "<br>" +
                "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                "' width='200' alt=''>"  + "<br>" +
                "<a href='/polylines/" + feature.properties.id + "/edit' class='btn btn-warning btn-sm'><i class='fa-solid fa-pen-to-square'></i></a> " +
                "<form method='POST' action='" + routedelete +"' class='d-inline'>" +
                    '@csrf' +
                    '@method("DELETE")' +
                "<button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin akan dihapus\")'><i class='fa-solid fa-trash-can'></i></button>" +
                "</form>" +
                 "<br>" + "<p>Dibuat Oleh: " + feature.properties.user_created + "</p>";

                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.name);
            }
        });

        $.getJSON("{{ route('api.polylines') }}", function(data) {
            console.log("Data Polylines:", data); // Debugging
            polyline.addData(data);
            map.addLayer(polyline);
        });

        // GeoJSON Polygons
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polygons.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polygons.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Luas: " + feature.properties.area_ha + " ha<br>" +
                "Dibuat: " + feature.properties.created_at + "<br>" +
                "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                "' width='200' alt=''>"  + "<br>" +
                "<a href='/polygons/" + feature.properties.id + "/edit' class='btn btn-warning btn-sm'><i class='fa-solid fa-pen-to-square'></i></a> " +
                "<form method='POST' action='" + routedelete + "' class='d-inline'>" +
                    '@csrf' +
                    '@method("DELETE")' +
                "<button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin akan dihapus?\")'><i class='fa-solid fa-trash-can'></i></button>" +
                "</form>" +
                 "<br>" + "<p>Dibuat Oleh: " + feature.properties.user_created + "</p>";

                layer.bindPopup(popupContent);
                layer.bindTooltip(feature.properties.name);
            }
        });

        $.getJSON("{{ route('api.polygons') }}", function(data) {
            console.log("Data Polygons:", data); // Debugging
            polygon.addData(data);
            map.addLayer(polygon);
        });
    </script>
@endsection
