@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-clipboard-list"></i> Manage Evaluation Forms
        </h2>
        @if($evaluationForms->isEmpty())
        <form action="{{ route('evaluation-forms.create') }}" method="get" style="display: inline;">
            <div style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="margin-left: 30px; border-radius: 15px;">
                    <i class="fas fa-plus"></i> Add Evaluation Form
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

<div class="container-fluid" style="padding: 0;">
    @if($evaluationForms->isEmpty())
    <div class="no-events-container">
        <i class="fas fa-file-alt"></i>
        <p>No evaluation forms found.</p>
    </div>
    @else
    <div class="card mb-4" style="margin-top: 50px; border: none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Evaluation Form List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="evaluationFormsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Form No.</th>
                            <th>Form Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $counter = 1; @endphp 
                        @foreach($evaluationForms as $index => $form)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#f9f9f9' : 'white' }};">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $form->form_name }}</td>
                            <td>{{ $form->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="button-group" style="display: flex; justify-content: center; align-items: center;">
                                    <form action="{{ route('evaluation-forms.duplicate', $form->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-edit rounded-circle" onclick="return confirm('Are you sure you want to duplicate this form?')">
                                            <i class="fas fa-copy" style="color: white;"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('evaluation-forms.edit', $form->id) }}" class="btn btn-edit rounded-circle me-2">
                                        <i class="fas fa-edit" style="color: white;"></i>
                                    </a>
                                    <form action="{{ route('evaluation-forms.deactivate', $form->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-delete rounded-circle" onclick="return confirm('Are you sure you want to deactivate this form?')">
                                            <i class="fas fa-times" style="color: white;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Button to add evaluation form -->
    <form action="{{ route('evaluation-forms.create') }}" method="get" style="display: inline;">
        <div style="margin-top: 10px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="border-radius: 15px;">
                <i class="fas fa-plus"></i> Add Evaluation Form
            </button>
        </div>
    </form>
    @endif
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#evaluationFormsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10
        });
    });
</script>

<!-- Styles -->
<style>
    .btn-primary {
        background-color: #001e54;
        color: white;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 15px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .custom-thead {
        background-color: #001e54;
        color: white;
    }

    .table td, .table th {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        overflow-wrap: break-word;
    }

    .table tbody tr:hover {
        background-color: #f2f2f2;
    }

    .btn-edit, .btn-delete {
        color: white;
        border-radius: 20px;
        width: 40px;
        height: 40px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        cursor: pointer;
    }

    .btn-edit {
        background-color: #001e54;
    }

    .btn-delete {
        background-color: #c9302c;
    }
</style>

@endsection
