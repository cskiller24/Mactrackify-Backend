@extends('layouts.brand-ambassador')

@section('title', 'Scheduled Deployments')

@section('pre-title', 'Deployee')

@section('content')
<h1 class="text-center">List of deployments</h1>
<div class="row">
    @foreach ($deployments as $deployment)
    <div @class(['card', 'col-12', 'my-1', 'bg-green-lt' => $deployment->isAccepted(), 'border-dark', 'bg-yellow-lt' => $deployment->isNoResponse(), 'bg-red-lt' => $deployment->isDeclined()])class="card col-12 my-1 bg-green-lt border-dark">
        <div class="card-body text-black d-flex justify-content-between ">
            <div>
                <h2>Date: {{ $deployment->date }}</h2>
                <h3>Status : @include('human-resource.components.deployment-status', ['deployment' => $deployment])</h3>
            </div>
            @if($deployment->isNoResponse())
            <div class="d-flex align-items-center ">
                <form action="{{ route('brand-ambassador.schedule.update', $deployment->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ App\Models\Deployment::ACCEPTED }}">
                    <button class="btn-success btn mx-1" type="submit">Accept</button>
                </form>
                <form action="{{ route('brand-ambassador.schedule.update', $deployment->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ App\Models\Deployment::DECLINED }}">
                    <button class="btn-danger btn mx-1" type="submit">Decline</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endforeach

</div>


@endsection
