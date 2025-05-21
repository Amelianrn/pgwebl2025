@extends('layout.template')

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Edit polygon -->
    <div class="modal fade" id="EditpolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('polygons.update', $id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill polygon name">
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
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail" width="400">
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

        // Feature group untuk hasil digitasi
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Control edit
        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        // Saat fitur diedit
        map.on('draw:edited', function(e) {
            var layers = e.layers;

            layers.eachLayer(function(layer) {
                var drawnJSONObject = layer.toGeoJSON();
                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
                var properties = drawnJSONObject.properties || {};

                $('#name').val(properties.name || '');
                $('#description').val(properties.description || '');
                $('#geom_polygon').val(objectGeometry);
                $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" + (properties.image || ''));

                $('#EditpolygonModal').modal('show');
            });
        });

        // Tambahkan GeoJSON dari API
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);
                var properties = feature.properties || {};
                var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);

                layer.on('click', function(e) {
                    $('#name').val(properties.name || '');
                    $('#description').val(properties.description || '');
                    $('#geom_polygon').val(objectGeometry);
                    $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" + (properties.image || ''));
                    $('#EditpolygonModal').modal('show');
                });
            }
        });

        $.getJSON("{{ route('api.polygon', $id) }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
            map.fitBounds(polygon.getBounds(), {
                padding: [100, 100]
            });
        });
    </script>
@endsection
