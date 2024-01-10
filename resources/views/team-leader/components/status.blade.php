@if($deployment)
    @if ($deployment->isAvailable())
    <span class="badge bg-yellow">Not Available</span>

    @elseif($deployment->isNotAvailable())
    <span class="badge bg-red">Not Available</span>

    @elseif($deployment->isNoResponse())
    <span class="badge bg-primary">Pending</span>
    @endif
@else
<span class="badge bg-yellow">No Response</span>

@endif
