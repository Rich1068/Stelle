@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('questions.store', ['id' => $id, 'form' => $formId]) }}" method="POST" class="form-question-container" onsubmit="return validateForm()">
    @csrf

    <!-- Header Text with Icon -->
    <div class="header-text">
        <i class="fas fa-list"></i> Create Evaluation Form
    </div>

    <!-- Input Field for Question -->
    <div class="input-container">
        <input type="text" id="question-input" placeholder="Type your question here..." class="question-input">
    </div>

    <!-- Divider with "Question Type" Text -->
    <div class="divider">
        <span class="divider-text">Question Type</span>
    </div>

    <!-- Buttons to add Essay or Radio questions -->
    <div class="button-container">
        <button type="button" class="add-question-btn" onclick="addRadioQuestion()">Add Question</button>
        <button type="button" class="add-question-btn" onclick="addEssayQuestion()">Add Comment</button>
    </div>

    <!-- Container for the questions -->
    <div id="questions" class="questions-container">
        <!-- Questions will be appended here -->
    </div>

    <!-- Error Message Container -->
    <div id="error-message" class="error-message" style="color: red; display: none;">
        Please add at least one question.
    </div>

    <!-- Save Button -->
    <button type="submit" class="save-questions-btn">Save Questions</button>
</form>

<script>
function addEssayQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return; // Do nothing if the input is empty

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length; // Generate a unique index for each question
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-header">${questionInput}</div>
        <div class="question-content">
            <div class="question-type">Essay Question</div>
        </div>
        <input type="hidden" name="questions[${questionIndex}][text]" value="${questionInput}">
        <input type="hidden" name="questions[${questionIndex}][type]" value="essay">
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
    `;
    questionsDiv.appendChild(newQuestionDiv);

    document.getElementById('question-input').value = ""; // Clear the input field
    document.getElementById('error-message').style.display = 'none'; // Hide the error message if displayed
}

function addRadioQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return; // Do nothing if the input is empty

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length; // Generate a unique index for each question
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-header">${questionInput}</div>
        <div class="question-content">
            <div class="question-type">Radio Question</div>
            <div class="radio-options">
                <label class="options-label">Options:</label>
                <div class="radio-buttons">
                    <input type="radio" name="questions[${questionIndex}][options]" value="1" disabled> 1
                    <input type="radio" name="questions[${questionIndex}][options]" value="2" disabled> 2
                    <input type="radio" name="questions[${questionIndex}][options]" value="3" disabled> 3
                    <input type="radio" name="questions[${questionIndex}][options]" value="4" disabled> 4
                    <input type="radio" name="questions[${questionIndex}][options]" value="5" disabled> 5
                </div>
            </div>
        </div>
        <input type="hidden" name="questions[${questionIndex}][text]" value="${questionInput}">
        <input type="hidden" name="questions[${questionIndex}][type]" value="radio">
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
    `;
    questionsDiv.appendChild(newQuestionDiv);

    document.getElementById('question-input').value = ""; // Clear the input field
    document.getElementById('error-message').style.display = 'none'; // Hide the error message if displayed
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}

function validateForm() {
    const questionsDiv = document.getElementById('questions');
    if (questionsDiv.children.length === 0) {
        document.getElementById('error-message').style.display = 'block'; // Show error message
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
</script>

<!-- Link to the external CSS file -->
<link rel="stylesheet" href="{{ asset('css/form-questions.css') }}">

@endsection
