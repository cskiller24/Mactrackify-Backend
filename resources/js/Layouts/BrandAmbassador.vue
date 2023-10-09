<template>
    <div class="page">
    <div class="page-wrapper">
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="" class="h2">
                        Mactrackify
                    </a>
                </h1>

                <div class="navbar-nav flex-row d-lg-none">
                    <div class="nav-item">
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Hello
                                {{ $page.props.user.first_name }}!</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="">Settings</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="post">
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        @foreach ($dashboard as $item)
                        <li @class(['nav-item', 'active' => route_named($item['link'])])>
                            <a class="nav-link" href="{{ $item['link'] }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="text-lg ti ti-{{ $item['icon'] }} icon"></i>
                                </span>
                                <span class="nav-link-title">
                                    {{ $item['name'] }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </aside>
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col ms-1">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            @yield('pre-title', 'Brand Ambassador')
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
                                    {{  }}!</a>
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
</template>
