@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none; margin-bottom: 100px;">
    <!-- Left: Event List Title -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-calendar-alt"></i> Event List
        </h2>
    </div>
</div>

<div class="card mb-4" style="margin-top: 50px; border: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Events List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Organizer</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Organizer</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>
                            <a href="{{ route('event.view', $event->id) }}" class="event-title" style="color: #001e54; text-decoration: none;">
                                {{ $event->title }}
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}</td>
                        <td>{{ Str::limit($event->description, 50, '...') }}</td>
                        <td>{{ $event->userEvent->user->first_name }} {{ $event->userEvent->user->last_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Styles for the Event List */
.custom-btn-light, .custom-btn-primary {
    background-color: #001e54 !important;
    color: white !important;
    border-radius: 15px !important;
    padding: 12px 20px !important;
    font-size: 16px !important;
    font-weight: bold !important;
    text-align: center !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: none !important;
    transition: background-color 0.3s, transform 0.3s !important;
    max-width: 200px !important;
}

.custom-btn-primary:hover {
    background-color: #004080 !important;
    transform: translateY(-2px) !important;
    color: #ffff !important;
}

.form-control-container {
    display: flex;
    justify-content: center;
    align-items: stretch;
    margin-top: 40px;
}

@media (max-width: 576px) {
    .custom-btn-primary {
        margin-bottom: 10px;
        width: 100%;
    }
}
</style>

<!-- Add DataTables JavaScript -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTables on the event table
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10,
            "language": {
                "searchPlaceholder": "Search for events..." // Placeholder text for search box
            }
        });
    });
</script>

@endsection
