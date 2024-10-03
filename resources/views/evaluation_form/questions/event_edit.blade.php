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
        @foreach($questions as $question)
            <div class="form-group question-group">
                <div class="question-content">
                    <label>{{ $question->type_id == 1 ? 'Essay Question:' : 'Radio Question:' }}</label>
                    
                    <!-- Hidden field for the question ID -->
                    <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}">

                    <!-- Text input for the question text -->
                    <input type="text" name="questions[{{ $loop->index }}][text]" value="{{ $question->question }}" required>

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
function addEssayQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return;

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length;
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label>Essay Question:</label>
            <input type="text" name="questions[${questionIndex}][text]" value="${questionInput}" required>
            <div class="essay-underline"></div>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
        <input type="hidden" name="questions[${questionIndex}][type]" value="essay">
    `;
    questionsDiv.appendChild(newQuestionDiv);

    document.getElementById('question-input').value = "";
    document.getElementById('error-message').style.display = 'none';
}

function addRadioQuestion() {
    const questionInput = document.getElementById('question-input').value;
    if (questionInput.trim() === "") return;

    const questionsDiv = document.getElementById('questions');
    const questionIndex = questionsDiv.children.length;
    const uniqueRadioName = `radio_${Date.now()}`;
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label>Radio Question:</label>
            <input type="text" name="questions[${questionIndex}][text]" value="${questionInput}" required>
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

    document.getElementById('question-input').value = "";
    document.getElementById('error-message').style.display = 'none';
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}

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
<link rel="stylesheet" href="{{ asset('css/form-questions.css') }}">

@endsection
