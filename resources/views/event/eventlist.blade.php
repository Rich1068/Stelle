@extends('layouts.app')

@section('body')
<div class="page-title-container-eventlist">
    <h2>
        <i class="fas fa-calendar-alt me-2"></i> <!-- Calendar icon -->
        Event List
    </h2>
</div> <!-- Close page-title-container-eventlist -->


  
<div class="event-filter-container p-3 mb-3">
    <form method="GET" action="{{ route('event.list') }}" id="event-filter-form" class="d-flex justify-content-center align-items-center">
        <!-- Search input with search icon inside the same container -->
        <div class="search-wrapper">
            <input type="text" name="search" class="form-control search-input" placeholder="SEARCH FOR EVENTS HERE" value="{{ request('search') }}">
            <button class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Sort and date input inside the same container -->
        <div class="d-flex align-items-center sort-date-wrapper">
            <button class="btn sort-btn">SORT BY</button>
            <span class="divider">|</span>
            <input type="date" name="date" class="form-control date-input" value="{{ request('date') }}" id="date-input">
        </div>
    </form>
</div>




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
@endsection

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
