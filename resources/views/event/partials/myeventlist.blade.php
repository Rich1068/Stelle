<!-- @if ($events->isEmpty())
<div class="no-events-container">
    <i class="fas fa-calendar-times"></i> 
    <p>No events available.</p>
</div>

@else -->
    <div class="event-list">
        @foreach ($events as $event)
            <div class="event-list-item mb-4">
                <!-- Date Section -->
                <div class="event-list-date text-center text-white">
                    @if ($event->start_date === $event->end_date)
                        <!-- Single-day event -->
                        <span class="event-list-day">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                        <span class="event-list-month">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</span>
                    @elseif (\Carbon\Carbon::parse($event->start_date)->format('M Y') === \Carbon\Carbon::parse($event->end_date)->format('M Y'))
                        <!-- Multi-day event within the same month -->
                        <span class="event-list-day">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d') }}
                        </span>
                        <span class="event-list-month">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</span>
                    @else
                        <!-- Multi-day event in different months or years -->
                        <span class="event-list-day">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                        <span class="event-list-month">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</span>
                        <span>-</span>
                        <span class="event-list-day">{{ \Carbon\Carbon::parse($event->end_date)->format('d') }}</span>
                        <span class="event-list-month">{{ \Carbon\Carbon::parse($event->end_date)->format('M Y') }}</span>
                    @endif
                </div>

                <!-- Event Details Section -->
                <div class="event-list-details">
                    <h3 class="event-list-title mb-1">
                        @if($event->trashed())
                            <a href="{{ route('event.view', $event->id) }}" style="color: #001e54;">
                                {{ $event->title }} <span style="color: red;">(DELETED)</span>
                            </a>
                        @else
                        <a href="{{ route('event.view', $event->id) }}" style="color: #001e54;">
                            {{ $event->title }}
                        </a>
                        @endif
                    </h3>
                    <p class="event-list-description text-muted mb-2">{{ Str::limit($event->description, 50) }}</p>
                    <div class="event-list-meta d-flex justify-content-between">
                        <div class="meta-item time d-flex align-items-center">
                            
                        </div>
                        <div class="meta-item location d-flex align-items-center">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="meta-text ms-1">{{ $event->address }}</span>
                        </div>
                        <div class="meta-item capacity d-flex align-items-center">
                            <i class="fas fa-users"></i>
                            <span class="meta-text ms-1">{{ $event->current_participants }}/{{ $event->capacity }}</span>
                        </div>
                    </div>
                    <div class="event-list-actions">
                        <a href="{{ route('event.view', $event->id) }}" class="event-list-view-btn">View</a>
                        @if ($event->trashed())
                        @else
                        <a href="{{ route('event.edit', $event->id) }}" class="event-list-edit-btn">Edit</a>
                        <form action="{{ route('admin.event.deactivate', $event->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this event?')" title="Delete Event" class="event-list-edit-btn" style="color: red; background: none; border: none; cursor: pointer;">
                                Remove
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
<!-- @endif -->
