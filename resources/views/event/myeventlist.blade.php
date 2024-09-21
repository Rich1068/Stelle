@extends('layouts.app')

@section('body')
<div class="page-title-container-eventlist">
    <h2>
        <i class="fas fa-list me-2"></i> <!-- List icon -->
        My Event List
    </h2>
</div> <!-- Close page-title-container-eventlist -->


        @if ($events->isEmpty())
            <p>No events available.</p>
        @else
            <div class="event-list">
                @foreach ($events as $event)
                    <div class="event-list-item">
                        <!-- Date Section -->
                        <div class="event-list-date text-center text-white">
                            <span class="event-list-day">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                            <span class="event-list-month">{{ \Carbon\Carbon::parse($event->date)->format('M Y') }}</span>
                        </div>

                        <!-- Event Details Section -->
                        <div class="event-list-details">
                            <h3 class="event-list-title mb-1">{{ $event->title }}</h3>
                            <p class="event-list-description text-muted mb-2">{{ Str::limit($event->description, 50) }}</p>
                            <div class="event-list-meta">
                                <div class="meta-item time">
                                    <i class="fas fa-clock"></i>
                                    <span class="meta-text">{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</span>
                                </div>
                                <div class="meta-item location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="meta-text">{{ $event->address }}</span>
                                </div>
                                <div class="meta-item capacity">
                                    <i class="fas fa-users"></i>
                                    <span class="meta-text">{{ $event->capacity }}</span>
                                </div>
                            </div>
                            <div class="event-list-actions">
                                <a href="{{ route('event.view', $event->id) }}" class="event-list-view-btn">View</a>
                                <a href="{{ route('event.edit', $event->id) }}" class="event-list-edit-btn">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        @endif
   
@endsection
