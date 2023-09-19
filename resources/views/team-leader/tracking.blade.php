@extends('layouts.team-leader')

@section('title', 'Tracking')

@section('pre-title', 'Tracking')

@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1>Live Brand Ambassador Tracking</h1>
    </div>
    <div class="col-12 d-flex justify-content-center">
        <img src="{{ asset('SampleMap.png') }}" alt="Sample Map" class="border border-2 border-dark">
    </div>
    <div class="col-12 text-center">
        <p class="h3">Deployed Brand Ambassadors: <b>{{ mt_rand(5, 10) }}</b></p>
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
            <tbody>
                @for ($i = 0; $i < rand(1,20); $i++)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ fake()->name() }}</td>
                        <td>{{ fake()->localCoordinates()['latitude'].', '.fake()->localCoordinates()['longitude'] }}</td>
                        <td>{{ fake()->city() }}</td>
                        <td>@include('team-leader.components.tracking-status')</td>
                        <td>{{ \Illuminate\Support\Carbon::parse(fake()->dateTimeBetween('-1 day'))->diffForHumans() }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection
