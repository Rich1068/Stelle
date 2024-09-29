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
                </tr>
            </thead>
            <tbody>
                @foreach($evaluationForms as $form)
                <tr>
                    <td>{{ $form->id }}</td>
                    <td>{{ $form->name ?? 'Untitled' }}</td> <!-- Optional: Add a form name field -->
                    <td>{{ $form->status->status }}</td>
                    <td>{{ $form->creator->first_name }}</td>
                    <td>{{ $form->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
