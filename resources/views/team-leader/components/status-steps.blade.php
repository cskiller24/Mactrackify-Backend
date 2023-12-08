@php
    $statuses = ['Pending', 'Partially Paid', 'Fully Paid', 'Released', 'Completed'];
    $currentStatus = $transaction->status;
@endphp
<div class="card mb-3">
    <div class="card-body">
        <div class="text-center">
            <div class="steps mt-3 mb-2">
                @foreach ($statuses as $status)
                    <span href="#" class="step-item {{ $currentStatus == $status ? 'active' : '' }}">
                        <i class="ti ti-confetti icon"></i>
                        {{ $status }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>
