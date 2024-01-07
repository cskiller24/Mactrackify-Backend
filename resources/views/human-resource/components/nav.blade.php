@php
    $dashboard = [
        [
            'name' => 'Dashboard',
            'icon' => 'home',
            'link' => ''
        ],
        [
            'name' => 'Deployees',
            'icon' => 'users-group',
            'link' => route('human-resource.brand-ambassador')
        ],
        [
            'name' => 'Deployment',
            'icon' => 'map-pins',
            'link' => route('human-resource.deployment')
        ],
    ]
@endphp
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="" class="h2">
                Trackcertify
            </a>
        </h1>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item">
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
