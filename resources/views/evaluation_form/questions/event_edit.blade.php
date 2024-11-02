@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('event-evaluation-forms.update', ['id' =>$id,'formId' => $form]) }}" method="POST" class="form-question-container" onsubmit="return validateForm()">
    @method('PUT')
    @csrf

    <!-- Header Text with Icon -->
    <div class="header-text">
        <i class="fas fa-list"></i> Edit Evaluation Form
    </div>

    <div class="form-group">
        <label for="form_name" class="font-weight-bold">Form Name</label>
        <input type="text" id="form_name" name="form_name" class="form-control" placeholder="Enter form name..." value="{{ old('form_name', $evaluationForm->form_name ?? '') }}" required>
    </div>

    <!-- Divider with "Question Type" Text -->
    <div class="divider">
        <span class="divider-text">Question Type</span>
    </div>

    <!-- Input Field for New Question -->
    <div class="input-container">
        <textarea id="question-input" placeholder="Type your question here..." class="question-input" oninput="autoExpandTextarea(this)"></textarea>
    </div>

    <!-- Existing Questions -->
    <div id="questions">
        @foreach($questions as $question)
            <div class="form-group question-group">
                <div class="question-content">
                    <label>{{ $question->type_id == 1 ? 'Essay Question:' : 'Radio Question:' }}</label>
                    
                    <!-- Hidden field for the question ID -->
                    <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}">

                    <!-- Text input for the question text -->
                    <textarea name="questions[{{ $loop->index }}][text]" required oninput="autoExpandTextarea(this)">{{ $question->question }}</textarea>

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
                <input type="hidden" name="questions[{{ $loop->index }}][type]" value="{{ $question->type_id == 1 ? 'essay' : 'radio' }}">
            </div>
        @endforeach
    </div>

    <!-- Buttons to add Essay or Radio questions -->
    <div class="button-container">
        <button type="button" class="add-question-btn" onclick="addEssayQuestion()">Add Comment</button>
        <button type="button" class="add-question-btn" onclick="addRadioQuestion()">Add Radio Question</button>
    </div>

    <!-- Error Message Container -->
    <div id="error-message" class="error-message" style="color: red; display: none;">
        Please add at least one question.
    </div>

    <!-- Save Button -->
    <button type="submit" class="save-questions-btn">Update Questions</button>
</form>

<script>
// Auto-expand textarea based on content
function autoExpandTextarea(element) {
    element.style.height = 'auto'; // Reset height to auto to calculate scroll height
    element.style.height = `${element.scrollHeight}px`; // Set height based on scroll height
}

// Attach auto-expand to the question-input field
document.getElementById('question-input').addEventListener('input', function () {
    autoExpandTextarea(this);
});

// Function to add a new essay question
function addEssayQuestion() {
    const questionInput = document.getElementById('question-input');
    const questionText = questionInput.value.trim();
    if (questionText === "") return;

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length;

    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label>Essay Question:</label>
            <textarea name="questions[${questionIndex}][text]" required oninput="autoExpandTextarea(this)">${questionText}</textarea>
            <div class="essay-underline"></div>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
        <input type="hidden" name="questions[${questionIndex}][type]" value="essay">
    `;

    questionsDiv.appendChild(newQuestionDiv);

    // Clear the input field and hide any error messages
    questionInput.value = "";
    document.getElementById('error-message').style.display = 'none';
}

// Function to add a new radio question
function addRadioQuestion() {
    const questionInput = document.getElementById('question-input');
    const questionText = questionInput.value.trim();
    if (questionText === "") return;

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length;
    const uniqueRadioName = `radio_${Date.now()}`;

    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label>Radio Question:</label>
            <textarea name="questions[${questionIndex}][text]" required oninput="autoExpandTextarea(this)">${questionText}</textarea>
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
        <input type="hidden" name="questions[${questionIndex}][type]" value="radio">
    `;

    questionsDiv.appendChild(newQuestionDiv);

    // Clear the input field and hide any error messages
    questionInput.value = "";
    document.getElementById('error-message').style.display = 'none';
}

// Function to remove a question
function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}

// Form validation to ensure at least one question is present
function validateForm() {
    const questionsDiv = document.getElementById('questions');
    if (questionsDiv.children.length === 0) {
        document.getElementById('error-message').style.display = 'block';
        return false;
    }
    return true;
}
</script>

<!-- Link to the external CSS file -->
<style>
    /* Style for the form container */
    .form-question-container {
        background-color: white; /* Restore original white background */
        border-radius: 15px; /* Keep rounded corners */
        padding: 20px; /* Add padding for spacing */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional shadow for depth */
    }

    /* Style for the header text */
    .header-text {
        font-size: 24px;
        font-weight: bold;
        color: darkblue; /* Restore the color of the header text */
        margin-bottom: 20px;
    }

    /* Style for the expandable question-input textarea */
    .question-input {
        width: 100%;
        min-height: 40px;  /* Initial height */
        max-height: 150px; /* Maximum height */
        resize: none;      /* Disable manual resize */
        overflow-y: hidden; /* Hide scroll initially */
        padding: 8px;
        font-size: 16px;
        box-sizing: border-box;
        border-radius: 15px; /* Rounded corners */
        border: 1px solid #ccc; /* Light border */
        background-color: white; /* White background for input */
    }

    /* Styling for dynamically added question textareas */
    .question-group textarea {
        width: 100%;
        min-height: 40px;
        resize: none;
        overflow-y: hidden;
        padding: 8px;
        font-size: 16px;
        box-sizing: border-box;
        border-radius: 15px; /* Rounded corners */
        border: 1px solid #ccc; /* Light border */
        background-color: white; /* White background for input */
    }

    /* Button styles */
    .add-question-btn, .save-questions-btn {
        background-color: #001e54; /* Original button color */
        color: white; /* White text */
        border: none;
        border-radius: 15px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px; /* Add space above buttons */
    }

    .remove-question {
        background-color: #900d09; /* Color for remove button */
        color: white;
        border: none;
        border-radius: 15px;
        padding: 5px 10px;
        cursor: pointer;
        margin-top: 10px; /* Add space above remove button */
    }

    .error-message {
        margin-top: 10px; /* Space above the error message */
    }

    /* Style for the divider */
    .divider {
        margin: 20px 0;
        border-top: 2px solid darkblue; /* Original divider color */
        position: relative;
    }

    .divider-text {
        position: absolute;
        top: -12px;
        left: 20px; /* Position text over the divider */
        background-color: white; /* Background color to match form */
        padding: 0 5px; /* Padding for text visibility */
        font-weight: bold; /* Bold text */
    }
</style>

@endsection
