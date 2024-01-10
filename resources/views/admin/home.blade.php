@php
    $counts = [
        [
            'count' => $userCount,
            'title' => 'Total Users',
            'icon' => 'ti-users',
            'color' => 'bg-blue-lt'
        ],
        [
            'count' => $invitationCount,
            'title' => 'Total Invitations',
            'icon' => 'ti-users-plus',
            'color' => 'bg-lime-lt'
        ],
        [
            'count' => $teamCount,
            'title' => 'Total Teams',
            'icon' => 'ti-users-group',
            'color' => 'bg-cyan-lt'
        ],
        [
            'count' => $warehouseCount,
            'title' => 'Total Warehouses',
            'icon' => 'ti-building-warehouse',
            'color' => 'bg-orange-lt',

        ],
        [
            'count' => $warehouseItemCount,
            'title' => 'Total Warehouse Items',
            'icon' => 'ti-tools',
            'color' => 'bg-red-lt',

        ],
        [
            'count' => $accountsCount,
            'title' => 'Total Accounts',
            'icon' => 'ti-credit-card',
            'color' => 'bg-yellow-lt',
        ]
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Admin')

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
            <div class="col-4 mt-3">
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
