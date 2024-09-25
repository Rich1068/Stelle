@extends('layouts.app')

@section('body')
<div class="container-fluid px-4">

    <!-- Combined Container with Divider -->
    <div class="row mb-4">
        <div class="col-lg-12 d-flex justify-content-center">
            <div class="card shadow-sm rounded" style="border-radius: 15px; width: 100%; max-width: 1200px;">
                <div class="d-flex" style="width: 100%;">
                    <!-- First Section -->
                    <div class="flex-fill" style="padding: 2rem; border-radius: 15px 0 0 15px; background-color: #f8f9fc;">
                        <h2 class="text-primary font-weight-bold mb-0">
                            Good Evening <span class="text-dark">User!</span>
                        </h2>
                        <p class="text-muted mb-0 font-weight-bold">How are you feeling?</p>
                    </div>

                    <!-- Divider -->
                    <div class="divider" style="width: 2px; background-color: #dee2e6;"></div>

                    <!-- Second Section -->
                    <div class="flex-fill" style="padding: 2rem; border-radius: 15px 15px 15px 0; background-color: #f8f9fc;">
                        <h2 class="text-primary font-weight-bold mb-0">
                            Here are your scheduled events <span class="text-dark">for this month</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dropdown mt-3">
        <select id="calendarFilter" class="form-control">
            <option value="all">All Events</option>
            <option value="join">Joined Events</option>
        </select>
    </div>
    <!-- Calendar Container -->
    <div id="calendar"></div>

    <!-- Modal for event details -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" id="modalContent">
                    <!-- Modal content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>


    <!-- Registration Section (Bottom Right) -->
    <div class="row mb-4">
        <div class="col-lg-12 d-flex justify-content-end">
            <div class="card shadow-sm rounded border-light p-4" style="border-radius: 15px; width: 300px;">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3 font-weight-bold">Want to Organize Events?</h3>
                    <p class="card-text mb-4 font-weight-bold">Become an admin and start managing events today!</p>
                    <form id="registerAdminForm" action="{{ route('register.admin') }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-dark-blue btn-lg" onclick="confirmSubmission()">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .btn-dark-blue {
        background-color: #003366; /* Dark Blue Color */
        color: white;
        border-radius: 15px; /* Circular but consistent */
        padding: 10px 20px; /* Adjust padding as needed */
        border: none;
        font-weight: bold; /* Make button text bold */
    }

    .btn-dark-blue:hover {
        background-color: #002244; /* Darker shade on hover */
    }

    .card {
        border: none;
    }

    .table th, .table td {
        border: 1px solid #dee2e6; /* Light border for table cells */
        font-weight: bold; /* Make table text bold */
    }

    .card-header {
        border-radius: 15px 15px 0 0; /* Rounded top corners consistent with other elements */
    }

    .table thead th {
        background-color: #003366; /* Dark Blue for table header */
        color: white;
    }

    .table tbody td {
        background-color: #f8f9fc; /* Light background for table cells */
    }

    .table tbody tr:nth-child(odd) td {
        background-color: #e9ecef; /* Alternate row color */
    }

    .bg-dark-blue {
        background-color: #003366; /* Dark Blue Background */
    }
</style>

<script>
    function confirmSubmission() {
        // Show confirmation dialog
        if (confirm('Are you sure you want to register as an event admin?')) {
            // If confirmed, submit the form
            document.getElementById('registerAdminForm').submit();
        }
        // If not confirmed, do nothing
    }
</script>
@endsection
