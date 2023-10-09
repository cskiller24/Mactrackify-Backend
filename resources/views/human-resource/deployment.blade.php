@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment')

@section('content')
<div class="accordion" id="accordionExample">
    @forelse ($teams as $team)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $team->id }}" aria-expanded="true" aria-controls="collapseOne{{ $team->id }}">
            {{ $team->name .' - '. $team->location }}
          </button>
        </h2>
        <div id="collapseOne{{ $team->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Role</th>
                            <th scope="col">
                                Actions
                                <a href="{{ route('human-resource.notification-send-all', $team->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send all notifications">
                                    <i class="ti ti-send"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($team->users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>@include('human-resource.components.deployment-status', ['status' => $user->latest_status])</td>
                            <td>@include('components.role-badge', ['user' => $user])</td>
                            <td>
                                <form action="" action="POST">
                                    @csrf
                                    <a href="{{ route('human-resource.notification-send', $user->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send notification">
                                        <i class="ti ti-send"></i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <h1 class="text-center">No teams</h1>
    @endforelse
</div>
@endsection
