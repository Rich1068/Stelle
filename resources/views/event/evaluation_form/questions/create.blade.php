@extends('layouts.app')
@section('body')

<form action="{{ route('questions.store', $formId) }}" method="POST">
    @csrf
    <div id="questions">
        <div class="form-group">
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
        </div>
    </div>
    <button type="button" onclick="addQuestion()">Add Another Question</button>
    <button type="submit">Save Questions</button>
</form>

<script>
function addQuestion() {
    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group');
    newQuestionDiv.innerHTML = `
        <label for="questions[]">Question:</label>
        <input type="text" name="questions[]" required>
    `;
    questionsDiv.appendChild(newQuestionDiv);
}
</script>
@endsection