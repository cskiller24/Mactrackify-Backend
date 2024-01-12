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
                <tr>
                    <td>1</td>
                    <td>
                        <a href="#">{{ $track->brandAmbassador->full_name }}</a>
                    </td>
                    <td onclick="centerMap(13.123,132.232)">{{ $track->latitude }} - {{ $track->longitude }}</td>
                    <td>{{ $track->location ?? 'No location' }}</td>
                    <td>Spoofed</td>
                    <td>{{ $track->createdAtFormatted }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var map;
    var markers = [];

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: { lat: {{ $track->latitude }}, lng: {{ $track->longitude }} },
            zoom: 13,
        });

        addMarker(map, {{ $track->latitude }}, {{ $track->longitude }}, '{{ $track->location }}', '{{ $track->brandAmbassador->profile_link }}')
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

    initMap();

})
</script>
@endsection
