@extends('layouts.admin')

@section('title', 'Teams')

@section('pre-title', 'Teams')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Teams: 10000
        </div>
        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#team-create-modal">
            <i class="ti ti-plus icon"></i>
            Team
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="accordion" id="accordionExample">
    @for ($i = 0; $i < 10; $i++)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $i }}" aria-expanded="true" aria-controls="collapseOne{{ $i }}">
            {{ fake()->words(mt_rand(3,5), true).' - '.fake()->words(mt_rand(3,5), true) }}
          </button>
        </h2>
        <div id="collapseOne{{ $i }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($j = 0; $j < rand(5, 20); $j++)
                        <tr>
                            <td>{{ $j + 1 }}</td>
                            <td>{{ fake()->name() }}</td>
                            <td><x-role-badge role="{{$j === 0 ? 'team_leader' : 'brand_ambassador'}}" /></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endfor
@endsection

@section('modals')
<div class="modal fade" id="team-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter the location">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="role" class="form-label">Team Leader</label>
                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>-- Select Team Leader --</option>
                            <option value="admin">Juan Dela Cruz</option>
                            <option value="team_leader">Jane Doe</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="ba_select" class="form-label">Brand Ambassadors</label>
                        <div class="row">
                            @for ($i = 0; $i < rand(5, 10); $i++)
                            <div class="col-4 d-flex">
                                <input type="checkbox" name="brand_ambassador[]" class="form-check me-1" id="ba_{{ $i }}">
                                <label for="ba_{{ $i }}" class="form-check-label align-middle">{{ fake()->name() }}</label>
                            </div>
                            @endfor
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
@endsection
