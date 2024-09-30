@extends('layouts.app')

@section('body')

<div class="top-container mb-4 d-flex align-items-left">
    <div class="d-flex align-items-right">
        <h2 class="font-weight-bold mb-0">
            <i class="fas fa-clipboard-list"></i> Manage Evaluation Forms
        </h2>
    </div>
    <div class="add-user-buttons ms-auto" style="display: flex; gap: 10px; margin-left: auto;">
        <form action="{{ route('evaluation-forms.create') }}" method="get" style="display: inline;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Evaluation Form
            </button>
        </form>
    </div>
</div>

<div class="container">
    @if($evaluationForms->isEmpty())
        <p class="text-center">No evaluation forms found.</p> <!-- Center-align the message -->
    @else
        <table class="table table-striped custom-table text-center"> <!-- Add 'text-center' to table -->
            <thead class="custom-thead"> <!-- Apply custom class for the table head -->
                <tr>
                    <th>ID</th>
                    <th>Form Name</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th> <!-- Center align actions -->
                </tr>
            </thead>
            <tbody>
                @foreach($evaluationForms as $form)
                <tr>
                    <td>{{ $form->id }}</td>
                    <td>{{ $form->form_name }}</td> <!-- Display form name -->
                    <td>{{ $form->status->status }}</td>
                    <td>{{ $form->creator->first_name }}</td>
                    <td>{{ $form->created_at->format('Y-m-d') }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('evaluation-forms.edit', $form->id) }}" class="btn btn-edit">Edit</a>

                        <!-- Deactivate Button (Change status to 'Inactive') -->
                        <form action="{{ route('evaluation-forms.deactivate', $form->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH') <!-- Using PATCH instead of DELETE -->
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to deactivate this form?')">Deactivate</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Custom CSS for table head and buttons -->
<style>
    /* Style for the table header */
    .custom-thead {
        background-color: #001e54; /* Set background to dark blue (#001e54) */
        color: white; /* Set text color to white */
    }

    /* Add more padding to the table header */
    .custom-thead th {
        padding: 1rem; /* Increase padding in the table head */
    }

    /* Remove white border from the table */
    .custom-table {
        border: none; /* Remove table border */
    }

    /* Remove white border from table cells */
    .custom-table td, .custom-table th {
        border: none; /* Remove border from table cells */
        text-align: center; /* Center-align text in all table cells */
        vertical-align: middle; /* Vertically center the content */
    }

    /* Reduce padding within table rows */
    .custom-table tbody tr {
        padding: 0.2rem 0.5rem; /* Adjust padding to your liking */
    }

    /* Center align the table itself */
    .custom-table {
        margin-left: auto;
        margin-right: auto;
    }

    /* Custom styles for Edit and Deactivate buttons */
    .btn-edit {
        margin-top: 19px; /* Adjust margin for better spacing */
        background-color: #001e54; /* Dark blue for Edit button */
        color: white;
        border-radius: 15px; /* Rounded corners */
        padding: 10px 15px; /* Adjust padding for a better fit */
        font-size: 15px; /* Font size */
        font-weight: bold;
        text-align: center; /* Center text */
        display: inline-block; /* Use inline-block to fit content */
        border: none; /* Remove border */
        text-decoration: none; /* Remove underline for links */
        min-width: 100px; /* Set a minimum width */
        height: 40px; /* Set a fixed height for consistency */
        cursor: pointer; /* Pointer cursor for button */
    }

    /* Deactivate button style */
    .btn-delete {
        margin-top: 19px; /* Adjust margin for better spacing */
        background-color: #c9302c; /* Dark red for Deactivate button */
        color: white;
        border-radius: 15px; /* Rounded corners */
        padding: 10px 15px; /* Adjust padding for a better fit */
        font-size: 15px; /* Font size */
        font-weight: bold;
        text-align: center; /* Center text */
        display: inline-block; /* Use inline-block to fit content */
        border: none; /* Remove border */
        min-width: 100px; /* Set a minimum width */
        height: 40px; /* Set a fixed height for consistency */
        cursor: pointer; /* Pointer cursor for button */
    }
</style>

@endsection
