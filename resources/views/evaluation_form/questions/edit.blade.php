@extends('layouts.app')

@section('body')

<!-- Main Form Container -->
<form action="{{ route('evaluation-forms.update', ['id' => $form]) }}" method="POST" class="form-question-container" onsubmit="return validateForm()">
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
    @foreach($questions as $index => $question)
        <div class="form-group question-group" data-order="{{ $index }}">
            <div class="question-content">
                <label>{{ $question->type_id == 1 ? 'Essay Question:' : 'Radio Question:' }}</label>
                
                <!-- Hidden field for the question ID -->
                <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}">

                <!-- Hidden field for question order -->
                <input type="hidden" name="questions[{{ $loop->index }}][order]" value="{{ $index }}">

                <!-- Text input for the question text -->
                <textarea name="questions[{{ $loop->index }}][text]" required>{{ $question->question }}</textarea>

                @if($question->type_id == 1)
                    <div class="essay-underline"></div>
                @else
                    <div class="radio-options">
                        <label>Options:</label>
                        <div>
                            <!-- Radio buttons without 'name' to exclude them from form submission -->
                            <input type="radio" readonly value="1" disabled> 1
                            <input type="radio" readonly value="2" disabled> 2
                            <input type="radio" readonly value="3" disabled> 3
                            <input type="radio" readonly value="4" disabled> 4
                            <input type="radio" readonly value="5" disabled> 5
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// Initialize SortableJS on the questions container
document.addEventListener('DOMContentLoaded', function () {
    const questionsContainer = document.getElementById('questions');

    Sortable.create(questionsContainer, {
        animation: 150,
        onEnd: function () {
            // Update order and name attributes after sorting
            const questionGroups = questionsContainer.querySelectorAll('.question-group');
            questionGroups.forEach((group, index) => {
                // Update the order hidden input field
                group.querySelector('input[name^="questions"][name$="[order]"]').value = index;

                // Update name attributes to reflect the new order
                group.querySelectorAll('textarea, input[type="hidden"]').forEach(input => {
                    const nameAttr = input.getAttribute('name');
                    if (nameAttr) {
                        const updatedName = nameAttr.replace(/\[.*?\]/, `[${index}]`);
                        input.setAttribute('name', updatedName);
                    }
                });
            });
        }
    });
});

// Auto-expand textarea based on content
function autoExpandTextarea(element) {
    element.style.height = 'auto';
    element.style.height = `${element.scrollHeight}px`;
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
    const questionIndex = questionsDiv.children.length; // Track index based on current number of questions

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
        <input type="hidden" name="questions[${questionIndex}][order]" value="${questionIndex}"> <!-- Add order field -->
        <input type="hidden" name="questions[${questionIndex}][id]" value="">
    `;

    questionsDiv.appendChild(newQuestionDiv);

    // Clear the input field and hide any error messages
    questionInput.value = "";
    document.getElementById('error-message').style.display = 'none';
    recalculateOrder();
}

// Function to add a new radio question
function addRadioQuestion() {
    const questionInput = document.getElementById('question-input');
    const questionText = questionInput.value.trim();
    if (questionText === "") return;

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length; // Track index based on current number of questions
    const uniqueRadioName = `radio_${Date.now()}`; // Unique name for each radio question group

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
        <input type="hidden" name="questions[${questionIndex}][order]" value="${questionIndex}"> <!-- Add order field -->
    `;

    questionsDiv.appendChild(newQuestionDiv);

    // Clear the input field and hide any error messages
    questionInput.value = "";
    document.getElementById('error-message').style.display = 'none';
    recalculateOrder();
}

// Function to remove a question
function removeQuestion(button) {
    if (confirm("Are you sure you want to remove this question?")) {
        const questionDiv = button.parentElement;
        questionDiv.remove();
        recalculateOrder();
    }
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
function recalculateOrder() {
    const questionsDiv = document.getElementById('questions');
    const questionGroups = questionsDiv.querySelectorAll('.question-group');

    questionGroups.forEach((group, index) => {
        // Update the order hidden input field for each question
        group.querySelector('input[name$="[order]"]').value = index;

        // Update the name attributes to maintain proper indexing
        group.querySelectorAll('textarea, input[type="hidden"]').forEach(input => {
            const nameAttr = input.getAttribute('name');
            if (nameAttr) {
                const updatedName = nameAttr.replace(/\[.*?\]/, `[${index}]`);
                input.setAttribute('name', updatedName);
            }
        });
    });
}
</script>

<!-- Link to the external CSS file -->
<style>
    /* Style for the form container */
    .form-question-container {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Style for the header text */
    .header-text {
        font-size: 24px;
        font-weight: bold;
        color: darkblue;
        margin-bottom: 20px;
    }

    /* Style for the expandable question-input textarea */
    .question-input {
        width: 100%;
        min-height: 40px;
        max-height: 150px;
        resize: none;
        overflow-y: hidden;
        padding: 8px;
        font-size: 16px;
        box-sizing: border-box;
        border-radius: 15px;
        border: 1px solid #ccc;
        background-color: white;
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
        border-radius: 15px;
        border: 1px solid #ccc;
        background-color: white;
    }

    /* Button styles */
    .add-question-btn, .save-questions-btn {
        background-color: #001e54;
        color: white;
        border: none;
        border-radius: 15px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .remove-question {
        background-color: #900d09;
        color: white;
        border: none;
        border-radius: 15px;
        padding: 5px 10px;
        cursor: pointer;
        margin-top: 10px;
    }

    .error-message {
        margin-top: 10px;
    }

    /* Style for the divider */
    .divider {
        margin: 20px 0;
        border-top: 2px solid darkblue;
        position: relative;
    }

    .divider-text {
        position: absolute;
        top: -12px;
        left: 20px;
        background-color: white;
        padding: 0 5px;
        font-weight: bold;
    }
</style>

@endsection
