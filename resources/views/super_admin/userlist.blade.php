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
<div style="display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 50px; margin-left: 20px;">
    <a href="{{ route('superadmin.usercreate') }}" class="btn custom-btn-primary" style="width: 100% !important; margin-bottom: 10px !important;">
        <i class="fas fa-plus"></i> Add User
    </a>
    <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="width: 100%;">
        <button type="submit" class="btn custom-btn-primary" style="width: 100% !important;">
            <i class="fas fa-user-shield"></i> View Requesting Admin
        </button>
    </form>
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

.custom-btn-light,
.custom-btn-primary {
    background-color: #001e54 !important; /* Dark blue background */
    color: white !important; /* White text color */
    border-radius: 15px !important; /* Rounded corners */
    padding: 12px 20px !important; /* Adequate padding */
    font-size: 16px !important; /* Font size */
    font-weight: bold !important; /* Bold text */
    text-align: center !important; /* Center the text */
    display: flex !important; /* Flexbox for alignment */
    align-items: center !important; /* Center items vertically */
    justify-content: center !important; /* Center items horizontally */
    border: none !important; /* Remove border */
    transition: background-color 0.3s, transform 0.3s !important; /* Smooth transition for hover effect */
    max-width: 200px !important; /* Limit maximum width for larger screens */
}

/* Ensure buttons fill their parent container */
.custom-btn-primary {
    width: 100% !important; /* Full width for mobile */
}

/* Hover effect for the button */
.custom-btn-primary:hover {
    background-color: #004080 !important; /* Darker shade for hover */
    transform: translateY(-2px) !important; /* Slight lift effect on hover */
    color: #ffff !important; 
}

/* Active state effect */
.custom-btn-primary:active {
    transform: translateY(1px) !important; /* Slight dip effect on click */
}

/* Media query for responsiveness */
@media (max-width: 768px) {
    .custom-btn-primary {
        padding: 15px !important; /* Increased padding for easier tapping */
        font-size: 18px !important; /* Slightly larger font size for better readability */
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
.page-item.active .page-link {
    background-color: #ffffff !important;
    border-color: #001e54 !important;
    color: #001e54 !important;
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

<style>
.page-item.active .page-link {
  background-color: transparent !important;
  border-color: transparent !important;
  color: inherit !important;
  font-weight: normal !important;
}

.pagination {
  margin-top: 20px !important;
  padding-bottom: 40px !important;
  display: flex !important;
  justify-content: center !important;
  list-style-type: none !important;
  margin-top: 10px !important;
  padding: 0 !important;
  align-items: center !important;
  margin-left: auto !important;
  margin-right: auto !important;
}

.pagination a, .pagination span {
  display: inline-block !important;
  color: grey !important;
  text-decoration: none !important;
  background-color: transparent !important;
  border: none !important;
  padding: 10px 15px !important;
  margin: 0 5px !important;
  font-weight: 600 !important;
  font-size: 1rem !important;
}

.pagination .active span {
  background-color: darkblue !important;
  color: white !important;
  font-weight: 800 !important;
  border-radius: 50% !important;
}

.pagination a:hover {
  background-color: lightgray !important;
  color: white !important;
  border-radius: 50% !important;
}

@media (max-width: 768px) {
  .pagination {
    justify-content: center !important;
    margin: auto !important;
  }

  .pagination a, .pagination span {
    padding: 6px 8px !important;
    font-size: 0.85rem !important;
    margin: 0 3px !important; /* Closer numbers */
  }

  .pagination .active span {
    font-weight: 700 !important;
  }
}
</style>

<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized and destroy it if so
        if ($.fn.dataTable.isDataTable('#userDataTable')) {
            $('#userDataTable').DataTable().destroy();
        }

        const table = $('#userDataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10,
            "pagingType": "full_numbers", // Show full pagination (numbers)
            "iDisplayLength": 10,  // Items per page
            "fnDrawCallback": function (settings) {
                // Check screen size and apply custom pagination for mobile and PC
                var api = this.api();
                var currentPage = api.page.info().page + 1;  // Get current page number
                var totalPages = api.page.info().pages;     // Get total pages

                 // On Mobile: Show only 3 pages (Previous, Current, Next)
            if ($(window).width() <= 768) {
                var pageNumbers = [];
                if (currentPage === 1) {
                    pageNumbers = [1, 2, 3];  // Show pages 1, 2, 3 when on the first page
                } else if (currentPage === totalPages) {
                    pageNumbers = [totalPages - 2, totalPages - 1, totalPages];  // Show last 3 pages
                } else {
                    pageNumbers = [currentPage - 1, currentPage, currentPage + 1];  // Show 3 pages around the current page
                }

                // Hide pages that are not part of the range for mobile
                $('.dataTables_paginate .paginate_button').each(function() {
                    var pageNumber = $(this).text();
                    if (!pageNumbers.includes(parseInt(pageNumber)) && pageNumber !== "«" && pageNumber !== "»") {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                // Show the "Previous" and "Next" buttons
                $('.dataTables_paginate .paginate_button.previous').show();
                $('.dataTables_paginate .paginate_button.next').show();

            } else {
                // On PC: Show all page numbers
                $('.dataTables_paginate .paginate_button').show();

                // Hide "Previous" and "Next" if we're on the first or last page
                $('.dataTables_paginate .paginate_button.previous').toggle(currentPage > 1); // Show 'Previous' if not on the first page
                $('.dataTables_paginate .paginate_button.next').toggle(currentPage < totalPages); // Show 'Next' if not on the last page
            }
        }
    });

        // Role filter event handler
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


@endsection
