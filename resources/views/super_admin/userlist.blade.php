@extends('layouts.app')

@section('body')
<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none; margin-bottom: 100px;">
    <!-- Left: User List Title -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> User List
        </h2>
    </div>
</div>

<!-- Custom Styles for this Page -->
<style>
.custom-btn-primary,
.custom-btn-light {
    outline: none; /* Remove focus outline */
    box-shadow: none; /* Remove any box shadow */
}

/* Keep the existing styles */
.custom-btn-primary {
    background-color: #001e54;
    color: white;
    border-radius: 20px; /* Rounded corners */
    padding: 15px;
    font-size: 15px;
    font-weight: bold;
    text-align: center; /* Center text */
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
}

.custom-btn-primary:hover {
    background-color: #004080; /* Darker shade for hover */
}

.custom-btn-light {
    background-color: transparent; /* Transparent background */
    color: #002060; /* Custom text color */
    border: none; /* Remove border */
}

.custom-btn-light:hover {
    color: #004080; /* Darker shade on hover */
}
</style>

<div class="card mb-4" style="margin-top: 50px; border: none;"> <!-- Removed shadow and added border: none; -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
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

<div style="display: flex; align-items: center; margin-bottom: 50px; margin-left: 20px;">
    <a href="{{ route('superadmin.usercreate') }}" class="btn custom-btn-primary" style="border-radius: 15px; padding: 10px 20px; margin-right: 10px; font-size: 16px;">
        <i class="fas fa-plus"></i> Add User
    </a>
    <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="display: inline;">
        <button type="submit" class="btn custom-btn-primary" style="border-radius: 15px; padding: 10px 20px; font-size: 16px;">
            <i class="fas fa-user-shield"></i> View Requesting Admin
        </button>
    </form>
</div>

@endsection
