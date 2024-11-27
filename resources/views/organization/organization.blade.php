@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="event-view-container">
    <!-- Tab Navigation -->
    <div class="tabs">
        <div class="tab-button active" data-tab="main">Organization Details</div>
        <div class="tab-button" data-tab="participants">Members</div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Main Page Content -->
        <div class="tab-pane active" id="main">
            <div class="event-view-content">
                <div class="event-view-header">
                    <h1 class="event-view-event-title" style="padding-top:10px">
                        {{ $organization->name }}
                        @if($organization->trashed()) 
                            <span style="color: red;">(ARCHIVED)</span>
                        @endif
                    </h1>
                    @if($organization->icon)
                        <img src="{{ asset($organization->icon) }}" alt="Organization Icon" class="organization-icon me-2">
                    @endif
                </div>
                <h3 class="event-view-about">
                    <i class="fas fa-info-circle"></i>
                    <b>About: </b>{{ $organization->description }}
                </h3>

                <div class="event-view-divider">
                    <span>Information</span>
                </div>

                <div class="event-view-info">   
                    <!-- Display Address -->
                    <div>
                        <i class="fas fa-solid fa-envelope"></i>
                        <span data-label="Email: ">@if($organization->contact_email == null) N/A @else {{ $organization->contact_email }} @endif</span>
                    </div>

                    <div>
                        <i class="fas fa-solid fa-phone"></i>
                        <span data-label="Contact Number: ">@if($organization->contact_phone == null) N/A @else {{ $organization->contact_phone }} @endif</span>
                    </div>

                    <!-- Display Capacity -->
                    <div>
                        <i class="fas fa-users"></i>
                        <span data-label="Members: ">{{ $totalMembers }}</span>
                    </div>

                    <!-- Display Organizer -->
                    <div>
                        <i class="fas fa-user"></i>
                        <span data-label="By: ">
                            <a href="{{ route('profile.view', $organization->owner) }}" class="no-link-style">
                                {{ $organization->owner->first_name }} {{ $organization->owner->last_name }}
                            </a>
                            @if($organization->owner->trashed())
                                <span style="color: red;">(DELETED)</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="event-view-buttons" style="display: flex; flex-direction: column; align-items: flex-start;">
                @if($organization->owner_id == Auth::user()->id)

           
                        <!-- Top Divider with Left-Aligned Text -->
                        <div style="width: 100%; text-align: left; position: relative; margin-top: 10px;">
                            <hr style="margin: 0; border: 1px solid #ccc; width: 100%;" />
                            <span style="position: absolute; top: -8px; background: white; padding: 0 10px; font-weight: bold; font-size: 12px;">Admin Control</span>
                        </div>

                        <div class="button-section"> 
                            <a href="{{ route('organization.edit', $organization->id) }}" class="btn btn-primary" 
                            style="flex: 1; min-width: 120px; text-align: center; padding: 10px;">
                                <span>Edit</span>
                            </a>
                            </br>
                            <div class="toggle-container">
                                <label class="switch">
                                    <input type="checkbox" id="is_open" {{ $organization->is_open ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <span id="toggle-label">{{ $organization->is_open ? 'Open' : 'Closed' }}</span>
                            </div>
                        </div>
                @endif
                @if($organization->owner_id != Auth::user()->id)
                    @if ($overallMember && $overallMember->status_id == 1)
                        <p>You are a member of this Organization</p>
                    @elseif ($overallMember && $overallMember->status_id == 3)
                        <button type="button" class="btn btn-secondary" disabled>Pending</button>
                    @elseif($organization->is_open == true)
                        <form action="{{ route('organization.join', $organization->id) }}" method="POST" class="full-width-button">
                            @csrf
                            <button type="submit" class="btn btn-primary">Join Organization</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>Closed</button>
                    @endif
                @endif
            </div>
            </div>
        </div>
        <div class="tab-pane" id="participants">
            <div class="top-container">
                <div class="answer-forms-event-title">
                    Organization Members
                </div>
                <div><i class="fas fa-users"></i><span data-label="members:">{{ $totalMembers }}</span></div>
            </div>
            <div class="card mb-4" style="margin-top: 25px; border: none; max-width: 100%;">
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="membersTable" class="table table-bordered text-center" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>{{ $member->member->first_name }} {{ $member->member->last_name }}                            
                                        @if($member->member->trashed())
                                            <span style="color: red;">(DELETED)</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->member->email }}</td>
                                    <td>

                                    @switch($member->org_role_id)
                                        @case(1)
                                            Owner |
                                            @break
                                        @case(2) 
                                            Organizer |
                                            @break
                                        @case(3)
                                            Member |
                                            @break
                                        @default
                                        Unknown Organization Role |
                                    @endswitch
                                    @switch($member->member->role_id)
                                        @case(1)
                                            Super Admin
                                            @break
                                        @case(2)
                                            Admin
                                            @break
                                        @case(3)
                                            User
                                            @break
                                        @default
                                            Unknown Role
                                    @endswitch
                                    </td>
                                    <td>
                                        <div class="button-group">
                                            @if($organization->owner_id == Auth::id())
                                                @if ($member->user_id != $organization->owner_id)
                                                <button type="button" class="btn btn-info rounded-circle change-role-btn" data-member-id="{{ $member->member->id }}" data-org-role-id="{{ $member->org_role_id }}" title="Change Role">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                                @endif
                                                <form action="{{ route('profile.view', $member->member->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit" class="btn btn-recover rounded-circle">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form>
                                                @if ($member->user_id != $organization->owner_id)
                                                <form action="{{ route('organization.member.remove', ['id' => $organization->id, 'memberId' => $member->user_id]) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-delete rounded-circle" onclick="return confirm('Are you sure you want to remove this member?');">
                                                    <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @else
                                                <form action="{{ route('profile.view', $member->member->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit" class="btn btn-recover rounded-circle">
                                                        <i class="fas fa-eye"></i>
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
            @if($organization->owner_id == Auth::id())
            <a href="{{ route('organization.pendingmembers', $organization->id) }}" class="position-relative">
                <button type="submit" class="btn btn-primary-2 position-relative">
                    View Pending Members
                    @if($pendingMembersCount > 0)
                        <span class="badge bg-danger pending-badge">{{ $pendingMembersCount }}</span>
                    @endif
                </button>
            </a>
            @endif
        </div>

        @if($organization->owner_id == Auth::id())
        <div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeRoleModalLabel">Change Member Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changeRoleForm" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Member ID (Hidden Field) -->
                            <input type="hidden" id="modal-member-id" name="member_id">

                            <!-- Select Role Dropdown -->
                            <div class="mb-3">
                                <label for="org_role_id" class="form-label">Select Role</label>
                                <select id="org_role_id" name="org_role_id" class="form-select">
                                    @foreach ($organizationRoles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Warning for Owner Role -->
                            <div id="ownerWarning" class="alert alert-warning d-none">
                                <strong>Warning:</strong> Assigning the "Owner" role will transfer ownership. You will become an Organizer after this action.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Change Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmOwnerModal" tabindex="-1" aria-labelledby="confirmOwnerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmOwnerModalLabel">Confirm Role Change</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to assign the "Owner" role to this user? You will lose ownership and become an Organizer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmOwnerButton" class="btn btn-danger">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<style>
    .event-view-about {
        margin-top: 10px
    }
    .organization-icon {
        width: 150px; /* Adjust size as needed */
        height: auto; /* Adjust size as needed */
    }
    #membersTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060;
    font-size: 0.9rem;
    }

    #membersTable th, #membersTable td {
        text-align: center;
        vertical-align: middle;
        padding: 5px;
        border-bottom: 1px solid #e0e0e0;
    }

    /* Header Styling */
    #membersTable th {
        background-color: #f7f8fa;
        font-weight: bold;
        color: #333;
    }
    #membersTable td:nth-child(2),
    #membersTable th:nth-child(2) {
        min-width: 100px; /* Adjust width as needed */
        white-space: normal; /* Allow text to wrap */
        word-wrap: break-word;
    }

    /* Alternating Row Colors */
    #membersTable tbody tr:nth-child(odd) {
        background-color: #fafbfc;
    }

    #membersTable tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    /* Hover Effect */
    #membersTable tbody tr:hover {
        background-color: #f0f4ff;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 0.9rem;
        max-width: 100%;
        color: #333;
        background-color: #fafafa;
    }
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.65rem; 
            padding: 6px; 
        }

        .table th {
            font-weight: bold; 
            background-color: #f8f9fa; 
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 70% !important; /* Limit the width of the search bar */
        }
        .table td {
            white-space: normal; 
        }
    }
    @media (max-width: 576px) {
    .table th, .table td {
        font-size: 0.8rem; 
        padding: 4px; 
    }
    }

    @media (min-width: 992px) {
    .table th, .table td {
        font-size: 1.1rem; 
        padding: 10px;
    }
}
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
    .button-group {
        flex-direction: column;
    }
    .button-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px; /* Space between buttons */
    flex-direction: row;
    }
    .button-group .btn .fas, 
    .button-group .btn .fa {
        margin: auto; /* Center icon within the button */
    }

    .button-group .btn {
        width: 40px;
        height: 40px;
        font-size: 0.75rem;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        color: white;
    }
    .btn-recover {
    background-color: #008b8b;
    }
    .pending-badge {
        position: absolute;
        top: 0px;
        right: -10px;
        padding: 8px 10px;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* The switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    /* Hide the input */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 25px;
        cursor: pointer;
    }

    /* The circle inside the slider */
    .slider:before {
        position: absolute;
        content: '';
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 2.5px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    /* Checked state */
    input:checked + .slider {
        background-color: #003366;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    /* Label styling */
    #toggle-label {
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }
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
    .btn-delete {
        background-color: #c9302c;
    }
    @media (max-width: 768px) {
        .button-group .btn {
            width: 30px;
            height: 30px;
            font-size: 0.75rem;
        }
        .button-group {
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
        const activeTabKey = 'activeTab'; // Key to store the active tab in local storage

        // Retrieve the last selected tab from local storage, if it exists
        const savedTab = localStorage.getItem(activeTabKey);
        if (savedTab) {
            // Find the button and pane for the saved tab
            const savedButton = document.querySelector(`.tab-button[data-tab="${savedTab}"]`);
            const savedPane = document.getElementById(savedTab);

            if (savedButton && savedPane) {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Activate the saved tab
                savedButton.classList.add('active');
                savedPane.classList.add('active');
            }
        }
    // Tab switching logic
    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to the clicked button and the corresponding pane
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');

            // Save the current tab to local storage
            localStorage.setItem(activeTabKey, targetTab);
        });
    });
    });
    document.addEventListener('DOMContentLoaded', function () {
        $('#membersTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            responsive: true,
        });
    });
    @if($organization->owner_id == Auth::id())
    document.getElementById('is_open').addEventListener('change', function () {
    const isChecked = this.checked;
    const organizationId = "{{ $organization->id }}"; // Pass the organization ID

    // Update the label text
    const toggleLabel = document.getElementById('toggle-label');
    toggleLabel.textContent = isChecked ? 'Open' : 'Closed';

    // Show an alert
    alert(`Organization is now ${isChecked ? 'open' : 'closed'}.`);

    // Send AJAX request to update the state
    fetch(`/organization/${organizationId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ is_open: isChecked }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Failed to update organization status.');
            }
            return response.json();
        })
        .then((data) => {
            console.log('Organization status updated successfully:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Failed to update organization status. Please try again.');
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const changeRoleButtons = document.querySelectorAll('.change-role-btn');
        const roleSelect = document.getElementById('org_role_id');
        const ownerWarning = document.getElementById('ownerWarning');
        const changeRoleForm = document.getElementById('changeRoleForm');
        const confirmOwnerModal = new bootstrap.Modal(document.getElementById('confirmOwnerModal'));
        const confirmOwnerButton = document.getElementById('confirmOwnerButton');

        // Show the Change Role Modal
        changeRoleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const memberId = this.dataset.memberId;
                const orgRoleId = this.dataset.orgRoleId;
                const url = `/organization/{{ $organization->id }}/members/${memberId}/change-role`;

                document.getElementById('modal-member-id').value = memberId;
                roleSelect.value = orgRoleId;
                changeRoleForm.action = url;

                // Hide the owner warning initially
                ownerWarning.classList.add('d-none');

                const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
                modal.show();
            });
        });

        // Handle Role Selection
        roleSelect.addEventListener('change', function () {
            if (this.value == 1) { // Assuming 1 is the "Owner" role ID
                ownerWarning.classList.remove('d-none'); // Show owner warning
            } else {
                ownerWarning.classList.add('d-none'); // Hide warning for other roles
            }
        });

        // Intercept Form Submission for Owner Role
        changeRoleForm.addEventListener('submit', function (e) {
            if (roleSelect.value == 1) {
                e.preventDefault(); // Prevent immediate submission
                confirmOwnerModal.show(); // Show confirmation modal
            }
        });

        // Confirm Owner Role Change
        confirmOwnerButton.addEventListener('click', function () {
            confirmOwnerModal.hide(); // Close confirmation modal
            changeRoleForm.submit(); // Submit the role change form
        });
    });
    @endif
</script>
@endsection