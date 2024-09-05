<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- User's Name at the Top -->
    <div style="margin-bottom: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 class="name-bold" style="color: darkblue;">
            Hello, {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
        </h2>
    </div>

    <!-- Earnings (Monthly) -->
    <div style="margin-bottom: 30px; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: darkblue;">Earnings (Monthly)</h3>
        <p style="font-size: 24px; color: #28a745;">$40,000</p>
    </div>

    <!-- Tasks Progress -->
    <div style="margin-bottom: 30px; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: darkblue;">Tasks</h3>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar" role="progressbar" style="width: 50%; background-color: #ffc107;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div style="margin-bottom: 30px; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: darkblue;">Pending Requests</h3>
        <p style="font-size: 24px; color: #dc3545;">18</p>
    </div>

    <!-- Mockup Calendar with Sample Schedules -->
    <div style="padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: darkblue; margin-bottom: 20px;">Your Schedule</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Event</th>
                    <th scope="col">Time</th>
                    <th scope="col">Location</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-09-10</td>
                    <td>Team Meeting</td>
                    <td>10:00 AM - 11:00 AM</td>
                    <td>Conference Room B</td>
                </tr>
                <tr>
                    <td>2024-09-12</td>
                    <td>Project Presentation</td>
                    <td>2:00 PM - 3:30 PM</td>
                    <td>Main Auditorium</td>
                </tr>
                <tr>
                    <td>2024-09-15</td>
                    <td>Client Workshop</td>
                    <td>9:00 AM - 12:00 PM</td>
                    <td>Room 203</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
