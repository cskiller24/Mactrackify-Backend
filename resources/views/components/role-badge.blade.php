@props(['role'])

@if ($role === \App\Models\User::ADMIN)
    <span class="badge bg-blue">Admin</span>
@endif

@if ($role === \App\Models\User::TEAM_LEADER)
    <span class="badge bg-teal">Team Leader</span>
@endif

@if ($role === \App\Models\User::BRAND_AMBASSADOR)
    <span class="badge bg-green">Brand Ambassador</span>
@endif
