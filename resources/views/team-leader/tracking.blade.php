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
                @forelse ($team?->members as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->latest_track?->latitude ?? 0 }}, {{ $member->latest_track?->longitude ?? 0 }}</td>
                    <td>{{ $member->latest_track?->location ?? 'Unknown' }}</td>
                    <td>@include('team-leader.components.tracking-status', ['tracking' => $member->latest_track])</td>
                    <td>{{ $member->lastest_track?->created_at?->diffForHumans() ?? 'No date' }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <h1>There are not tracks available</h1>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
