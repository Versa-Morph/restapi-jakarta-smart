@extends('layouts.app')

@push('css')
<!-- Load Leaflet from CDN -->
<link rel="stylesheet" href="{{ asset('assets/css/leaflet/leaflet.css') }}" />

<!-- Load Esri Leaflet from CDN -->
<link href='{{ asset('assets/css/leaflet/leaflet-fullscreen.css') }}' rel='stylesheet' />

{{-- Leaflet legend --}}
<link rel="stylesheet" href="{{ asset('assets/css/leaflet-control/leaflet.legend.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/css/maps.css') }}">

<style>
.page-content {
    padding: calc(45px + 24px) calc(1px / 2) 45px calc(1px / 2) !important;
}

.container, .container-fluid, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    padding: 0 !important;
}
.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

@media (prefers-reduced-motion: reduce) {
    .badge {
        transition: none;
    }
}
.badge-pill {
    padding-right: 0.6em;
    padding-left: 0.6em;
    border-radius: 10rem;
}

.badge-success {
    color: #fff;
    background-color: #28a745;
}

.badge-danger {
    color: #fff;
    background-color: #dc3545;
}

.leaflet-popup-content-wrapper {
    padding: 0;
}

.leaflet-popup-content {
    margin: 0;
}

.popup-card {
    background-color: white;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
}

.popup-card-header {
    font-size: 1.2em;
    font-weight: bold;
}

.popup-card-body {
    margin-top: 10px;
}

.popup-card-body p {
    margin: 5px 0;
}

.popup-card-footer {
    margin-top: 10px;
    text-align: right;
}

</style>
@endpush
@section('breadcumbs')
@endsection

