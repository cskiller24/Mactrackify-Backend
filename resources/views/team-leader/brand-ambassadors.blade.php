@extends('layouts.team-leader')

@section('title', 'Brand Ambassadors')

@section('pre-title', 'Brand Ambassadors')

@section('content-header')
<div class="row">
    <div class="col-12 text-center">
        <h1>{{ $team?->name ?? 'No Team' }}</h1>
    </div>
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Brand Ambassadors: {{ $team?->members?->count() ?? 0 }}
        </div>
    </div>
</div>
@endsection

@section('content')
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">User ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Latest Deployment Status</th>
        <th scope="col">Submitted Data</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @if ($team)
            @foreach ($members as $user)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>
                <td>@include('human-resource.components.deployment-status', ['deployment' => $user->deployments()->latest()->first()])</td>
                <td>
                    <a href="{{ route('team-leader.data', ['user_id' => $user->id]) }}">
                        {{ $user->transactions->count() }}
                    </a>
                </td>
                <td>
                    <a href="" title="View last spoofed tracking" >
                        <i class="ti ti-eye-pin"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        @else

        @endif
    </tbody>
  </table>
@endsection
