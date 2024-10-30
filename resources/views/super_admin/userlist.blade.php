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
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('storage/images/profile_pictures/default.jpg') }}" 
                                 alt="Profile picture of {{ $user->first_name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        </td>
                        <td>
                            <a href="{{ route('profile.view', $user->id) }}" class="participant-name" style="color: #001e54; text-decoration: none;">
                                {{ Str::limit($user->first_name . ' ' . $user->last_name, 16) }}
                            </a>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{ $user->role->role_name }}</td>
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

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: #333; /* Darker text color for better readability */
    font-size: 0.9rem; /* Slightly smaller font for compact look */
    margin: 10px 0; /* Space between elements */
}

#evaluationFormsTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060; /* Dark blue text color */
    font-size: 0.9rem; /* Smaller font for cleaner look */
}

#evaluationFormsTable th, #evaluationFormsTable td {
    text-align: center;
    vertical-align: middle;
    padding: 10px;
    border-bottom: 1px solid #e0e0e0; /* Light grey bottom border only */
}

/* Header Styling */
#evaluationFormsTable th {
    background-color: #f7f8fa; /* Softer light grey for the header */
    font-weight: 600; /* Slightly bolder for visibility */
    color: #333; /* Darker text for header */
}

/* Alternating Row Colors */
#evaluationFormsTable tbody tr:nth-child(odd) {
    background-color: #fafbfc; /* Very light grey for odd rows */
}

#evaluationFormsTable tbody tr:nth-child(even) {
    background-color: #ffffff; /* White for even rows */
}

/* Hover Effect */
#evaluationFormsTable tbody tr:hover {
    background-color: #f0f4ff; /* Light blue tint on hover */
}

/* Cleaner Pagination Styling */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none;
    color: #002060; /* Dark blue text */
    border: none;
    font-size: 0.85rem;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
    transition: color 0.2s ease;
}

/* Pagination Hover and Active Style */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #004080; /* Slightly darker blue on hover */
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff; /* White text for active page */
    background-color: #002060; /* Dark blue background for active page */
    border-radius: 5px;
}

/* Remove Pagination Focus Outline */
.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    box-shadow: none;
}

/* Styling for Dropdown (entries selection) */
.dataTables_wrapper .dataTables_length select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: transparent;
    border: none;
    font-size: inherit;
    color: #333;
    padding: 0;
    margin: 0;
}

/* Styling for Search Box */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 0.9rem;
    color: #333;
    background-color: #fafafa;
    outline: none;
    transition: border-color 0.2s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #004080; /* Slightly darker border on focus */
}

    
    .dataTables_wrapper .dataTables_length select {
    appearance: none;          /* Remove default styling */
    -webkit-appearance: none;   /* Remove default styling in Safari */
    -moz-appearance: none;      /* Remove default styling in Firefox */
    background-image: none;     /* Ensure no background arrow image */

    margin: auto;
}

    /* Minimalist Pagination Styling */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none; /* No background */
    color: #002060; /* Dark blue text */
    border: none;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
    font-weight: normal;
    transition: color 0.3s ease; /* Smooth color transition on hover */
    outline: none; /* Remove focus outline */
}

/* Hover Effect for Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #ffffff; /* White text on hover */
    background: none; /* Ensure no background on hover */
}

/* Active Page Style */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff; /* White text for active page */
    font-weight: bold; /* Bold for the active page */
    background: none; /* Ensure no background */
    outline: none; /* Remove outline */
    box-shadow: none; /* Remove any default shadow */
}

/* Remove hover effect on active page */
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: none; /* No background on hover for active page */
    color: #ffffff; /* Keep white text for consistency */
    box-shadow: none; /* No shadow */
}

/* Remove focus outline on click */
.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    background: none;
    box-shadow: none;
}

/* General Table Styling */
#userDataTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060;
    font-size: 0.9rem;
}

#userDataTable th, #userDataTable td {
    text-align: center;
    vertical-align: middle;
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;
}

/* Header Styling */
#userDataTable th {
    background-color: #f7f8fa;
    font-weight: bold;
    color: #333;
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

/* Dropdown Styling */
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

/* Pagination and Button Styling */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none;
    color: #002060;
    border: none;
    font-size: 0.85rem;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #004080;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff;
    background-color: #002060;
    border-radius: 5px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    box-shadow: none;
}

.page-item.active .page-link {
    background-color: #ffffff !important;
    border-color: #001e54 !important;
    color: #001e54 !important;
}
</style>
@endsection
