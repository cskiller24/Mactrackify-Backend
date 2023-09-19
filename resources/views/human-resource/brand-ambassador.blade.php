@extends('layouts.human-resource')

@section('title', 'Brand Ambassadors')

@section('pre-title', 'Brand Ambassadors')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Data: {{ $totalSales ?? 0 }}
        </div>
        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ba-create-modal">
            <i class="ti ti-plus icon"></i>
            Data
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Location</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 10; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ fake()->name() }}</td>
                    <td>{{ fake()->unique()->email() }}</td>
                    <td>{{ fake()->city() }}</td>
                    <td>@include('human-resource.components.ba-status')</td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection
