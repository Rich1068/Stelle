@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> User List
        </h2>
    </div>
</div>

<!-- Role Filter Dropdown -->
<div class="input-group mb-3 custom-select-container">
    <select id="roleFilter" class="custom-select" style="width: 200px; margin-bottom: 20px;">
        <option value="">All Roles</option>
        <option value="Super Admin">Super Admin</option>
        <option value="Admin">Admin</option>
        <option value="User">User</option>
    </select>
</div>

<div class="card mb-4" style="margin-top: 50px; border: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="userDataTable" class="table table-bordered text-center" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="d-none d-md-table-cell">Profile Picture</th> <!-- Hidden on smaller screens -->
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="d-none d-md-table-cell">
                            <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('storage/images/profile_pictures/default.jpg') }}" 
                                 alt="Profile picture of {{ $user->first_name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        </td>
                        <td>
                            <a href="{{ route('profile.view', $user->id) }}" class="participant-namee" style="color: #001e54; text-decoration: none;">
                                {{$user->first_name . ' ' . $user->last_name }}
                            </a>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{ $user->role->role_name }}</td>
                        <td>
                            @if($user->trashed())
                                <span style="color: red;" class="status">DELETED</span>
                            @elseif($user->email_verified_at == null)
                                <span style="color: gray;" class="status">NOT VERIFIED</span>
                            @else
                                <span style="color: green;" class="status">ACTIVE</span>
                            @endif
                        </td>
                        <td>
                            <div class="button-group">
                                <form action="{{ route('profile.view', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-recover rounded-circle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                                @if($user->trashed())
                                    <form action="{{ route('superadmin.recoverUser', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-recover rounded-circle" 
                                            onclick="return confirm('Are you sure you want to recover this user?')" title="Recover User">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('superadmin.destroyUser', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-delete rounded-circle" 
                                            onclick="return confirm('Are you sure you want to soft delete this user?')" title="Delete User">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#userDataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10
        });

        $('#roleFilter').on('change', function() {
            const selectedRole = $(this).val();
            if (selectedRole) {
                table.column(3).search(`^${selectedRole}$`, true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });
    });
</script>

<style>
/* Default table styles */
.table th, .table td {
    font-size: 0.9rem;
}
.participant-namee {
  font-size: 1.2em; /* Font size for larger screens */
  color: #002f6c; /* Dark blue color */
  font-weight: bold;
  word-wrap: break-word; /* Allows long names to break onto a new line */
  white-space: normal; /* Allows text to wrap onto multiple lines */
}


/* Mobile-friendly adjustments */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 0.85rem; /* Smaller font for readability on mobile */
    }
    .button-group .btn {
        width: 30px;
        height: 30px;
        font-size: 0.75rem;
    }
    .button-group {
        flex-direction: column;
    }
    .custom-select-container {
        width: 100%;
    }
    .participant-namee {
    font-size: 1em; /* Smaller font size for mobile devices */
    }


}
.dataTables_wrapper .dataTables_length select {
    min-width: 50px; /* Adjust width as needed */
    padding: 5px; /* Add padding for better readability */
    height: auto;
    font-size: 0.9rem;
    border: 1px solid #ccc;  /* Add border to make it visible */
    background-color: #fff;
    color: #333;
    border-radius: 4px;  /* Adjusts the rounded corners */
    outline: none;  /* Removes focus outline */
}

/* Hides the profile picture column on small screens */
.d-none.d-md-table-cell {
    display: none;
}

/* Styles for larger screens */
@media (min-width: 768px) {
    .d-none.d-md-table-cell {
        display: table-cell;
    }
}

/* Dropdown adjustments */
.custom-select-container {
    width: 200px;
    margin-left: 10px;
}

.custom-select {
    border-radius: 15px;
    background-color: #001e54;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    height: 50px;
    margin: auto;
}

/* Pagination and Search Box styling */

.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none;
    color: #002060;
    border: none;
    font-size: 0.85rem;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
    transition: color 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: none;
    color: #004080;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff;
    background-color: #002060;
    border-radius: 5px;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 0.9rem;
    color: #333;
    background-color: #fafafa;
}

/* Styling for Table */
#userDataTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060;
    font-size: 0.9rem;
}

#userDataTable th, #userDataTable td {
    text-align: center;
    vertical-align: middle;
    padding: 5px;
    border-bottom: 1px solid #e0e0e0;
}

/* Header Styling */
#userDataTable th {
    background-color: #f7f8fa;
    font-weight: bold;
    color: #333;
}
#userDataTable td:nth-child(2),
#userDataTable th:nth-child(2) {
    min-width: 100px; /* Adjust width as needed */
    white-space: normal; /* Allow text to wrap */
    word-wrap: break-word;
}

/* Alternating Row Colors */
#userDataTable tbody tr:nth-child(odd) {
    background-color: #fafbfc;
}

#userDataTable tbody tr:nth-child(even) {
    background-color: #ffffff;
}

/* Hover Effect */
#userDataTable tbody tr:hover {
    background-color: #f0f4ff;
}

/* General Button Styles */
.button-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px; /* Space between buttons */
}
.button-group .btn .fas, 
.button-group .btn .fa {
    margin: auto; /* Center icon within the button */
}

.button-group .btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 15px;
    color: white;
}

/* Button Colors */
.btn-view {
    background-color: #008b8b;
}
.btn-recover {
    background-color: #008b8b;
}
.btn-edit {
    background-color: #001e54;
}
.btn-delete {
    background-color: #c9302c;
}
</style>
@endsection
