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
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <th scope="col">Submitted Data</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @if ($team)
        @foreach ($team->members as $user)
        <tr>
            <th scope="row">{{ $user->id }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>@include('team-leader.components.status', ['status' => $user->latestStatus])</td>
            <td>{{ $user->sales->count() }}</td>
        </tr>
        @endforeach
        @else

        @endif
        {{-- <tr>
            <th scope="row">{{ $i + 1 }}</th>
            <td>{{ fake()->name() }}</td>
            <td>{{ fake()->unique()->email() }}</td>
            <td>@include('team-leader.components.status')</td>
            <td>{{ mt_rand(100, 1000) }}</td>
            <td>@include('team-leader.components.actions')</td>
        </tr> --}}
    </tbody>
  </table>
@endsection
