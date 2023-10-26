@extends('layouts.team-leader')

@section('title', 'Notifications')

@section('pre-title', 'Team Leader')

@section('page-title', 'Notifications')

@section('content')
    @forelse ($notifications as $notification)
        <a href="{{ $notification->data['link'] }}" @class(['card', 'border', 'border-green' => $notification->read_at === null, 'border-gray' => $notification->read_at, 'border-2', 'p-3', 'mb-3', 'rounded']) >
            <div class="d-flex justify-content-between ">
                <div>
                    <h3>{{ $notification->data['title'] }}</h3>
                    <p>{{ $notification->data['description'] }}</p>
                </div>
                <div>
                    <i class="ti ti-eye"></i>
                </div>
            </div>
        </a>

        @php
            $notification->markAsRead()
        @endphp
    @empty
        <h1 class="text-center">No notifications</h1>
    @endforelse
@endsection
