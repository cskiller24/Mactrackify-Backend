@extends('layouts.team-leader')

@section('title', 'Data')

@section('pre-title', 'Data')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Data: <b>{{ $transactions->count() ?? 0 }}</b>
        </div>

        <a href="{{ route('team-leader.transactions.create') }}" class="btn btn-outline-primary">
            <i class="ti ti-plus icon"></i>
            Create Transaction
        </a>

    </div>
</div>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Transaction Code</th>
                <th scope="col">Account Name</th>
                <th scope="col">Deployee Name</th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
            <tr>
                <td>
                    <a href="{{ route('team-leader.transactions.show', $transaction->id) }}">{{ $transaction->uuid }}</a>
                </td>
                <td>{{ $transaction->account->name }}</td>
                <td>{{ $transaction->user->full_name }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ $transaction->created_at->diffForHumans() }}</td>
                <td></td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <h1 class="mt-4 text-center">No entries found.</h1>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection
