<div>
    <a href="{{ route('admin.invites.resend', $invite->code) }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Resend Invitation">
        <i class="ti ti-send icon"></i>
    </a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#inv_{{$invite->code}}" title="Edit">
        <i class="ti ti-pencil icon" ></i>
    </a>
    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="ti ti-trash text-red icon" ></i>
    </a>
</div>