@section('content')
    <!-- Main Content Start -->
    <section class="main--content">
        <div class="row gutter-20">
            <div class="col-md-3 mb-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #EFEAFA;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Total Incident Today</div>
                    <div class="card-number mb-2">{{ $all_incident }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #CBF2FF;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Emergency Queue</div>
                    <div class="card-number mb-2">{{ $queue_incident }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #E5EDE6;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Emergency Resolved</div>
                    <div class="card-number mb-2">{{ $completed_incident }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #FFEDE2;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Currently Being Handled</div>
                    <div class="card-number mb-2">{{ $processed_incident }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="main--content mb-5 pt-3">
        <div class="row gutter-20">
            <div class="col-sm-12">
                <div class="card" style="border-radius: 20px;">
                    <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                        <h3 class="m-0 text-center">MAPS INDONESIA</h3>
                    </div>
                    <div class="card-body">
                        <div id="map" style="z-index: 1 !important">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <!-- Load Leaflet from CDN -->
<script src="{{ asset('assets/js/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('assets/js/leaflet/leaflet-control-locate.js') }}"></script>

<!-- Load Esri Leaflet from CDN -->
<script src="{{ asset('assets/js/leaflet/esri-leaflet.js') }}"></script>
<script src='{{ asset('assets/js/leaflet/leaflet-fullscreen.js') }}'></script>

{{-- Leaflet Legend --}}
<script type="text/javascript" src="{{ asset('assets/js/leaflet-control/leaflet.legend.js') }}"></script>

<script>
    const officon = L.icon({
        iconUrl: `img/location-off.png`,

        iconSize:     [40, 40], // size of the icon
        iconAnchor:   [19, 39], // point of the icon which will correspond to marker's location
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    const onicon = L.icon({
        iconUrl: `img/location-on.png`,

        iconSize:     [40, 40], // size of the icon
        iconAnchor:   [19, 39], // point of the icon which will correspond to marker's location
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    L.Control.MyControl = L.Control.extend({
        onAdd: function(map) {
            var el = L.DomUtil.create('div', 'leaflet-bar my-control');
            el.innerHTML = '<b>Jakarta</b>'; // Customize this line
            return el;
        },

        onRemove: function(map) {
            // Nothing to do here
        }
    });

    L.control.myControl = function(opts) {
        return new L.Control.MyControl(opts);
    }

    var markers = []
    var parentLocation = []
    var subLocation = []
    var parentStatus = []
    var popups = []
    var map;

    $(document).ready(function() {
        const incidents = @json($incidents);

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                localCoord = position.coords;
                lat = localCoord.latitude;
                long = localCoord.longitude;

                // Map Satellite
                const sattelite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                });

                // Map Streets
                const streets = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxNativeZoom:19,
                    maxZoom: 22,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                });

                // Map Topography
                const topography = L.tileLayer('//server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 22,
                    maxNativeZoom: 18,
                });

                // Map Grayscale
                const grayscale = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://cartodb.com/attributions">CartoDB</a>',
                    subdomains: 'abcd',
                    minZoom: 0,
                    maxZoom: 22,
                    maxNativeZoom: 18,
                });

                // Setting basemaps
                const basemaps = {
                    "<div class='layers-control-img'><img src='{{ asset('img/topography.png') }}'></div> Topography": topography,
                    "<div class='layers-control-img'><img src='{{ asset('img/satellite.png') }}'></div> Sattelite": sattelite,
                    "<div class='layers-control-img'><img src='{{ asset('img/grayscale.png') }}'></div> Grayscale": grayscale,
                    "<div class='layers-control-img'><img src='{{ asset('img/street.png') }}'></div> Streets": streets,
                };

                // Latitude dan Longitude Jakarta
                var jakartaLatLng = [-6.2088, 106.8456];

                // Konfigurasi peta
                map = L.map('map', {
                    center: jakartaLatLng,
                    zoom: 12,
                    zoomControl: false,
                    fullscreenControl: false,
                    layers: [topography, sattelite, grayscale, streets]
                });

                // Add change layer control
                L.control.layers(basemaps).addTo(map);

                // Add fullscreen control
                L.control.fullscreen({
                    position: 'topright'
                }).addTo(map);

                // Get Current Location control
                L.control.locate({
                    strings: {
                        title: "Show my location!"
                    },
                    position: 'topright',
                    initialZoomLevel: 13
                }).addTo(map);

                // Add Zoom control
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);

                L.control.myControl({
                    position: 'topleft'
                }).addTo(map);

                // Add markers for incidents
                incidents.forEach(incident => {
                    let popupContent = `
                        <div class="popup-card">
                            <div class="popup-card-header">${incident.incident_number}</div>
                            <div class="popup-card-body">
                                <img src="{{ asset('${incident.image}') }}" width="50" alt="" class="d-block mx-auto">
                                <p>Status: ${incident.status}</p>
                                <hr class="my-2">
                                <p>Description:</p>
                                <p>${incident.description}</p>
                            </div>
                            <div class="popup-card-footer">
                    `;

                    if (incident.status === 'requested') {
                        popupContent += `<button class="btn btn-success accept-btn" data-id="${incident.id}" data-url="/queue/${incident.id}/accept">Accept</button>`;
                    } else if (incident.status === 'processed') {
                        popupContent += `<button class="btn btn-warning complete-btn" data-id="${incident.id}" data-url="/incidents/${incident.id}/complete">Complete</button>`;
                    }

                    popupContent += `</div></div>`;

                    L.marker([incident.latitude, incident.longitude], {
                        icon: incident.status !== 'completed' ? onicon : officon
                    }).addTo(map)
                    .bindPopup(popupContent);
                });

                // Event listeners for buttons
                map.on('popupopen', function(e) {
                    const acceptButtons = document.querySelectorAll('.accept-btn');
                    acceptButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const incidentId = this.getAttribute('data-id');
                            const url = this.getAttribute('data-url');
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            fetch(`${url}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message === 'Incident accepted successfully') {
                                    Swal.fire(
                                        'Accepted!',
                                        'The incident has been accepted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message,
                                        'error'
                                    )
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        });
                    });

                    const completeButtons = document.querySelectorAll('.complete-btn');
                    completeButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const incidentId = this.getAttribute('data-id');
                            const url = this.getAttribute('data-url');
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            fetch(`${url}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message === 'Incident completed successfully') {
                                    Swal.fire(
                                        'Completed!',
                                        'The incident has been completed.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message,
                                        'error'
                                    )
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        });
                    });
                });
            });
        }
    });

</script>
@endpush
