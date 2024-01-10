@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment')

@section('content-header')
    <div class="row align-items-center ">
        <div class="col">
            <x-search />
        </div>
        <div class="d-flex col flex-column  align-items-end">
            <div class="">
                Total Deployments: {{ $totalDeployments ?? 0 }}
            </div>
            <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deployment-create-modal">
                <i class="ti ti-plus icon"></i>
                Add Deployment
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Team Name</th>
                <th scope="col">Deployee Name</th>
                <th scope="col">Role</th>
                <th scope="col">Last Known Location</th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deployments as $date => $deps)
            <tr>
                <td colspan="7" style="background-color: rgb(217, 231, 243);">
                    <b>{{ $date }}</b>
                </td>
            </tr>
                @foreach ($deps as $deployment)
                <tr>
                    <td>
                        {{ $deployment->team->name }}
                    </td>
                    <td>
                        {{ $deployment->user->full_name }}
                    </td>
                    <td>
                        @include('components.role-badge', ['user' => $deployment->user])
                    </td>
                    <td>
                        {{ $deployment->user->latest_track?->location ?? 'No last location' }}
                    </td>
                    <td>
                        @include('human-resource.components.deployment-status', ['deployment' => $deployment])
                    </td>
                    <td>
                        {{ $deployment->created_at->diffForHumans() }}
                    </td>
                    <td>
                        @if($deployment->isNoResponse())
                        <a href="{{ route('human-resource.notification-send', $deployment->id) }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Resend Availability">
                            <i class="ti ti-send icon"></i>
                        </a>
                        @endif
                        @if($deployment->isDeclined() && $deployment->isNotReplaced())
                        <a href="{{ route('human-resource.deployment.replace-auto', $deployment->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Automatically Replace User">
                            <i class="ti ti-user-cog icon"></i>
                        </a>
                        {{-- <a href="{{ route('human-resource.deployment.replace-auto', $deployment->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Manually Replace User">
                            <i class="ti ti-user-plus icon"></i>
                        </a> --}}
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
            <td colspan="7">
                <h1 class="text-center">There are no deployments</h1>
            </td>
            @endforelse
        </tbody>
    </table>
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
@endsection
