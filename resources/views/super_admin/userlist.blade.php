@extends('layouts.app')

@section('body')

<div class="page-title-container-eventlist" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div class="answer-forms-event-title" style="font-weight: bold; font-size: 24px; color: #001e54;">
        <i class="fas fa-users"></i> User List
    </div>
    <div class="add-user-buttons" style="display: flex; gap: 10px;">
        <a href="{{ route('superadmin.usercreate') }}" class="btn btn-primary mt-2">
            <i class="fas fa-user-plus"></i> Add User
        </a>
        <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="display: inline;">
            <button type="submit" class="btn btn-primary mt-2">
                <i class="fas fa-user-shield"></i> View Requesting Admin
            </button>
        </form>
    </div>
</div>

<div class="table-responsive" style="max-width: 80%; margin: 0 auto;">
    <table class="table table-bordered mt-4" style="border: 1px solid #001e54; background-color: white; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); font-size: 14px; width: 100%; table-layout: fixed; overflow: hidden;">
        <thead style="background-color: #001e54; color: white;">
            <tr>
                <th style="padding: 6px; text-align: center; vertical-align: middle;">Profile Picture</th>
                <th style="padding: 6px; text-align: center; vertical-align: middle;">Name</th>
                <th style="padding: 6px; text-align: center; vertical-align: middle;">Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="text-align: center;">
                <td style="padding: 6px;">
                    @if($user->profile_picture == null)
                        <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54;">
                    @else
                        <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #001e54;">
                    @endif
                </td>
                <td style="vertical-align: middle; font-weight: bold; font-size: 14px; padding: 6px;">
                    <a href="{{ route('profile.view', $user->id) }}" class="participant-name" style="color: #001e54; text-decoration: none;">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </a>
                </td>
                <td style="vertical-align: middle; font-weight: bold; font-size: 14px; padding: 6px;">
                    <span style="color: #001e54;">{{ $user->role->role_name }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>



</div>

@endsection
