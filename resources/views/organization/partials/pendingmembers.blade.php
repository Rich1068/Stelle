@foreach($members as $member)
    <div class="participant-list-item" 
         data-user-id="{{ $member->member->id }}" 
         data-name="{{ strtolower($member->member->first_name . ' ' . $member->member->last_name) }}">
         
        <!-- User Information -->
        <div class="participant-info">
            <div class="participant-profile d-flex align-items-center">
                @if(is_null($member->member->profile_picture))
                    <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" 
                         alt="Default profile picture" 
                         class="profile-picture"> 
                @else 
                    <img src="{{ asset($member->member->profile_picture) }}" 
                         alt="{{ $member->member->first_name }}" 
                         class="profile-picture">
                @endif
                <div class="participant-details ms-3">
                    <a href="{{ route('profile.view', $member->member->id) }}" class="participant-name">
                        {{ $member->member->first_name }} {{ $member->member->last_name }}
                    </a>
                    <p class="participant-email">{{ $member->member->email }}</p>
                    <p class="participant-status">
                        @if($member->member->role_id == 1) 
                            Super Admin
                        @elseif($member->member->role_id == 2)
                            Admin
                        @elseif($member->member->role_id == 3)
                            User
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="participant-actions mt-3">
            <div class="button-container d-flex gap-2">
                <button 
                    type="button" 
                    class="btn btn-success accept-btn" 
                    data-user-id="{{ $member->member->id }}" 
                    data-organization-id="{{ $organization->id }}"
                    {{ !$organization->is_open ? 'disabled' : '' }}>
                    Accept
                </button>
                <button 
                    type="button" 
                    class="btn btn-danger decline-btn" 
                    data-user-id="{{ $member->member->id }}" 
                    data-organization-id="{{ $organization->id }}">
                    Decline
                </button>
            </div>
        </div>
    </div>
@endforeach
