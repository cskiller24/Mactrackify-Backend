@if($deployment->isNoResponse())
<a href="{{ route('human-resource.notification-send', $deployment->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send notification">
    <i class="ti ti-send"></i>
</a>
@endif

@if($deployment->isNoResponse())
<a href="{{ route('human-resource.notification-send', $deployment->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send notification">
    <i class="ti ti-send"></i>
</a>
@endif
