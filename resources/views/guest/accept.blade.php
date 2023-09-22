@extends('layouts.guest')

@section('title', 'Register | Mactrackify')

@section('content')
<div class="page page-center w-50">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h1 class="text-center mb-3">
                    Mactrackify
                </h1>
                <h2 class="h2 text-center mb-4">Register your account</h2>
                <form action="{{ route('invites.register', $invite->code) }}" method="POST">
                    @csrf
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <div class="text-muted">{{ session('status') }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" @class(['form-control', 'is-invalid'=> $errors->has('middle_name')])
                        required autofocus required>
                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" @class(['form-control', 'is-invalid'=> $errors->has('middle_name')])
                        autofocus>
                        <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" @class(['form-control', 'is-invalid'=> $errors->has('last_name')])
                        required autofocus required>
                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" @class(['form-control', 'is-invalid'=> $errors->has('email')])
                        required autofocus value="{{ $invite->email }}" readonly>
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="{{ $invite->role }}" selected>{{ $invite->formatted_role }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" @class(['form-control', 'is-invalid'=> $errors->has('password')])
                        placeholder="Your password" autocomplete="password" required autofocus>
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirm password" autocomplete="password" required autofocus>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
