@extends('layouts.brand-ambassador')

@section('pre-title', 'Brand Ambassador')

@section('content-header')

@endsection
@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1>Location Tracking</h1>
    </div>

    <div class="col-12 d-flex justify-content-center">
        <img src="{{ asset('SampleMap.png') }}" alt="Sample Map" class="border border-2 border-dark">
    </div>
    <div class="mx-5 col d-flex justify-content-center">
        <button class="btn btn-success w-50 mt-2" id="enable-tracking">Enable Tracking</button>
        <button class="btn btn-danger w-50 mt-2 d-none" id="disable-tracking">Disable Tracking</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#enable-tracking').on('click', function () {
        $('#enable-tracking').addClass('d-none')
        $('#disable-tracking').removeClass('d-none')
    })

    $('#disable-tracking').on('click', function () {
        $('#disable-tracking').addClass('d-none')
        $('#enable-tracking').removeClass('d-none')
    })
</script>
@endsection
