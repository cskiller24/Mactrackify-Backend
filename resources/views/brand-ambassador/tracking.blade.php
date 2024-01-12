@extends('layouts.brand-ambassador')

@section('pre-title', 'Deployee')

@section('content-header')

@endsection
@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1>Location Tracking</h1>
    </div>

    <div class="col-12 d-flex justify-content-center">
        <div id="map" style="width: 75vw; height: 50vh;"></div>
    </div>
    <div class="mx-5 col">
        <p class="text-red text-center h1 d-none " id="geo-text">Geo location is not supported in this browser. Please enable location permission</p>
        <button type="submit" class="btn btn-success w-100 mt-2" id="enable-tracking" onclick="toggleTrack()">Enable Tracking</button>
    </div>
    <div class="table-response mt-3">
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Brand Ambassador Name</th>
                    <th scope="col">GPS Coordinates</th>
                    <th scope="col">Location</th>
                    <th scope="col">Status</th>
                    <th scope="col">Location Retrieve At</th>
                </tr>
            </thead>
            <tbody id="t-body">
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('scripts')
<script>
var isTrack = localStorage.getItem('is_track') === 'true' ?? false;
$(document).ready(function () {
    var map;
    var markers = [];

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: { lat: 14.58977819216876, lng: 120.98159704631904 },
            zoom: 13,
        });
    }

    var apiTracks = '{{ route('api.tracks.show', $user->id) }}'

    initMap();

    if(isTrack) {
        $('#enable-tracking').addClass('btn-danger')
        $('#enable-tracking').removeClass('btn-success')
        $('#enable-tracking').html('Disable tracking')

        if(navigator.geolocation) {
            // navigator.geolocation.getCurrentPosition(position => {
            //     console.log(position.coords.latitude)
            // }, error => {
            //     $('#geo-text').removeClass('d-none')
            // });

            setLocation()

            setInterval(function() {
                setLocation()
            }, 30000);
        } else {
            $('#geo-text').removeClass('d-none')
        }
    } else {
        $('#enable-tracking').addClass('btn-success')
        $('#enable-tracking').removeClass('btn-danger')
        $('#enable-tracking').html('Enable tracking')
    }

    function setLocation() {
        var locationUrl = '{{ route('api.tracks.store') }}';
        navigator.geolocation.getCurrentPosition(position => {
            var lat = position.coords.latitude
            var lng = position.coords.longitude
            $.ajax({
                url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyAHv9dGlK4BtbyuVplUHLPJA4aQ4SjnWwA`,
                method: 'GET',
                success: function (data) {
                    $.ajax({
                        url: locationUrl,
                        method: 'POST',
                        data: {latitude: lat, longitude: lng, is_authentic: 1, location: data.results[0].formatted_address},
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data2) {
                            console.log(data2)
                        },
                    })
                }
            })
        }, error => {
            $('#geo-text').removeClass('d-none')
        });
    }



    function makeAPICall() {
        $.ajax({
            url : apiTracks,
            method: 'GET',
            success: function (data) {
                removeMarkers()
                if(!data.latest_tracking.length) {
                    var tr = $('<tr>');
                    var td = $('<td colspan="6">');
                    var h1 = $('<h1 class="mt-4 text-center">').text('No entries found.');

                    td.append(h1);
                    tr.append(td);

                    $('#t-body').append(tr);
                } else {
                    data.latest_tracking.map(function (row) {
                        renderTableRow({
                            id: data.id,
                            fullName: data.fullName,
                            latitude: row.latitude,
                            longitude: row.longitude,
                            location: row.location,
                            is_authentic: row.is_authentic,
                            createdAtDiff: row.createdAtDiff
                        })
                        addMarker(map, parseFloat(row.latitude), parseFloat(row.longitude), row.location ?? "Null")
                    })
                }
            }
        })
    }

    function centerMap(lat, lng) {
        const center = new google.maps.LatLng(lat, lng);
        map.setCenter(center);
        map.setZoom(16.5)
    }

    function addMarker(map, lat, lng, title) {
        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: title // You can customize the title if needed
        });

        markers.push(marker)
    }

    function removeMarkers() {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }


    function renderTableRow(data) {
        const newRow = $('<tr>');

        // Append cells with data
        newRow.append($('<td>').text(data.id));
        newRow.append($('<td>').html('<a href="#">' + data.fullName + '</a>'));
        newRow.append($('<td>').text(data.latitude + ', ' + data.longitude).click(function() {
            centerMap(data.latitude, data.longitude);
        }).addClass('cursor-pointer'));
        newRow.append($('<td>').text(data.location ?? "Null"));
        newRow.append($('<td>').text(data.is_authentic ? 'Genuine' : 'Spoofed'));
        newRow.append($('<td>').text(data.createdAtDiff));

        // Append the new row to the table
        $('#t-body').append(newRow);
    }

    makeAPICall()

    setInterval(function() {
        $('#t-body').html('')
        $('')
        makeAPICall();
    }, 30000);
})

function toggleTrack() {
    localStorage.setItem('is_track', ! isTrack);
    location.reload()
}
</script>
@endsection
