@extends('layouts.admin')

@section('title', 'Invites')

@section('pre-title', 'Invitations')

@section('content-header')
    <div class="row">
        <div class="col">
            <x-search />
        </div>

        <div class="col-6 d-flex justify-content-end align-items-center">
            <div class="fs-3 mx-3">
                Total Invitations: 10000
            </div>
            <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#invite-create-modal">
                <i class="ti ti-plus icon"></i>
                Invite
            </a>
        </div>
    </div>
@endsection

@section('content')
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Code</th>
      <th scope="col">Role</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Mark@gmail.com</td>
      <td>123</td>
      <td><x-role-badge role="admin" /></td>
      <td>@include('admin.components.invitation-action')</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>124</td>
      <td><x-role-badge role="team_leader" /></td>
      <td>@include('admin.components.invitation-action')</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>bird@gmail.com</td>
      <td>125</td>
      <td><x-role-badge role="brand_ambassador" /></td>
      <td>@include('admin.components.invitation-action')</td>
    </tr>
  </tbody>
</table>
@endsection

@section('modals')
<div class="modal fade" id="invite-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter the email">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="rol" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>-- Select Role --</option>
                            <option value="admin">Admin</option>
                            <option value="team_leader">Team Leader</option>
                            <option value="brand_ambassador">Brand Ambassador</option>
                        </select>
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
