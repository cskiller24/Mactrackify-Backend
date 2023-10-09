<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ $modalAction }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ $team->name }}" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter the location" value="{{ $team->location }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="team_leader" class="form-label">Team Leader</label>
                        <select name="team_leader" id="team_leader" class="form-select">
                            <option value="" selected disabled>-- Select Team Leader --</option>
                            @foreach ($teamLeaderCreate as $teamLeader)
                                <option value="{{ $teamLeader->id }}" @selected($team->leaders->contains($teamLeader->id))>{{ $teamLeader->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="ba_select" class="form-label">Brand Ambassadors</label>
                        <div class="row">
                            @foreach ($brandAmbassadorCreate as $brandAmbassador)
                            <div class="col-4 d-flex">
                                <input type="checkbox" name="brand_ambassador[]" class="form-check me-1" id="ba_{{ $brandAmbassador->id }}" value="{{ $brandAmbassador->id }}">
                                <label for="ba_{{ $brandAmbassador->id }}" class="form-check-label align-middle">{{ $brandAmbassador->full_name }}</label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
