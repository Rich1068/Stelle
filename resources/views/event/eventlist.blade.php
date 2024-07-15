@extends('layouts.app')
@section('body')
    <div class="container">
        <h1>Event List</h1>

        @if ($events->isEmpty())
            <p>No events available.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Address</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ Str::limit($event->description, 50) }}</td>
                            <td>{{ $event->date }}</td>
                            <td>{{ $event->start_time }}</td>
                            <td>{{ $event->end_time }}</td>
                            <td>{{ $event->address }}</td>
                            <td>{{ $event->capacity }}</td>
                            <td>
                                <a href="{{ route('event.view', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                <!-- <a href="{{ route('profile.edit', $event->id) }}" class="btn btn-secondary btn-sm">Edit</a> -->
                                <!-- <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection