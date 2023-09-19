@php
    $rand = mt_rand(1,3)
@endphp

@if($rand === 1)
    <span class="badge bg-yellow">Pending</span>
@elseif($rand === 2)
    <span class="badge bg-green">Accepted</span>
@else
    <span class="badge bg-red">Unavailable</span>
@endif
