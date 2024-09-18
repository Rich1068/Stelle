@extends('layouts.app')

@section('body')
    <div class="container event-list-container">
        <h1 class="event-list-header">Event List</h1>
        <form method="GET" action="{{ route('event.list') }}" id="event-filter-form" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search events" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Search</button>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="input-group">
                    <!-- Date Input -->
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" id="date-input">
                    <!-- Clear Button (X) beside the date input -->
                    @if(request('date'))
                        <button type="button" id="clear-date-btn" class="btn btn-outline-secondary">
                            &#x2715; <!-- X icon -->
                        </button>
                    @endif
                </div>
            </div>


        </form>
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
                            <a href="{{ route('event.view', $event->id) }}" class="event-list-view-btn">View</a>
                        </div>
                    </div>
                @endforeach
            </div>

<!-- Using the custom1 pagination template -->
<div class="d-flex justify-content-center">
    {{ $events->appends(request()->query())->links('vendor.pagination.custom1') }}
</div>
    @endif
</div>
<script>
    document.getElementById('date-input').addEventListener('change', function() {
        document.getElementById('event-filter-form').submit();
    });
    const clearDateBtn = document.getElementById('clear-date-btn');
        if (clearDateBtn) {
            clearDateBtn.addEventListener('click', function() {
                // Clear the date input
                document.getElementById('date-input').value = '';

                // Submit the form to clear the filter
                document.getElementById('event-filter-form').submit();
            });
        }
</script>

@endsection
