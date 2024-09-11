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

    <!-- Centered Event Calendar (Full Width) -->
    <div class="row mb-5">
        <div class="col-lg-12 d-flex justify-content-center">
            <div class="card shadow-lg rounded border-light" style="border-radius: 15px; width: 100%; max-width: 700px;">
                <!-- Calendar Header with Month -->
                <div class="card-header bg-dark-blue text-white font-weight-bold text-center" style="border-radius: 5px 5px 0 0;">
                    <h6 class="m-0">September</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered text-center mb-0">
                        <thead class="bg-dark-blue text-white font-weight-bold">
                            <tr>
                                <th class="font-weight-bold">Sun</th>
                                <th class="font-weight-bold">Mon</th>
                                <th class="font-weight-bold">Tue</th>
                                <th class="font-weight-bold">Wed</th>
                                <th class="font-weight-bold">Thu</th>
                                <th class="font-weight-bold">Fri</th>
                                <th class="font-weight-bold">Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- First Week -->
                            <tr>
                                <td class="bg-light font-weight-bold"></td>
                                <td class="bg-light font-weight-bold"></td>
                                <td class="bg-light font-weight-bold"></td>
                                <td class="bg-light font-weight-bold">1</td>
                                <td class="bg-light font-weight-bold">2</td>
                                <td class="bg-light font-weight-bold">3</td>
                                <td class="bg-light font-weight-bold">4</td>
                            </tr>
                            <!-- Second Week -->
                            <tr>
                                <td class="bg-light font-weight-bold">5</td>
                                <td class="bg-light font-weight-bold">6 <br><span class="text-primary font-weight-bold">Dev Meet</span></td>
                                <td class="bg-light font-weight-bold">7</td>
                                <td class="bg-light font-weight-bold">8</td>
                                <td class="bg-light font-weight-bold">9 <br><span class="text-info font-weight-bold">AI Conf</span></td>
                                <td class="bg-light font-weight-bold">10<<br><span class="text-warning font-weight-bold">Sample Event</span></td>
                                <td class="bg-light font-weight-bold">11</td>
                            </tr>
                            <!-- Third Week -->
                            <tr>
                                <td class="bg-light font-weight-bold">12</td>
                                <td class="bg-light font-weight-bold">13</td>
                                <td class="bg-light font-weight-bold">14 <br><span class="text-warning font-weight-bold">Tech Expo</span></td>
                                <td class="bg-light font-weight-bold">15</td>
                                <td class="bg-light font-weight-bold">16</td>
                                <td class="bg-light font-weight-bold">17</td>
                                <td class="bg-light font-weight-bold">18 <br><span class="text-success font-weight-bold">Code Fest</span></td>
                            </tr>
                            <!-- Fourth Week -->
                            <tr>
                                <td class="bg-light font-weight-bold">19 <br><span class="text-danger font-weight-bold">Web Summit</span></td>
                                <td class="bg-light font-weight-bold">20</td>
                                <td class="bg-light font-weight-bold">21</td>
                                <td class="bg-light font-weight-bold">22</td>
                                <td class="bg-light font-weight-bold">23 <br><span class="text-primary font-weight-bold">Data Talk</span></td>
                                <td class="bg-light font-weight-bold">24</td>
                                <td class="bg-light font-weight-bold">25</td>
                            </tr>
                            <!-- Fifth Week -->
                            <tr>
                                <td class="bg-light font-weight-bold">26</td>
                                <td class="bg-light font-weight-bold">27 <br><span class="text-info font-weight-bold">JS Meetup</span></td>
                                <td class="bg-light font-weight-bold">28</td>
                                <td class="bg-light font-weight-bold">29</td>
                                <td class="bg-light font-weight-bold">30 <br><span class="text-warning font-weight-bold">Cloud Conf</span></td>
                                <td class="bg-light font-weight-bold">31</td>
                                <td class="bg-light font-weight-bold"></td>
                            </tr>
                        </tbody>
                    </table>
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
