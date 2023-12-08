@extends('layouts.admin')

@section('title', 'Account')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Account: {{ $total }}
        </div>
        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#account-create-modal">
            <i class="ti ti-plus icon"></i>
            Account
        </a>
    </div>
</div>
@endsection

@section('content')
<table class="table table-striped">
    <thead>
          <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Account Number</th>
              <th scope="col">Actions</th>
          </tr>
    </thead>
    <tbody>
        @forelse ($accounts as $account)
            <tr>
                <td scope="row">{{ $account->id }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->number }}</td>
                <td></td>
            </tr>
        @empty
            <tr>
                <td colspan="4">
                    <h1 class="mt-4 text-center">No entries found.</h1>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection

@section('modals')
<div class="modal fade" id="account-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.accounts.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter the account number">
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
@endsection