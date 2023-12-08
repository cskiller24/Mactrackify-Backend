@extends('layouts.team-leader')

@section('title', 'Transaction Show')

@section('page-title', 'Transaction View')

@section('pre-title', 'Team Leader')

@section('content-header')
    @include('team-leader.components.status-steps', ['transaction' => $transaction])
@endsection

@section('content')
<div class="card p-2">
    <div class="card-body d-flex justify-content-between align-items-center -center ">
        <h2>FMC Electrical & Industrial Supply</h2>
        <p>457- B <br>J. P Rizal Street, Barangay Olympia <br>Makati City 1200 Makati, Philippines</p>
    </div>
    <hr class="mx-3 my-0">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                Transaction ID: <b>{{ $transaction->uuid }}</b>
                <br>
                Account Name: <b>{{ $transaction->account->name }}</b>
            </div>
            <div class="row">
                @if ($transaction->status == 'Partially Paid' || $transaction->status == 'Pending')
                <a href="" class="col-12 mb-2 btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#add-payment-modal">
                    <i class="ti ti-plus icon"></i>
                    Add payment
                </a>
                @endif
                @if ($transaction->status == 'Fully Paid')
                <a href="" class="col-12 mb-2 btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#confirm-transaction-release">
                    <i class="ti ti-writing-sign icon"></i>
                    Confirm transaction release
                </a>
                @endif

                @if ($transaction->status == 'Released')
                <form action="{{ route('team-leader.transactions.complete', $transaction->id) }}" method="post" class="col-12 mb-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-success w-100 ">
                        <i class="ti ti-check icon"></i>
                        Mark as Complete
                    </button>
                </form>
                @endif

                @if ($transaction->status == 'Released' || $transaction->status == 'Completed')
                <a href="{{ route('team-leader.transactions.receipt', $transaction->id) }}" class="col-12 mb-2 btn btn-outline-primary" >
                    <i class="ti ti-download icon"></i>
                    Download Reciept
                </a>
                @endif

                <a href="" class="col-12 mb-2 btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#view-payment-history">
                    <i class="ti ti-eye icon"></i>
                    View payment history
                </a>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Warehouse Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->items as $item)
                    <tr>
                        <td>
                            {{ $item->warehouseItem->name }}
                        </td>
                        <td>{{ $item->warehouseItem->warehouse->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->warehouseItem->price }}</td>
                        <td>{{ intval($item->quantity) * $item->warehouseItem->price }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center ">
                            <h4 class="mb-0">Total Amount</h4>
                        </td>
                        <td colspan="1">
                            <h3 class="mb-0">{{ $totalAmount }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center ">
                            <h4 class="mb-0">Paid Amount</h4>
                        </td>
                        <td colspan="1">
                            <h3 class="mb-0">{{ $paidAmount }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center ">
                            <h4 class="mb-0">Remaining Balance</h4>
                        </td>
                        <td colspan="1">
                            <h3 class="mb-0">{{ $totalAmount - $paidAmount }}</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="add-payment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('team-leader.transactions.addBalance', $transaction->id) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Payment for {{ $transaction->uuid }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount (limit {{ $totalAmount - $paidAmount }})" max="{{ $totalAmount - $paidAmount }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="view-payment-history" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Payment for {{ $transaction->uuid }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction->collectionHistories as $collectionHistory)
                        <tr>
                            <td>{{ $collectionHistory->id }}</td>
                            <td>
                                {{ $collectionHistory->balance }}
                            </td>
                            <td>{{ $collectionHistory->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <h1 class="mt-4 text-center">No entries found.</h1>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if ($transaction->status == 'Fully Paid')
@php
    $hasDeficitQuantity = false;
@endphp
<div class="modal fade" id="confirm-transaction-release" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Payment for {{ $transaction->uuid }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Item Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Warehouse Name</th>
                            <th scope="col">Warehouse Quantity</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction->items as $transactionItem)
                        <tr>
                            <td class="text-er">{{ $transactionItem->warehouseItem->name }}</td>
                            <td>
                                {{ $transactionItem->quantity }}
                            </td>
                            <td>{{ $transactionItem->warehouseItem->name }}</td>
                            <td>{{ $transactionItem->warehouseItem->quantity }}</td>
                            @if ($transactionItem->quantity <= $transactionItem->warehouseItem->quantity)
                            <td class="text-center icon">
                                <i class="ti ti-check text-success" data-bs-toggle="tooltip" title="Approved"></i>
                            </td>
                            @else
                                @php
                                    $hasDeficitQuantity = true;
                                @endphp
                                <td class="text-center">
                                    <i class="ti ti-x icon text-danger" data-bs-toggle="tooltip" title="Cannot proceed for release if the warehouse item is deficit"></i>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <h1 class="mt-4 text-center">No entries found.</h1>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(! $hasDeficitQuantity)
                <form action="{{ route('team-leader.transactions.release', $transaction->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve release</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
