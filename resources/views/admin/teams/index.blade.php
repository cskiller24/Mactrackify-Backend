@extends('layouts.admin')

@section('title', 'Teams')

@section('pre-title', 'Teams')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Teams: {{ $total }}
        </div>
        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#team-create-modal">
            <i class="ti ti-plus icon"></i>
            Team
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="accordion" id="accordionExample">
    @forelse ($teams as $team)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $team->id }}" aria-expanded="true" aria-controls="collapseOne{{ $team->id }}">
            {{ $team->name. '' .$team->location }}
          </button>
        </h2>
        <div id="collapseOne{{ $team->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($team->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>@include('components.role-badge', ['user' => $user])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <h1 class="mt-4 text-center">No entries found.</h1>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
        <h1 class="mt-3 text-center card p-5" >No entries found</h1>
    @endforelse
@endsection

@section('modals')
<div class="modal fade" id="team-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.teams.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter the location">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="team_leader" class="form-label">Team Leader</label>
                        <select name="team_leader" id="team_leader" class="form-select">
                            <option value="" selected disabled>-- Select Team Leader --</option>
                            @foreach ($teamLeaderCreate as $teamLeader)
                                <option value="{{ $teamLeader->id }}">{{ $teamLeader->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="ba_select" class="form-label">Brand Ambassadors</label>
                        <div class="row">
                            @foreach ($brandAmbassadorCreate as $brandAmbassador)
                            <div class="col-4 d-flex">
                                <input type="checkbox" name="brand_ambassador[]" class="form-check me-1" id="ba_{{ $brandAmbassador->id }}" value="{{ $brandAmbassador->id }}">
                                <label for="ba_{{ $brandAmbassador->id }}" class="form-check-label align-middle">{{ $brandAmbassador->full_name }}</label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
@endsection
