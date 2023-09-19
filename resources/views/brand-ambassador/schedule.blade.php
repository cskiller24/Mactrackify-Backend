@extends('layouts.brand-ambassador')

@section('pre-title', 'Brand Ambassador')

@section('content')
<div class="d-flex justify-content-center">
    <div class="border border-2 border-dark bg-green p-3 w-50 rounded-3">
        <h1 class="text-center text-white">Availablity</h1>
        <h2 class="text-center text-white">Status: Available</h2>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    <div class="border border-2 border-dark bg-purple p-3 w-50 rounded-3">
        <h2 class="text-center text-white">
            You are assigned at {{ fake()->city() }} on {{ \Illuminate\Support\Carbon::parse(fake()->dateTimeBetween('now', '+3 days'))->toDateString() }}. Notify the HR your availability to your assigned deployment
        </h2>
        <div class="row mx-3">
            <button class="btn btn-yellow w-full col-6 mb-2 mx-1">Available</button>
            <button class="btn btn-red w-full col-6 mb-2 mx-1">Not Available</button>
        </div>
    </div>
</div>
@endsection
