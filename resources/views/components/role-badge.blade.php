@if ($user->isAdmin())
    <span class="badge bg-blue">Admin</span>
@endif

@if ($user->isTeamLeader())
    <span class="badge bg-teal">Team Leader</span>
@endif

@if ($user->isBrandAmbassador())
    <span class="badge bg-green">Brand Ambassador</span>
@endif

@if ($user->isHumanResource())
    <span class="badge bg-gray-500">Human Resource</span>
@endif
