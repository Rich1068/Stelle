@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none; margin-bottom: 100px;">
    <!-- Left: User List Title -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> User List
        </h2>
    </div>
</div>

<div class="input-group mb-3 form-control-container">
    <input type="text" id="userSearch" class="form-control" placeholder="Search for users...">
    <div class="input-group-append">
        <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<div class="input-group mb-3 custom-select-container">
    <div id="roleDropdown" class="custom-select" tabindex="0">
        <span id="selectedRole" class="selected">All</span> 
        <div class="arrow" id="dropdownArrow"></div>
        <div class="options">
            <div class="option" data-value="All">All</div> 
            <div class="option" data-value="Super Admin">Super Admin</div>
            <div class="option" data-value="Admin">Admin</div>
            <div class="option" data-value="User">User</div>
        </div>
    </div>
</div>

<div class="card mb-4" style="margin-top: 50px; border: none;">
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
                                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54; object-fit: cover; display: block; margin: auto;">
                            @else
                                <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54; object-fit: cover; display: block; margin: auto;">
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
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
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


<style>
#selectedRole {
    font-weight: bold !important; /* Make the text bold */
    text-align: center !important; /* Center the text */
    display: block !important; /* Ensure it behaves like a block element */
    width: 100% !important; /* Take full width of the parent */
    margin: auto !important;
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


.custom-select-container {
    position: relative;
    width: 200px; /* Adjust the width as needed */
    margin-left: 10px; /* Add left margin */
}

.custom-select {
    border-radius: 15px; /* Rounded corners */
    background-color: #001e54; /* Dark blue background */
    color: white; /* White text */
    border: none; /* Remove border */
    padding: 10px; /* Adjust padding as needed */
    cursor: pointer; /* Change cursor to pointer */
    position: relative; /* Needed for absolute positioning of options */
    margin: auto;
    height:50px;    
}

/* Arrow styles */
.arrow {
    position: absolute;
    top: 50%;
    right: 10px; /* Position the arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid white; /* Arrow color */
    transition: transform 0.3s ease; /* Animation duration */
    transform: translateY(-50%) rotate(0deg); /* Initial rotation */
}

/* Arrow rotation class */
.arrow.open {
    transform: translateY(-50%) rotate(180deg); /* Arrow pointing down */
}

.options {
    display: none; /* Hidden by default */
    position: absolute;
    top: 100%; /* Position it below the dropdown */
    left: 0;
    right: 0;
    background-color: #001e54; /* Same background as the dropdown */
    border-radius: 15px; /* Rounded corners for options */
    z-index: 100; /* Ensure it's above other elements */
    overflow: hidden; /* Prevent overflow */
    animation: slideIn 0.3s ease; /* Animation for dropdown opening */
}

.options.active {
    display: block; /* Show options when active */
}

/* Option styles */
.option {
    padding: 10px;
    color: white;
    cursor: pointer;
    text-align: center; /* Center text */
    border-top: 1px solid rgba(255, 255, 255, 0.2); /* 20% transparent divider line */
}

.option:first-child {
    border-top: none; /* Remove the top border from the first option */
}

.option:hover {
    background-color: #004080; /* Darker shade on hover */
}

.selected {
    display: block; /* Show the selected option */
    text-align: center; /* Center text for selected */
}

/* Animation for dropdown opening */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-btn-primary,
.custom-btn-light {
    outline: none; /* Remove focus outline */
    box-shadow: none; /* Remove any box shadow */
}

/* Adjust button size and stacking on mobile */
.custom-btn-light,
.custom-btn-primary {
    background-color: #001e54;
    color: white;
    border-radius: 20px; /* Rounded corners */
    padding: 10px 15px; /* Smaller padding */
    font-size: 14px; /* Smaller font size */
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
    background-color: transparent; 
    color: #002060; 
    border: none; 
}

.custom-btn-light:hover {
    color: #white; /* Darker shade on hover */
}

.form-control-container {
    display: flex;                
    justify-content: center;     
    align-items: stretch;         
    margin-top: 40px;             
}

.form-control {
    padding: 12px !important;  
    border-radius: 20px 0 0 20px !important;
    border: 1px solid #ccc !important;
    transition: border-color 0.3s !important;
    border-right: none !important; 
    font-size: 14px !important;
    color: #1a2a5c !important;
    max-width: 50% !important;  
    height: auto;               
}

.input-group .btn {
    padding: 12px !important;     
    border-radius: 0 15px 15px 0 !important; 
    display: flex;               
    align-items: center;         
    justify-content: center;      
    height: auto;                
}

/* Stack buttons vertically on mobile */
@media (max-width: 576px) {
    .custom-btn-primary {
        margin-bottom: 10px; /* Space between buttons */
        width: 100%; /* Full width on mobile */
    }
}

</style>

<script>
    document.getElementById('userSearch').addEventListener('input', function () {
        filterTable();
    });

    // Select the dropdown and arrow elements
    const roleDropdown = document.getElementById('roleDropdown');
    const arrow = document.getElementById('dropdownArrow');
    const options = document.querySelector('.options');

    // Add event listener for click to animate arrow and toggle options
    roleDropdown.addEventListener('click', (event) => {
        // Prevent the dropdown from immediately closing when clicking an option
        event.stopPropagation();
        options.classList.toggle('active'); // Toggle visibility of options
        arrow.classList.toggle('open'); // Toggle class to rotate arrow
    });

    // Handle option click
    document.querySelectorAll('.option').forEach(option => {
        option.addEventListener('click', function () {
            const selectedRole = this.getAttribute('data-value');
            document.getElementById('selectedRole').innerText = selectedRole; // Update displayed value

            options.classList.remove('active'); // Hide options after selection
            arrow.classList.remove('open'); // Reset arrow
            filterTable(); // Filter table based on selected role
        });
    });

    // Close the dropdown if clicking outside of it
    document.addEventListener('click', function (event) {
        if (!roleDropdown.contains(event.target)) {
            options.classList.remove('active'); // Hide options when clicking outside
            arrow.classList.remove('open'); // Reset arrow when clicking outside
        }
    });

    function filterTable() {
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        const roleFilter = document.getElementById('selectedRole').innerText.toLowerCase();
        const rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // 2nd column contains the name
            const role = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // 3rd column contains the role

            const matchesSearch = name.includes(searchTerm);
            const matchesRole = (roleFilter === 'all') || role.includes(roleFilter); // Show all if 'All' is selected

            if (matchesSearch && matchesRole) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

@endsection
