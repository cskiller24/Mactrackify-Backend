@extends('layouts.admin')

@section('title', 'Invites')

@section('pre-title', 'Invitations')

@section('content-header')
    <div class="row">
        <div class="col">
            <x-search />
        </div>

        <div class="col-6 d-flex justify-content-end align-items-center">
            <div class="fs-3 mx-3">
                Total Invitations: 10000
            </div>
            <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#invite-create-modal">
                <i class="ti ti-plus icon"></i>
                Invite
            </a>
        </div>
    </div>
@endsection

@section('content')
<table class="table table-striped">
  <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Email</th>
            <th scope="col">Code</th>
            <th scope="col">Role</th>
            <th scope="col">Actions</th>
        </tr>
  </thead>
  <tbody>
    @forelse ($invites as $invite)
        <tr>
            <td scope="row">{{ $invite->id }}</td>
            <td>{{ $invite->email }}</td>
            <td>{{ $invite->code }}</td>
            <td>{{ $invite->role }}</td>
            <td>
                @include('admin.components.invitation-action', ['invite' => $invite])
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6">
                <h1 class="mt-4 text-center">No entries found.</h1>
            </td>
        </tr>
    @endforelse
  </tbody>
</table>
@endsection

@section('modals')
<div class="modal fade" id="invite-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.invites.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter the email">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="rol" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>-- Select Role --</option>
                            @foreach (\App\Models\User::rolesList() as $role)
                                <option value="{{ $role }}">{{ Str::title(str_replace('_', ' ', $role)) }}</option>
                            @endforeach
                        </select>
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
@forelse ($invites as $invite)
    @include('admin.components.invite-edit', ['invite' => $invite])
@empty

@endforelse

@endsection
