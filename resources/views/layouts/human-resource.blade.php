@extends('layouts.app')

@section('body')
    <div class="page">
    <!-- Sidebar -->
    @include('human-resource.components.nav')
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
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
                                        {{ auth()->user()->name ?? 'Admin Name' }}!</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="">Settings</a>
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
                            @yield('content-header')
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
