@extends('layouts.team-leader')

@section('title', 'Tracking')

@section('pre-title', 'Tracking')

@section('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1>Live Tracking</h1>
    </div>
    <div class="col-12 d-flex justify-content-center">
        <div id="map" style="width: 75vw; height: 50vh;"></div>
    </div>

    <div class="col-12 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Deployee Name</th>
                    <th scope="col">GPS Coordinates</th>
                    <th scope="col">Location</th>
                    <th scope="col">Status</th>
                    <th scope="col">Location Retrieve At</th>
                </tr>
            </thead>
            <tbody id="t-body">
                {{-- <tr>
                    <td>1</td>
                    <td>
                        <a href="#">Cedy Cadayong</a>
                    </td>
                    <td onclick="centerMap(13.123,132.232)">13.123, 132.232</td>
                    <td>Manila</td>
                    <td>Spoofed</td>
                    <td>1 second ago</td>
                </tr> --}}
            </tbody>
            @if(request('enable-route'))
            <button class="" id="map-route">
                Set Route
            </button>
            <button class="" id="map-route-remove">
                Remove route
            </button>
            @endif
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var map;
    var markers = [];
    var waypoints = [];
    var startPoint = null;
    var endPoint = null;
    var directionsDisplay;
    var directionsService;

    var apiTracks = '{{ route('api.tracks.show', $user->id) }}'

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: { lat: 14.58977819216876, lng: 120.98159704631904 },
            zoom: 13,
        });

        directionsService = new google.maps.DirectionsService()
        directionsDisplay = new google.maps.DirectionsRenderer()
        directionsDisplay.setMap(map)
    }

    initMap();

    function makeAPICall() {
        $.ajax({
            url : apiTracks,
            method: 'GET',
            success: function (data) {
                removeMarkers()
                removeRouteMap()
                if(!data.latest_tracking.length) {
                    var tr = $('<tr>');
                    var td = $('<td colspan="6">');
                    var h1 = $('<h1 class="mt-4 text-center">').text('No entries found.');

                    td.append(h1);
                    tr.append(td);

                    $('#t-body').append(tr);
                } else {
                    data.latest_tracking.map(function (row, index, array) {
                        renderTableRow({
                            id: index + 1,
                            fullName: data.fullName,
                            latitude: row.latitude,
                            longitude: row.longitude,
                            location: row.location,
                            is_authentic: row.is_authentic,
                            createdAtFormatted: row.createdAtFormatted,
                            profile_link: data.profile_link,
                        })
                        if(index === 0) {
                            endPoint = new google.maps.LatLng(row.latitude, row.longitude)
                            addMarker(map, parseFloat(row.latitude), parseFloat(row.longitude), row.location ?? "Null", data.profile_link)
                        }

                        if(index === data.latest_tracking.length - 1) {
                            startPoint = new google.maps.LatLng(row.latitude, row.longitude)
                        }


                        if(index > 0 && index < data.latest_tracking.length - 1) {
                            console.log('added')
                            waypoints.push({
                                location: new google.maps.LatLng(row.latitude, row.longitude),
                                stopover: true
                            })
                        }

                    })
                }
            }
        })
    }

    function centerMap(lat, lng, location, profile_link) {
        const center = new google.maps.LatLng(lat, lng);
        map.setCenter(center);
        map.setZoom(14.5);
        removeMarkers();
        addMarker(map, parseFloat(lat), parseFloat(lng), location, profile_link);
    }

    function addMarker(map, lat, lng, title, image) {
        const icon = {
            url: image,
            scaledSize: new google.maps.Size(75, 75),
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        }
        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: title, // You can customize the title if needed
            icon: icon
        });

        markers.push(marker)
    }

    function removeMarkers() {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }

    function removeRouteMap() {
        if (directionsDisplay != null) {
            directionsDisplay.setMap(null);
            directionsDisplay = null;
        }
        waypoints = []
        startPoint = null;
        endPoint = null;
    }

    function addRouteMap() {
        // console.log(waypoints.length > 0 && startPoint != null && endPoint != null)
        console.log(waypoints.length > 0)
        console.log(startPoint != null, startPoint)
        console.log(endPoint != null)
        if(waypoints.length > 0 && startPoint != null && endPoint != null) {
            var request = {
                origin: startPoint,
                destination: endPoint,
                waypoints: waypoints,
                travelMode: google.maps.DirectionsTravelMode.WALKING
            }
            directionsService.route(request, function (response, status) {
                if(directionsDisplay == null) {
                    directionsDisplay = new google.maps.DirectionsRenderer()
                    directionsDisplay.setMap(map)
                }
                if (status == google.maps.DirectionsStatus.OK) {

                    directionsDisplay.setDirections(response)
                }
            })
        }
    }


    function renderTableRow(data) {
        const newRow = $('<tr>');

        // Append cells with data
        newRow.append($('<td>').text(data.id));
        newRow.append($('<td>').html('<a href="#">' + data.fullName + '</a>'));
        newRow.append($('<td>').text(data.latitude + ', ' + data.longitude).click(function() {
            centerMap(data.latitude, data.longitude, data.location, data.profile_link);
        }).addClass('cursor-pointer'));
        newRow.append($('<td>').text(data.location ?? "Null"));
        newRow.append($('<td>').text(data.is_authentic ? 'Genuine' : 'Spoofed'));
        newRow.append($('<td>').text(data.createdAtFormatted));

        // Append the new row to the table
        $('#t-body').append(newRow);
    }

    makeAPICall()
    $('#map-route').click(function () {
        addRouteMap()
    })

    $('#map-route-remove').click(function () {
        removeRouteMap()
    })

    setInterval(function() {
        $('#t-body').html('')
        $('')
        makeAPICall();
    }, 30000);
})

</script>
@endsection
