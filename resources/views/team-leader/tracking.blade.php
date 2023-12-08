@extends('layouts.team-leader')

@section('title', 'Tracking')

@section('pre-title', 'Tracking')

@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1>Live Brand Ambassador Tracking</h1>
    </div>
    <div class="col-12 d-flex justify-content-center">
        <div id="map" style="width: 75vw; height: 50vh;"></div>
    </div>

    <div class="col-12 table-responsive">
        <table class="table table-striped">
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
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let map;

    var apiTracks = '{{ route('api.tracks') }}'

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: { lat: 14.58977819216876, lng: 120.98159704631904 },
            zoom: 8,
        });
    }

    initMap();

    function makeAPICall() {
        $.ajax({
            url : apiTracks,
            method: 'GET',
            success: function (data) {
                if(!data.length) {
                    var tr = $('<tr>');
                    var td = $('<td colspan="6">');
                    var h1 = $('<h1 class="mt-4 text-center">').text('No entries found.');

                    // Build the structure
                    td.append(h1);
                    tr.append(td);

                    // Append to the table body
                    $('#t-body').append(tr);
                } else {
                    console.log(data)
                    data.map(function (row) {
                        renderTableRow(row)
                    })
                }
            }
        })
    }

    function centerMap(lat, lng) {
        const center = new google.maps.LatLng(lat, lng);
        map.setCenter(center);
    }

    function renderTableRow(data) {
        const newRow = $('<tr>');

        // Append cells with data
        newRow.append($('<td>').text(data.id));
        newRow.append($('<td>').html('<a href="#">' + data.fullName + '</a>'));
        newRow.append($('<td>').text(data.latestTrack.latitude + ', ' + data.latestTrack.longitude).click(function() {
            centerMap(data.latestTrack.latitude, data.latestTrack.longitude);
        }));
        newRow.append($('<td>').text(data.latestTrack.location ?? "Null"));
        newRow.append($('<td>').text(data.latestTrack.is_authentic ? 'Genuine' : 'Spoofed'));
        newRow.append($('<td>').text(data.latestTrack.createdAtDiff));

        // Append the new row to the table
        $('#t-body').append(newRow);
    }

    makeAPICall()

    setInterval(function() {
        $('#t-body').html('')
        makeAPICall();
    }, 30000);
})

</script>
@endsection
