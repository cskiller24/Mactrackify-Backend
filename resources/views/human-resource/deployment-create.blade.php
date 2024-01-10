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
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="team_leader_id" class="form-label">Deployer</label>
            <select name="team_leader" id="team_leader" class="form-select">
                <option value="" selected disabled>-- SELECT DEPLOYER --</option>
                @foreach ($team->leaders as $leader)
                    <option value="{{ $leader->id }}">{{ $leader->full_name }}</option>
                @endforeach
            </select>
        </div>

        @foreach (range(1, 3) as $num)
        <div class="mb-3">
            <label for="deployee_{{ $num }}" class="form-label">Deployee {{ $num }}</label>
            <select name="brand_ambassador[]" class="form-select">
                <option value="" selected disabled>-- SELECT DEPLOYEE --</option>
                @foreach($team->members as $member)
                <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                @endforeach
            </select>
        </div>
        @endforeach

        <div class="mb-3">
            <input type="submit" value="Submit" class="btn btn-outline-primary w-100 ">
        </div>
    </form>
</div>
@endsection
