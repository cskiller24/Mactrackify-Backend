@extends('layouts.guest')

@section('title')
Login | {{ env('APP_NAME') }}
@endsection

@section('content')
<div class="page page-center w-50">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h1 class="text-center mb-3">
                    {{ env('APP_NAME') }}
                </h1>
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <div class="text-muted">{{ session('status') }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" @class(['form-control', 'is-invalid'=> $errors->has('email')])
                        placeholder="your@email.com" required autofocus value="{{ old('email') }}">
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>

                    </div>
                    <div class="mb-3">
                            <input type="password" name="password" @class(['form-control', 'is-invalid'=> $errors->has('password')])
                            placeholder="Your password" autocomplete="password" required autofocus>
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
