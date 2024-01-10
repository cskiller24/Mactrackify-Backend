@extends('layouts.app')

@section('body')
    <div class="page">
        <!-- Sidebar -->
        @include('admin.components.nav')
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                {{-- asdasd --}}
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col ms-1">
                            <!-- Page pre-title -->
                            <div class="page-pretitle">
                                @yield('pre-title', 'ADMIN')
                            </div>
                            <h2 class="page-title">
                                @yield('page-title', 'Dashboard')
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <a href="#" class="nav-link mx-3">Notifications</a>
                        </div>
                        <div class="col-auto ms-auto d-print-none d-none d-lg-flex">
                            <div class="btn-list">
                                <div class="dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Hello
                                        {{ auth()->user()->first_name }}!</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-12 my-1">
                            <div class="row align-items-center ">
                                @if($hasBack ?? true)
                                <div class="col-1">
                                    <a href="{{ url()->previous() ?? route('admin.index') }}" class="nav-link">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="" width="50  " height="50"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 21a9 9 0 1 0 0 -18a9 9 0 0 0 0 18" />
                                            <path d="M8 12l4 4" />
                                            <path d="M8 12h8" />
                                            <path d="M12 8l-4 4" />
                                        </svg>
                                    </a>
                                </div>
                                @endif
                                <div @class(['col-11' => $hasBack ?? true, 'col-12' => $hasBack ?? false])>
                                    @yield('content-header')
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-1">
                            @yield('content')
                        </div>
                        <div class="col-12 my-1">
                            @yield('content-footer')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
