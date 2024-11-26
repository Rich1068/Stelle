@extends('layouts.app')

@section('body')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    <!-- Permanent notification if the organization is closed -->
    @if (!$organization->is_open)
        <div class="alert alert-danger">
            This organization is currently closed. Members cannot be accepted.
        </div>
    @endif

<div class="top-container mb-4" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <!-- Left: My Certificate Templates Title -->
    <div class="d-block">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> Pending Members For
        </h2>
        <h2 class="font-weight-bold mt-2" style="color: grey;">
            {{ $organization->name }}
        </h2>
        <h5 id="participantCount" class="font-weight-bold mt-3" style="color: grey;">
            <i class="fas fa-users"></i>{{$totalMembers}}
        </h5>
    </div>
</div>

<!-- Search Bar -->
<div class="container">
    <div class="search-bar-container d-flex justify-content-center" style="margin: 30px 0;">
        <div class="search-wrapper position-relative w-50">
            <input type="text" id="search-participants" class="form-control search-input" placeholder="Search participants..." style="border-radius: 10px; height: 50px; padding: 10px 45px 10px 20px;">
            <i class="fas fa-search search-icon position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%); color: #999;"></i>
        </div>
    </div>

    <!-- Participant List -->
    <div class="participant-list-container">
    @include('organization.partials.pendingmembers', ['members' => $members, 'organization' => $organization])
</div>


    <!-- Pagination Links with custom template -->
    <div class="d-flex justify-content-center" id="pagination-links">
        {{ $members->appends(request()->query())->links('vendor.pagination.custom1') }}
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    $('#search-participants').on('input', function () {
        const searchQuery = $(this).val();
        const organizationId = "{{ $organization->id }}";

        $.ajax({
            url: '/organization/' + organizationId + '/pending-members',
            type: 'GET',
            data: { search: searchQuery },
            success: function (response) {
                // Replace the member list with filtered results
                $('.participant-list-container').html(response.html);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    });

    $(document).on('click', '#pagination-links a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('.participant-list-container').html(response.html);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    });

    $(document).on('click', '.accept-btn', function (e) {
        e.preventDefault();
        let userId = $(this).data('user-id');
        let organizationId = $(this).data('organization-id');
        let statusId = 1; // Accepted

        $.ajax({
            url: '/organization/' + organizationId + '/pending-members/' + userId + '/update',
            type: 'POST', // Ensure POST method is used
            data: {
                _token: '{{ csrf_token() }}',
                status_id: statusId,
            },
            success: function () {
                $('.participant-list-item[data-user-id="' + userId + '"]').remove();
                alert('Member accepted successfully.');
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseText);
            },
        });
    });

    $(document).on('click', '.decline-btn', function (e) {
        e.preventDefault();
        let userId = $(this).data('user-id');
        let organizationId = $(this).data('organization-id');
        let statusId = 2; // Declined

        $.ajax({
            url: '/organization/' + organizationId + '/pending-members/' + userId + '/update',
            type: 'POST', // Ensure POST method is used
            data: {
                _token: '{{ csrf_token() }}',
                status_id: statusId,
            },
            success: function () {
                $('.participant-list-item[data-user-id="' + userId + '"]').remove();
                alert('Member declined successfully.');
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseText);
            },
        });
    });

        // Notification helper function
        function showNotification(type, message) {
            const notification = $('<div>')
                .addClass(`alert alert-${type === 'success' ? 'success' : 'danger'}`)
                .text(message);

            $('#notifications').append(notification);

            // Auto-dismiss notification after 5 seconds
            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 5000);
        }
});

</script>

<style>
    .search-input {
        border-radius: 8px;
        height: 50px;
        padding-right: 45px; /* Enough space for the icon */
    }

    .search-icon {
        pointer-events: none;
    }
    .accept-btn:disabled {
        background-color: #d6d6d6; /* Light gray background */
        color: #8c8c8c; /* Gray text color */
        cursor: not-allowed; /* Show "not allowed" cursor */
        border: 1px solid #c0c0c0; /* Optional: light border */
    }

</style>
@endsection
