@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment')

@section('content-header')
    <div class="row">
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                Total Deployments: {{ $totalDeployments ?? 0 }}
            </div>
            <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deployment-create-modal">
                <i class="ti ti-plus icon"></i>
                Create Deployment for Tommorow ({{ now()->addDay()->toDateString() }})
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="border border-dark rounded  bg-light p-2 mb-3">
        <h1 class="text-center">Deployments for Today</h1>
        <div class="row mx-1 mb-3">
        @forelse ($todayDeployments as $team => $data)
            <div class="col-4 btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#team_{{ $data->first()->team_id }}">
                {{ $team }}
            </div>
        @empty
            <h2 class="text-center my-2">There are no deployments for today</h2>
        @endforelse
        </div>
    </div>

    <div class="border border-dark rounded  bg-light p-2">
        <h1 class="text-center">Deployments for Tommorow</h1>
        <div class="row mx-1 mb-3">
        @forelse ($tommorowDeployments as $team => $data)
            <div class="col-4 btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#team_tommorow_{{ $data->first()->team_id }}">
                {{ $team }}
            </div>
        @empty
            <h2 class="text-center my-2">There are no deployments for tommorow</h2>
        @endforelse
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="deployment-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Team to Create Deployment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($teams as $team)
                    <a href="{{ route('human-resource.deployment.create', $team->id) }}" class="btn btn-outline-primary w-100 mb-2 ">{{ $team->name }}</a>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($todayDeployments as $team => $deployments)
    <div class="modal fade" id="team_{{ $deployments->first()->team_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Team {{ $team }} Development for Today</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table table-responsive ">
                    <table class="table table-striped ">
                        <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Deployment Status</th>
                            <th>Deployment Last Update</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($deployments as $deployment)
                            <tr>
                                <td>{{ $deployment->user->full_name }}</td>
                                <td>{{ $deployment->user->email }}</td>
                                <td>@include('components.role-badge', ['user' => $deployment->user])</td>
                                <td>@include('human-resource.components.deployment-status', ['deployment' => $deployment])</td>
                                <td>{{ $deployment->updated_at->diffForHumans() }}</td>
                                <td>
                                    @include('human-resource.components.deployment-action', ['deployment' => $deployment])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @foreach ($tommorowDeployments as $team => $deployments)
    <div class="modal fade" id="team_tommorow_{{ $deployments->first()->team_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Team {{ $team }} Development for Tommorow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table table-responsive ">
                    <table class="table table-striped ">
                        <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Deployment Status</th>
                            <th>Deployment Last Update</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($deployments as $deployment)
                            <tr>
                                <td>{{ $deployment->user->full_name }}</td>
                                <td>{{ $deployment->user->email }}</td>
                                <td>@include('components.role-badge', ['user' => $deployment->user])</td>
                                <td>@include('human-resource.components.deployment-status', ['deployment' => $deployment])</td>
                                <td>{{ $deployment->updated_at->diffForHumans() }}</td>
                                <td>
                                    @include('human-resource.components.deployment-action', ['deployment' => $deployment])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection
