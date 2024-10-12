@if ($events->isEmpty())
    <p>No events available.</p>
@else
    <div class="event-list">
        @foreach ($events as $event)
            <div class="event-list-item mb-4">
                <!-- Date Section -->
                <div class="event-list-date text-center text-white">
                    <span class="event-list-day">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                    <span class="event-list-month">{{ \Carbon\Carbon::parse($event->date)->format('M Y') }}</span>
                </div>

                <!-- Event Details Section -->
                <div class="event-list-details">        
                    <h3 class="event-list-title mb-1"> <a href="{{ route('event.view', $event->id) }}" style="color: #001e54;">{{ $event->title }}</a></h3>
                    <p class="event-list-description text-muted mb-2">{{ Str::limit($event->description, 50) }}</p>
                    <div class="event-list-meta d-flex justify-content-between">
                        <div class="meta-item time d-flex align-items-center">
                            <i class="fas fa-clock"></i>
                            <span class="meta-text ms-1">{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="meta-item location d-flex align-items-center">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="meta-text ms-1">{{ $event->address }}</span>
                        </div>
                        <div class="meta-item capacity d-flex align-items-center">
                            <i class="fas fa-users"></i>
                            <span class="meta-text ms-1">{{ $event->capacity }}</span>
                        </div>
                    </div>
                    <div class="event-list-actions">
                        <a href="{{ route('event.view', $event->id) }}" class="event-list-view-btn">View</a>
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
