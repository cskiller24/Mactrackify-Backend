@extends('layouts.brand-ambassador')

@section('title', 'Sales Data')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Data: {{ $sales?->count() ?? 0 }}
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
                <th scope="col">Deployee Name</th>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Promo</th>
                <th scope="col">Quantity</th>
                <th scope="col">Created at</th>
                <th scope="col">Signature</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->customer_name }}</td>
                <td>{{ $sale->customer_contact }}</td>
                <td>{{ auth()->user()->full_name }}</td>
                <td>{{ $sale->product }}</td>
                <td class="text-center">{{ $sale->product_quantity }}</td>
                <td>{{ $sale->promo }}</td>
                <td class="text-center">{{ $sale->promo_quantity }}</td>
                <td>{{ $sale->created_at->diffForHumans() }}</td>
                <td class="text-center">
                    <a href="{{ route('download', $sale->signature_url) }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="View Signature">
                        <i class="ti ti-eye icon" ></i>
                    </a>
                </td>
            </tr>
            @empty
                <h1>Empty Sales</h1>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('modals')
<div class="modal fade" id="ba-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('brand-ambassador.data.store') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="hidden" name="team_leader_id" value="{{ $teamLeader->id }}">
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <label for="team_leader_name" class="form-label">Deployer Name</label>
                        <input type="text" name="team_leader_name" id="team_leader_name" class="form-control form-disabled" readonly value="{{ $teamLeader->full_name }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="brand_ambassador_name" class="form-label">Deployee Leader Name</label>
                        <input type="text" name="brand_ambassador_name" id="brand_ambassador_name" class="form-control form-disabled" readonly value="{{ auth()->user()->full_name }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="customer_contact" class="form-label">Customer Contact</label>
                        <input type="text" name="customer_contact" id="customer_contact" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="customer_age" class="form-label">Customer Age</label>
                        <input type="text" name="customer_age" id="customer_age" class="form-control" required pattern="^(1[8-9]|[2-9]\d+)$" placeholder="Please enter the age (must be 18 above)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="product" class="form-label">Product Name</label>
                        <select name="product" id="product" class="form-select">
                            <option value="Product A">Product A</option>
                            <option value="Product B">Product B</option>
                            <option value="Product C">Product C</option>
                            <option value="Product D">Product D</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="product_quantity" class="form-label">Product Quantity</label>
                        <input name="product_quantity" type="number" id="product_quantity" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="promo" class="form-label">Promo Name</label>
                        <select name="promo" id="promo" class="form-select">
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
                    <div class="col-12 mb-3">
                        <label for="signature" class="form-label">Signature</label>
                        <input name="signature" type="file" id="signature" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
