<div class="event-list">
        @foreach ($organizations as $organization)
            <div class="event-list-item mb-4">
                <!-- Date Section -->
                <!-- Event Details Section -->
                <div class="event-list-details">        
                    <h3 class="event-list-title mb-1"> <a href="{{ route('organization.view', $organization->id) }}" style="color: #001e54;">{{ $organization->name }}</a></h3>
                    <p class="event-list-description text-muted mb-2">{{ Str::limit($organization->description, 50) }}</p>
                    <div class="event-list-meta d-flex justify-content-between">
                        <div class="meta-item time d-flex align-items-center">

                        </div>
                        <div class="meta-item location d-flex align-items-center">
                            <i class="fas fa-solid fa-envelope"></i>
                            <span class="meta-text ms-1">@if($organization->contact_email == null) N/A @else {{ $organization->contact_email }} @endif</span>
                        </div>
                        <div class="meta-item d-flex align-items-center">
                            <i class="fas fa-solid fa-phone"></i>
                            <span class="meta-text ms-1">@if($organization->contact_phone == null) N/A @else {{ $organization->contact_phone }} @endif</span>
                        </div>
                        <div class="meta-item capacity d-flex align-items-center">
                            <i class="fas fa-users"></i>
                            <span class="meta-text ms-1">{{ $organization->current_members }}</span>
                        </div>
                    </div>
                    <div class="event-list-actions">
                        <a href="{{ route('organization.view', $organization->id) }}" class="event-list-view-btn">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>