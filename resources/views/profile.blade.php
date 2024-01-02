@extends('layouts.'.$layout)

@section('title', 'Profile')

@section('page-title', 'Profile')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="text-center">Profile Page</h1>
        <form class="row" method="POST" enctype="multipart/form-data" >
            @csrf
            @method('PUT')
            <div class="col-4">
                <div class="border text-center  border-dark p-2 rounded">
                    <img src="{{ $user->profile_link }}" alt="Profile picture" width="200" height="200">
                </div>
                <div class="mt-2">
                    <input type="file" name="profile_image" id="profile_image" class="form-control" title="Change profile picture">
                </div>
            </div>
            <div class="col-8">
                <div class="mb-2">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="" placeholder="First Name" class="form-control" value="{{ $user->first_name }}">
                </div>
                <div class="mb-2">
                    <label for="first_name" class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" id="" placeholder="Middle Name" class="form-control" value="{{ $user->middle_name }}">
                </div>
                <div class="mb-2">
                    <label for="first_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="" placeholder="Last Name" class="form-control" value="{{ $user->last_name }}">
                </div>
                <div class="mt-3">
                    <input type="submit" class="btn btn-outline-success w-100" value="Update Profile">
                </div>
            </div>
            <div class="mt-2">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" id="email" disabled placeholder="Email" value="{{ $user->email }}" class="form-control">
            </div>
            <div class="mt-2">
                <label for="email" class="form-label">Role</label>
                <input type="text" name="email" id="email" disabled placeholder="Email" value="{{ str_replace('_', ' ', str($user->roles->first()->name)->title()) }}" class="form-control">
            </div>
        </form>
    </div>
</div>

<div class="card mt-2">
    <div class="card-body">
        <h1 class="text-center">
            Change Password
        </h1>
        <form action="{{ route('profile.update.password') }}" method="post">
            @method('PUT')
            @csrf
            <div class="mb-2">
                <label for="current_password" class="form-label">Current password</label>
                <input type="password" name="current_password" id="current_password" class="form-control">
            </div>
            <div class="mb-2">
                <label for="new_password" class="form-label">New password</label>
                <input type="password" name="new_password" id="new_password" class="form-control">
            </div>
            <div class="mb-2">
                <label for="new_password_confirmation" class="form-label">Confirm new password</label>
                <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
            </div>
            <input type="submit" value="Change password" class="btn btn-outline-primary w-100 mt-2">
        </form>
    </div>
</div>
@endsection
