@php
    $rand = mt_rand(1, 3)
@endphp
@if($rand === 1)
<span class="badge bg-green">Currently Deployed</span>
@elseif ($rand === 2)
<span class="badge bg-red">Unavailable</span>
@elseif ($rand === 3)
<span class="badge bg-yellow">On a break (Tracking Off)</span>
@endif
