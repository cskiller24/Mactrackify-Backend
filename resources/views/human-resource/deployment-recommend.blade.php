@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment Recommendations')

@section('content')
<div class="row card p-3 border border-dark">
    <h1>Replace a Deployee on Team {{ $team->name }}</h1>
    <h2>Deployee to replace : {{ $user->full_name }}</h2>

    <form action="" method="post">
        @csrf
        <label for="ba" class="form-label">Select which Deployee would you want to replace</label>
        <select name="brand_ambassador" id="ba" class="form-select mb-3">
            <option value="" selected disabled>-- SELECT DEPLOYEE --</option>
            @foreach ($team->members as $member)
                @if($member->id !== $user->id)
                <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->latestStatus->status }}</option>
                @endif
            @endforeach
        </select>
        <input type="submit" value="Change brand ambassador" class="btn btn-primary w-100">
    </form>
</div>
@endsection
