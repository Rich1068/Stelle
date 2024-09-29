@extends('layouts.app')

@section('body')
<div class="container">
    <h1>Evaluation Forms</h1>

    <!-- Add Evaluation Form Button -->
    <div class="mb-3">
        <a href="{{ route('evaluation-forms.create') }}" class="btn btn-primary">Add Evaluation Form</a>
    </div>

    @if($evaluationForms->isEmpty())
        <p>No evaluation forms found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Form Name</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th> <!-- New column for actions (Edit/Deactivate) -->
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
                        <a href="{{ route('evaluation-forms.edit', $form->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Deactivate Button (Change status to 'Inactive') -->
                        <form action="{{ route('evaluation-forms.deactivate', $form->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH') <!-- Using PATCH instead of DELETE -->
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to deactivate this form?')">Deactivate</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
