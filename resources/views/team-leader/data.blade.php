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
            Total Data: <b>{{ $sales->count() ?? 0 }}</b>
        </div>

        <form action="{{ route('team-leader.data.export') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="ti ti-file-export icon"></i>
                Export to XLXS
            </button>
        </form>
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
            @forelse ($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->customer_name }}</td>
                <td>{{ $sale->customer_contact }}</td>
                <td>{{ 'John Doe' }}</td>
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

@section('scripts')
<script>
    $('#data-date-range').daterangepicker({
    });
</script>
@endsection
