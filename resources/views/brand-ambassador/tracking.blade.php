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
        <img src="{{ asset('SampleMap.png') }}" alt="Sample Map" class="border border-2 border-dark">
    </div>
    <div class="mx-5 col">
        <form action="{{ route('brand-ambassador.test.track') }}" method="post" class="col d-flex justify-content-center">
            @csrf
            @if($isTracking === false)
            <button type="submit" class="btn btn-success w-50 mt-2" id="enable-tracking">Enable Tracking</button>
            @else
            <button type="submit" class="btn btn-danger w-50 mt-2" id="disable-tracking">Disable Tracking</button>
            @endif
        </form>
    </div>
    <div class="table-response mt-3">
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>GPS Coordinates</th>
                    <th>Location</th>
                    <th>Authenticty</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tracks as $track)
                    <tr>
                        <td>{{ $track->id }}</td>
                        <td>{{ $track->latitude }} | {{ $track->longitude }}</td>
                        <td>{{ $track->location }}</td>
                        <td>@include('brand-ambassador.components.tracking-status', ['tracking' => $track])</td>
                        <td>{{ $track->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <h1>No entries found</h1>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('scripts')
@endsection
