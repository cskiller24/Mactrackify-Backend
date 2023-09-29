@if($status)
    @if($status->isDeployed())
    <span class="badge bg-green">Deployed</span>

    @elseif ($status->isAvailable())
    <span class="badge bg-yellow">Not Available</span>

    @elseif($status->isNotAvailable())
    <span class="badge bg-red">Not Available</span>

    @elseif($status->isPending())
    <span class="badge bg-primary">Pending</span>

    @endif
@else
<span class="badge bg-gray">Unknown</span>
@endif
