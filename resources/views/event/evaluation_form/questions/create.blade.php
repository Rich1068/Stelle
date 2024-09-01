@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('questions.store', ['id' => $id, 'form' => $formId]) }}" method="POST" class="form-question-container">
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
        <button type="button" class="add-question-btn" onclick="addEssayQuestion()">Add Essay Question</button>
        <button type="button" class="add-question-btn" onclick="addRadioQuestion()">Add Radio Question</button>
    </div>

    <!-- Save Button -->
    <button type="submit" class="save-questions-btn">Save Questions</button>
</form>

<!-- Container for the questions -->
<div id="questions" class="questions-container">
    <!-- Questions will be appended here -->
</div>

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
}

function addRadioQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return; // Do nothing if the input is empty

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length; // Generate a unique index for each question
    const uniqueRadioName = `radio_${Date.now()}`; // Unique name for each set of radio buttons
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-header">${questionInput}</div>
        <div class="question-content">
            <div class="question-type">Radio Question</div>
            <div class="radio-options">
                <label class="options-label">Options:</label>
                <div class="radio-buttons">
                    <input type="radio" name="questions[${questionIndex}][${uniqueRadioName}]" value="1" disabled> 1
                    <input type="radio" name="questions[${questionIndex}][${uniqueRadioName}]" value="2" disabled> 2
                    <input type="radio" name="questions[${questionIndex}][${uniqueRadioName}]" value="3" disabled> 3
                    <input type="radio" name="questions[${questionIndex}][${uniqueRadioName}]" value="4" disabled> 4
                    <input type="radio" name="questions[${questionIndex}][${uniqueRadioName}]" value="5" disabled> 5
                </div>
            </div>
        </div>
        <input type="hidden" name="questions[${questionIndex}][text]" value="${questionInput}">
        <input type="hidden" name="questions[${questionIndex}][type]" value="radio">
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
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
