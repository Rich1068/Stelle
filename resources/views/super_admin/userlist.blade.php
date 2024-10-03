@extends('layouts.app')

@section('body')
<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <!-- Left: User List Title and Arrow Button -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> User List
        </h2>
        <!-- Arrow Button (Dropdown Trigger) -->
        <button id="toggleButton" class="btn custom-btn-light ms-2" type="button" aria-expanded="false" style="border: none; background-color: transparent;">
            <i id="arrowIcon" class="fas fa-chevron-down" style="font-size: 1.5rem; color: #002060;"></i> <!-- Arrow icon -->
        </button>
    </div>

    <!-- Right: Hidden Buttons -->
    <div class="d-flex align-items-center">
        <!-- Hidden Buttons (Initially Hidden) -->
        <div id="buttonContainer" style="display: none; margin-left: 10px;">
            <a href="{{ route('superadmin.usercreate') }}" class="btn custom-btn-primary" style="border-radius: 20px; margin-right: 10px;">
                <i class="fas fa-plus"></i> Add User
            </a>
            <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="display: inline;">
                <button type="submit" class="btn custom-btn-primary" style="border-radius: 20px;">
                    <i class="fas fa-user-shield"></i> View Requesting Admin
                </button>
            </form>
        </div>
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

<!-- JavaScript to toggle the visibility of the buttons and change the arrow direction -->
<script>
    document.getElementById("toggleButton").addEventListener("click", function() {
        var buttonContainer = document.getElementById("buttonContainer");
        var arrowIcon = document.getElementById("arrowIcon");

        // Toggle button container visibility
        if (buttonContainer.style.display === "none") {
            buttonContainer.style.display = "block"; // Show the buttons
            arrowIcon.classList.remove("fa-chevron-down"); // Change arrow direction
            arrowIcon.classList.add("fa-chevron-up"); // Point upward
        } else {
            buttonContainer.style.display = "none"; // Hide the buttons
            arrowIcon.classList.remove("fa-chevron-up"); // Change arrow direction back
            arrowIcon.classList.add("fa-chevron-down"); // Point downward
        }
    });
</script> 





<div class="card shadow mb-4">
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



@endsection
