<div class="container">
    <div class="participant-list-container">
        @foreach($participants as $participant)
            <div class="participant-list-item" data-user-id="{{ $participant->user_id }}" data-name="{{ strtolower($participant->user->first_name . ' ' . $participant->user->last_name) }}">
                <!-- User Information -->
                <div class="participant-info">
                    <div class="participant-profile">
                        @if($participant->user)
                            @if(is_null($participant->user->profile_picture))
                                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture"> 
                            @else 
                                <img src="/{{ $participant->user->profile_picture }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                            @endif
                            <div class="participant-details">
                                <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                                    {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                                    @if(in_array($participant->user_id, $attendanceLog))
                                            <span style="color: green; !important;" title="Attended">&#10003;</span>
                                    @endif
                                    @if($participant->user->trashed())
                                        <span style="color: red;">(DELETED)</span>
                                    @endif
                                </a>
                                @if($participant->user->role_id == 1) 
                                    <p class="participant-status">Super Admin</p>
                                @elseif($participant->user->role_id == 2)
                                    <p class="participant-status">Admin</p>
                                @elseif($participant->user->role_id == 3)
                                    <p class="participant-status">User</p>
                                @endif
                            </div>
                        @else
                            <p>User information is missing.</p>
                        @endif
                    </div>
                </div>

                <!-- Attendance Check -->

                <!-- Remove Button (Visible only for Super Admin or Event Creator) -->
                @if($userevent->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    @if($participant->user) <!-- Ensure user exists before rendering the button -->
                        <div class="participant-actions">
                            <button type="button" class="btn btn-danger remove-btn" data-user-id="{{ $participant->user->id }}">
                                <span class="remove-text">Remove</span>
                                <span class="remove-icon">X</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
    <div class="pagination-container">
        {{ $participants->links() }}
    </div>
</div>


<style>
.remove-btn {
    width: auto;
    height: auto;
    border-radius: 15px; 
    background-color: #d9534f;
    color: white;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    cursor: pointer;
    padding: 8px 16px;
}

.remove-btn .remove-icon {
    display: none;
}

.remove-btn .remove-text {
    display: inline-block;
}
.attendance-status {
    margin-top: 5px;
    font-size: 14px;
}

@media (max-width: 900px) {
    .remove-btn .remove-text {
        display: none;
    }

    .remove-btn .remove-icon {
        display: inline-block;
        border-radius: 50%; /* Fully circular for the 'X' button */
        width: 20px; /* Make it a circle */
        height: 20px; /* Make it a circle */
        font-size: 18px; /* Adjust font size */
        display: flex;
        justify-content: center;
        align-items: center;

    }

    .remove-btn {
        font-size: 16px;
        padding: 5px 10px;
    }
}

@media (max-width: 767px) {
    .remove-btn {
        font-size: 14px;
        padding: 4px 8px;
    }
}
</style>
