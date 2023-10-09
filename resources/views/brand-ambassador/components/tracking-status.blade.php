@if($tracking)
    @if($tracking->is_authentic)
    <span class="badge bg-green">Genuine</span>
    @else
    <span class="badge bg-red">Spoofed</span>
    @endif
@else
<span class="badge bg-gray">Unknown</span>
@endif

