@extends('layouts.team-leader')

@section('title', 'Data')

@section('pre-title', 'Data')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>
    <div class="col">
        <div class="mx-3 d-flex align-items-center">
            <label for="data-date-range" class="form-label me-2">Range</label>
            <input type="text" name="daterange" class="form-control daterange" id="data-date-range" autocomplete="off">
        </div>
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Data: <b>100</b>
        </div>

        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#team-create-modal">
            <i class="ti ti-file-export icon"></i>
            Export to XLXS
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
                <th scope="col">Customer Name</th>
                <th scope="col">Customer Contact</th>
                <th scope="col">Brand Ambassador Name</th>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Promo</th>
                <th scope="col">Quantity</th>
                <th scope="col">Created at</th>
                <th scope="col">Signature</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 100; $i++)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ fake()->name() }}</td>
                <td>{{ fake()->randomElement([fake()->unique()->email(), fake()->mobileNumber()]) }}</td>
                <td>{{ fake()->name() }}</td>
                <td>{{ fake()->randomElement(['Marlboro Red', 'Hope', 'Marlboro Blue', 'Marlboro Black', 'Winston']) }}</td>
                <td class="text-center">{{ mt_rand(1, 50) }}</td>
                <td>{{ fake()->randomElement(['Tumbler', 'Umbrella', 'Key Chain', 'Earphones']) }}</td>
                <td class="text-center">{{ mt_rand(1, 5) }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse(fake()->dateTimeBetween('-1 day'))->diffForHumans() }}</td>
                <td class="text-center">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="View Signature">
                        <i class="ti ti-eye icon" ></i>
                    </a>
                </td>
            </tr>
            @endfor

        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
    $('#data-date-range').daterangepicker({
    });
</script>
@endsection
