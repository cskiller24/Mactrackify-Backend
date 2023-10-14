@if($deployment)
    @if($deployment->isAccepted())
    <span class="badge bg-green">Accepted</span>

    @elseif ($deployment->isDeclined())
    <span class="badge bg-red">Declined</span>

    @elseif($deployment->isNoResponse())
    <span class="badge bg-yellow">No response</span>
    @endif
@else
<span class="badge bg-gray">Unknown</span>
@endif
