@extends('layouts.human-resource')

@section('title', 'Deployees')

@section('pre-title', 'Deployees')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Deployees: {{ $brandAmbassadors?->count() ?? 0 }}
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($brandAmbassadors as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>@include('components.role-badge', ['user' => $user])</td>
                    <td>@include('human-resource.components.ba-status', ['status' => $user->latest_status])</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <h1>No entries.</h1>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
