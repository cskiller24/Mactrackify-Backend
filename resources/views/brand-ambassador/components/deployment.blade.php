@if(in_array($type, ['today', 'tommorow']))
<div class="border border-dark rounded p-2 mb-3">
    <h1 class="text-center">{{ ucfirst($type) }} Deployment</h1>
    @if($deployment)
        @if($deployment->isNoResponse())
            <h2 class="text-center my-2">You have deployment for {{ $type }} ({{ $deployment->date }}). Please update if you are available or not.</h2>

            <form action="{{ route('brand-ambassador.schedule.update', $deployment->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ \App\Models\Deployment::ACCEPTED }}">
                <input type="submit" value="Available" class="btn btn-outline-success w-100 mb-2">
            </form>

            <form action="{{ route('brand-ambassador.schedule.update', $deployment->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ \App\Models\Deployment::DECLINED }}">
                <input type="submit" value="Not Available" class="btn btn-outline-danger w-100 mb-2">
            </form>


        @endif
        @if($deployment->isAccepted())
            <h2 class="text-center">You accepted the deployment for {{ $type }}. Please be ready by {{ $deployment->date }}</h2>
        @endif
        @if($deployment->isDeclined())
            <h2 class="text-center">You declined the deployment for {{ $type }}. The roster will be replaced by HR.</h2>
        @endif
    @else
    <h2 class="text-center my-2">You have no deployment for today. You can still let our HR know that you are available by updating your status.</h2>
    <form class="form-group" action="{{ route('brand-ambassador.schedule.status.update') }}" method="POST">
        @csrf
        @method('PUT')
        <label for="status" class="form-label">
            Current status
        </label>
        <select name="status" id="">
            @foreach (\App\Models\Status::listStatuses() as $status)
                <option value="{{ $status }}" @selected($deployment->user->latestStatus === $status) >{{ str(Status::NOT_AVAILABLE)->title()->replace('_', ' ') }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>
@else
<h1>ERROR DEPLOYMENT TYPE IN DEPLOYMENT COMPONENT ONLY TODAY OR TOMMOROW IS ACCEPTED</h1>
@endif


