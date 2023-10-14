@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment')

@section('content')
<div class="border border-dark bg-light rounded p-2">
    <h2 class="text-center ">Create Deployment for Team {{ $team->name }} </h2>
    <form action="{{ route('human-resource.deployment.store', $team->id) }}" method="post">
        @csrf
        <input type="hidden" name="team_id" value="{{ $team->id }}">
        <div class="mb-3">
            <label for="team_leader_id" class="form-label">Team Leader</label>
            <select name="team_leader" id="team_leader" class="form-select">
                <option value="" selected disabled>-- SELECT TEAM LEADER --</option>
                @foreach ($team->leaders as $leader)
                    <option value="{{ $leader->id }}">{{ $leader->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
            <label class="form-label">Select Brand Ambassadors to Deploy</label>
            @foreach ($team->members as $member)
                <div class="col-12 col-sm-3 p-2 form-check bg-light mx-1">
                    <input type="checkbox" name="brand_ambassador[]" id="ba_{{ $member->id }}" value="{{ $member->id }}">
                    <label for="ba_{{ $member->id }}">
                        {{ $member->full_name }}
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <input type="submit" value="Submit" class="btn btn-outline-primary w-100 ">
        </div>
    </form>
</div>
@endsection
