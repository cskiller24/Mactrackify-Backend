<div class="modal fade" id="inv_{{$invite->code}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.invites.update', $invite->code) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Update Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" value="{{ $invite->email }}" class="form-control" placeholder="Enter the email">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="rol" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            @foreach (\App\Models\User::rolesList() as $role)
                                <option value="{{ $role }}" @selected($invite->role == $role)>{{ Str::title(str_replace('_', ' ', $role)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
