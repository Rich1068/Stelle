@extends('layouts.app')

@section('body')
<div class="top-container mb-4 d-flex align-items-left">
    <div class="d-flex align-items-right">
        <h2 class="font-weight-bold mb-0">
            <i class="fas fa-users"></i> User List
        </h2>
    </div>
    <div class="add-user-buttons ms-auto" style="display: flex; gap: 10px; margin-left: auto;">
        <a href="{{ route('superadmin.usercreate') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
        <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="display: inline;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-shield"></i> View Requesting Admin
            </button>
        </form>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered" width="70%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            @if($user->profile_picture == null)
                                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54;">
                            @else
                                <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54;">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('profile.view', $user->id) }}" class="participant-name" style="color: #001e54; text-decoration: none;">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </a>
                        </td>
                        <td>{{ $user->role->role_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Load DataTables scripts -->




@endsection
