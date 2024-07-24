@extends('layouts.app')

@section('body')

<form action="{{ route('questions.store', $formId) }}" method="POST">
    @csrf
    <div id="questions">
        <div class="form-group question-group">
            <label for="question_type[]">Question Type:</label>
            <select name="question_type[]" onchange="changeQuestionType(this)">
                <option value="essay">Essay</option>
                <option value="matrix">Matrix</option>
            </select>
            <div class="question-content">
                <!-- Essay question by default -->
                <label for="questions[]">Question:</label>
                <input type="text" name="questions[]" required>
            </div>
            <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
        </div>
    </div>
    <button type="button" onclick="addQuestion()">Add Another Question</button>
    <button type="submit">Save Questions</button>
</form>

<script>
function addQuestion() {
    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <label for="question_type[]">Question Type:</label>
        <select name="question_type[]" onchange="changeQuestionType(this)">
            <option value="essay">Essay</option>
            <option value="matrix">Matrix</option>
        </select>
        <div class="question-content">
            <!-- Essay question by default -->
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
    `;
    questionsDiv.appendChild(newQuestionDiv);
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}

function changeQuestionType(select) {
    const questionContentDiv = select.nextElementSibling;
    questionContentDiv.innerHTML = '';

    if (select.value === 'essay') {
        questionContentDiv.innerHTML = `
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
        `;
    } else if (select.value === 'matrix') {
        questionContentDiv.innerHTML = `
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
            <div class="matrix-options">
                <label>Options:</label>
                <input type="text" name="matrix_options[]" required placeholder="Option 1,Option 2,Option 3,Option 4,Option 5">
            </div>
            <div class="matrix-rows">
                <label>Rows:</label>
                <button type="button" onclick="addMatrixRow(this)">Add Row</button>
            </div>
        `;
    }
}

function addMatrixRow(button) {
    const matrixRowsDiv = button.parentElement;
    const newRowDiv = document.createElement('div');
    newRowDiv.classList.add('matrix-row');
    newRowDiv.innerHTML = `
        <label for="matrix_rows[]">Row Label:</label>
        <input type="text" name="matrix_rows[]" required>
        <button type="button" onclick="removeMatrixRow(this)">Remove Row</button>
    `;
    matrixRowsDiv.appendChild(newRowDiv);
}

function removeMatrixRow(button) {
    const rowDiv = button.parentElement;
    rowDiv.remove();
}
</script>

@endsection
