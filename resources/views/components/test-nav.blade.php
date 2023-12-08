@php
$admin = [
    [
        'name' => 'Invites',
        'icon' => 'send',
        'link' => route('admin.invites')
    ],
    [
        'name' => 'Teams',
        'icon' => 'users',
        'link' => route('admin.teams')
    ],
];

$teamLeader = [
    [
        'name' => 'Deployees',
        'icon' => 'users-group',
        'link' => route('team-leader.brand_ambassadors')
    ],
    [
        'name' => 'Data',
        'icon' => 'file-info',
        'link' => route('team-leader.data')
    ],
    [
        'name' => 'Tracking',
        'icon' => 'location-search',
        'link' => route('team-leader.tracking')
    ]
];

$brandAmbassador = [
    [
        'name' => 'Data',
        'icon' => 'file-database',
        'link' => route('brand-ambassador.data')
    ],
    [
        'name' => 'Tracking',
        'icon' => 'map-pin',
        'link' => route('brand-ambassador.tracking')
    ],
    [
        'name' => 'Schedule / Availability',
        'icon' => 'calendar-stats',
        'link' => route('brand-ambassador.schedule')
    ],
];

$humanResource = [
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
];
@endphp
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
                        {{ auth()->user()->name ?? 'Admin' }}!</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="">Settings</a>
                        <div class="dropdown-divider"></div>
                        <form action="" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <div class="hr-text text-light mt-3 mb-1">Admin</div>
                @foreach ($admin as $item)
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

                <div class="hr-text text-light mt-3 mb-1">Deployer</div>
                @foreach ($teamLeader as $item)
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

                <div class="hr-text text-light mt-3 mb-1">Deployee</div>
                @foreach ($brandAmbassador as $item)
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

                <div class="hr-text text-light mt-3 mb-1">Human Resource</div>
                @foreach ($humanResource as $item)
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
