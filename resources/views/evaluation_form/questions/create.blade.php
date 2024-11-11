@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('evaluation-forms.store') }}" method="POST" class="form-question-container" onsubmit="return validateForm()">
    @csrf

    <!-- Header Text with Icon -->
    <div class="header-text">
        <i class="fas fa-list"></i> Create Evaluation Form Questions
    </div>

    <div class="form-group">
        <label for="form_name" class="font-weight-bold">Form Name</label>
        <input type="text" id="form_name" name="form_name" class="form-control" placeholder="Enter form name..." value="{{ old('form_name') }}" required>
    </div>

    <!-- Input Field for Question -->
    <div class="input-container">
        <input type="text" id="question-input" placeholder="Type your question here..." class="question-input">
    </div>

    <!-- Buttons to Add Questions -->
    <div class="button-container">
        <button type="button" class="add-question-btn" onclick="addQuestion('essay')">Add Comment Question</button>
        <button type="button" class="add-question-btn" onclick="addQuestion('radio')">Add Radio Question</button>
    </div>

    <!-- Container for the Questions -->
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const questionsDiv = document.getElementById('questions');

    // Initialize SortableJS on the questions container
    Sortable.create(questionsDiv, {
        animation: 150,
        onEnd: function () {
            recalculateOrder(); // Update order after dragging
        }
    });
});
function addQuestion(type) {
    const questionInput = document.getElementById('question-input').value.trim();
    const errorMessage = document.getElementById('error-message');

    // Show error if input is empty
    if (questionInput === "") {
        errorMessage.innerText = "Please type a question before adding.";
        errorMessage.style.display = 'block';
        return;
    }

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length;
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');

    // Build question HTML based on type
    if (type === 'essay') {
        newQuestionDiv.innerHTML = `
            <div class="question-header">
                <textarea oninput="autoExpand(this)" name="questions[${questionIndex}][text]" class="editable-question-input" placeholder="Edit your question">${questionInput}</textarea>
            </div>
            <div class="question-type">Comment Question</div>
        `;
    } else if (type === 'radio') {
        newQuestionDiv.innerHTML = `
            <div class="question-header">
                <textarea oninput="autoExpand(this)" name="questions[${questionIndex}][text]" class="editable-question-input" placeholder="Edit your question">${questionInput}</textarea>
            </div>
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
        `;
    }

    // Add hidden inputs for type and order, and remove button
    newQuestionDiv.innerHTML += `
        <input type="hidden" name="questions[${questionIndex}][type]" value="${type}">
        <input type="hidden" name="questions[${questionIndex}][order]" value="${questionIndex}">
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
    `;

    // Append the new question and clear input
    questionsDiv.appendChild(newQuestionDiv);
    document.getElementById('question-input').value = "";
    errorMessage.style.display = 'none'; // Hide the error message

    // Recalculate order after adding
    recalculateOrder();
}

function autoExpand(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function removeQuestion(button) {
    if (confirm("Are you sure you want to remove this question?")) {
        const questionDiv = button.parentElement;
        questionDiv.remove();
        recalculateOrder(); 
    }
}

function recalculateOrder() {
    const questionsDiv = document.getElementById('questions');
    const questionGroups = questionsDiv.querySelectorAll('.question-group');

    questionGroups.forEach((group, index) => {
        // Update the order hidden input field for each question
        group.querySelector('input[name$="[order]"]').value = index;

        // Update name attributes to maintain proper indexing
        group.querySelectorAll('textarea, input[type="hidden"]').forEach(input => {
            const nameAttr = input.getAttribute('name');
            if (nameAttr) {
                const updatedName = nameAttr.replace(/\[.*?\]/, `[${index}]`);
                input.setAttribute('name', updatedName);
            }
        });
    });
}

function validateForm() {
    const questionsDiv = document.getElementById('questions');
    const errorMessage = document.getElementById('error-message');

    // If no questions are present, show error message
    if (questionsDiv.children.length === 0) {
        errorMessage.innerText = "Please add at least one question.";
        errorMessage.style.display = 'block';
        return false;
    }
    return true; // Form is valid, allow submission
}
</script>

<style>
.editable-question-input {
    background-color: #003d8b; /* Dark blue background */
    color: #ffffff; /* White text */
    border: 2px solid #ffffff; /* White border */
    border-radius: 9px; /* Rounded corners */
    padding: 8px; /* Padding for the textarea */
    width: 100%; /* Full width */
    font-size: 1rem; /* Font size */
    resize: none; /* Disable resizing */
    overflow: hidden; /* Hide scrollbars */
    min-height: 40px; /* Minimum height */
}

.editable-question-input::placeholder {
    color: #ffffff; /* Placeholder text color */
    opacity: 0.7; /* Placeholder text opacity */
}

.question-group .question-header {
    margin-bottom: 8px; /* Space below header */
    display: flex; /* Flexbox for alignment */
    justify-content: center; /* Centering */
}

.question-type {
    font-weight: bold; /* Bold text */
    margin-top: 5px; /* Space above the question type text */
    text-align: center; /* Center align */
}

.question-group {
    text-align: center; /* Center contents of the question group */
}
</style>

<!-- Link to the external CSS file -->
<link rel="stylesheet" href="{{ asset('css/form-questions.css') }}">

@endsection
