@if($status)
    @if ($status->isAvailable())
    <span class="badge bg-yellow">Available</span>

    @elseif($status->isNotAvailable())
    <span class="badge bg-red">Not Available</span>

    @elseif($status->isPending())
    <span class="badge bg-primary">Pending</span>
    @endif
@else

<span class="badge bg-yellow">No response</span>
@endif
