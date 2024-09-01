@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('questions.update', ['id' => $id, 'form' => $form]) }}" method="POST" class="form-question-container">
    @method('PUT')
    @csrf

    <!-- Header Text with Icon -->
    <div class="header-text">
        <i class="fas fa-list"></i> Edit Evaluation Form
    </div>

    <!-- Input Field for New Question -->
    <div class="input-container">
        <input type="text" id="question-input" placeholder="Type your question here..." class="question-input">
    </div>

    <!-- Divider with "Question Type" Text -->
    <div class="divider">
        <span class="divider-text">Question Type</span>
    </div>

    <!-- Existing Questions -->
    <div id="questions">
        <!-- Existing questions will be displayed here -->
        @foreach($questions as $question)
            <div class="form-group question-group">
                <div class="question-content">
                    <label for="questions[]">
                        {{ $question->type_id == 1 ? 'Essay Question:' : 'Radio Question:' }}
                    </label>
                    <input type="text" name="questions[]" value="{{ $question->question }}" required>
                    @if($question->type_id == 1)
                        <div class="essay-underline"></div>
                    @else
                        <div class="radio-options">
                            <label>Options:</label>
                            <div>
                                <input type="radio" name="radio_{{ $question->id }}" value="1" disabled> 1
                                <input type="radio" name="radio_{{ $question->id }}" value="2" disabled> 2
                                <input type="radio" name="radio_{{ $question->id }}" value="3" disabled> 3
                                <input type="radio" name="radio_{{ $question->id }}" value="4" disabled> 4
                                <input type="radio" name="radio_{{ $question->id }}" value="5" disabled> 5
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
                <input type="hidden" name="question_type[]" value="{{ $question->type_id == 1 ? 'essay' : 'radio' }}">
            </div>
        @endforeach
    </div>

    <!-- Buttons to add Essay or Radio questions -->
    <div class="button-container">
        <button type="button" class="add-question-btn" onclick="addEssayQuestion()">Add Essay Question</button>
        <button type="button" class="add-question-btn" onclick="addRadioQuestion()">Add Radio Question</button>
    </div>

    <!-- Save Button -->
    <button type="submit" class="save-questions-btn">Update Questions</button>
</form>

<script>
function addEssayQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return; // Do nothing if the input is empty

    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label for="questions[]">Essay Question:</label>
            <input type="text" name="questions[]" value="${questionInput}" required>
            <div class="essay-underline"></div>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
        <input type="hidden" name="question_type[]" value="essay">
    `;
    questionsDiv.appendChild(newQuestionDiv);

    document.getElementById('question-input').value = ""; // Clear the input field
}

function addRadioQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return; // Do nothing if the input is empty

    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    const uniqueRadioName = `radio_${Date.now()}`; // Unique name for each set of radio buttons
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label for="questions[]">Radio Question:</label>
            <input type="text" name="questions[]" value="${questionInput}" required>
            <div class="radio-options">
                <label>Options:</label>
                <div>
                    <input type="radio" name="${uniqueRadioName}" value="1" disabled> 1
                    <input type="radio" name="${uniqueRadioName}" value="2" disabled> 2
                    <input type="radio" name="${uniqueRadioName}" value="3" disabled> 3
                    <input type="radio" name="${uniqueRadioName}" value="4" disabled> 4
                    <input type="radio" name="${uniqueRadioName}" value="5" disabled> 5
                </div>
            </div>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
        <input type="hidden" name="question_type[]" value="radio">
    `;
    questionsDiv.appendChild(newQuestionDiv);

    document.getElementById('question-input').value = ""; // Clear the input field
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}
</script>

<!-- Link to the external CSS file -->
<link rel="stylesheet" href="{{ asset('css/form-questions.css') }}">

@endsection
