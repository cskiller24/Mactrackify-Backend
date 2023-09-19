@extends('layouts.brand-ambassador')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Data: 10000
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

@section('modals')
<div class="modal fade" id="ba-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="team_leader_name" class="form-label">Team Leader Name</label>
                        <input type="text" name="team_leader_name" id="team_leader_name" class="form-control form-disabled" readonly value="Juan Dela Cruz">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="brand_ambassador_name" class="form-label">Brand Ambassador Leader Name</label>
                        <input type="text" name="brand_ambassador_name" id="brand_ambassador_name" class="form-control form-disabled" readonly value="John Doe">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="customer_contact" class="form-label">Customer Contact</label>
                        <input type="text" name="customer_contact" id="customer_contact" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <select name="product_name" id="product_name" class="form-select">
                            <option value="Marlboro">Marlboro</option>
                            <option value="Winston">Winston</option>
                            <option value="Camel">Camel</option>
                            <option value="Pall Mall">Pall Mall</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="product_quantity" class="form-label">Product Quantity</label>
                        <input name="product_quantity" type="number" id="product_quantity" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="promo_name" class="form-label">Promo Name</label>
                        <select name="promo_name" id="promo_name" class="form-select">
                            <option value="Tumbler">Tumbler</option>
                            <option value="Mug">Mug</option>
                            <option value="Umbrella">Umbrella</option>
                            <option value="Earphone">Earphone</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="promo_quantity" class="form-label">Promo Quantity</label>
                        <input name="promo_quantity" type="number" id="promo_quantity" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>
@endsection
