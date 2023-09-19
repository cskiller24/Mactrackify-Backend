@extends('layouts.human-resource')

@section('title', 'Dashboard')

@section('pre-title', 'Deployment')

@section('content')
<div class="accordion" id="accordionExample">
    @for ($i = 0; $i < 10; $i++)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $i }}" aria-expanded="true" aria-controls="collapseOne{{ $i }}">
            {{ fake()->words(mt_rand(3,5), true).' - '.fake()->words(mt_rand(3,5), true) }}
          </button>
        </h2>
        <div id="collapseOne{{ $i }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($j = 0; $j < rand(1, 5); $j++)
                        <tr>
                            <td>{{ $j + 1 }}</td>
                            <td>{{ fake()->name() }}</td>
                            <td>@include('human-resource.components.deployment-status')</td>
                            <td><x-role-badge role="{{$j === 0 ? 'team_leader' : 'brand_ambassador'}}" /></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endfor
@endsection
