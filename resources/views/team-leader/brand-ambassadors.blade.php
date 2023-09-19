@extends('layouts.team-leader')

@section('title', 'Brand Ambassadors')

@section('pre-title', 'Brand Ambassadors')

@section('content-header')
<div class="row">
    <div class="col-12 text-center">
        <h1>Team Name</h1>
    </div>
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Brand Ambassadors: 10000
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
        @for ($i = 0; $i < 100; $i++)
        <tr>
            <th scope="row">{{ $i + 1 }}</th>
            <td>{{ fake()->name() }}</td>
            <td>{{ fake()->unique()->email() }}</td>
            <td>@include('team-leader.components.status')</td>
            <td>{{ mt_rand(100, 1000) }}</td>
            <td>@include('team-leader.components.actions')</td>
        </tr>
        @endfor
    </tbody>
  </table>
@endsection
