@php
    $counts = [
        [
            'count' => $teamsDeployedCount,
            'title' => 'Teams Deployed',
            'icon' => 'ti-receipt',
            'color' => 'bg-blue-lt'
        ],
        [
            'count' => $totalDeployeesCount,
            'title' => 'Total Deployees',
            'icon' => 'ti-users',
            'color' => 'bg-lime-lt'
        ],
    ];
@endphp
@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Dashboard')

@section('content')
<div class="row align-self-center ">
    <div class="col-12">
        <h1 class="text-center">
            Welcome to {{ env('APP_NAME') }}
        </h1>
    </div>
    <div class="col-4">
        @include('components.timezone')
    </div>
    <a href="#" class="card card-sm border-0 p-0 col-8">
        <div class="card-body h2 text-center">
            <div class="page-pretitle mb-3">Inspiring Quote</div>
            {{ quote() }}
        </div>
    </a>
    @foreach ($counts as $summary)
        <div class="col-6 mt-3">
            <a href="#" class="card card-sm border-0 p-0">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-auto ps-3">
                            <span @class(['avatar', 'avatar-sm', 'fs-3', $summary['color']])><i @class(['ti', 'icon', $summary['icon']])></i></span>
                        </div>
                        <div class="col">
                            <div class="subheader">{{ $summary['title'] }}</div>
                            <div class="fw-bold fs-3">{{ $summary['count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection
