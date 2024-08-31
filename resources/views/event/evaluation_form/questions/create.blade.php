@extends('layouts.app')

@section('body')

<form action="{{ route('questions.store', ['id' => $id, 'form' => $formId]) }}" method="POST">
    @csrf
    <div id="questions">
        <!-- Questions will be appended here -->
    </div>
    <!-- Buttons to add Essay or Radio questions -->
    <button type="button" onclick="addEssayQuestion()">Add Essay Question</button>
    <button type="button" onclick="addRadioQuestion()">Add Radio Question</button>
    <button type="submit">Save Questions</button>
</form>

<script>
function addEssayQuestion() {
    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label for="questions[]">Essay Question:</label>
            <input type="text" name="questions[]" required>
            <div class="essay-underline" style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
        </div>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">Remove</button>
    `;
    questionsDiv.appendChild(newQuestionDiv);
}

function addRadioQuestion() {
    const questionsDiv = document.getElementById('questions');
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.classList.add('form-group', 'question-group');
    const uniqueRadioName = `radio_${Date.now()}`; // Unique name for each set of radio buttons
    newQuestionDiv.innerHTML = `
        <div class="question-content">
            <label for="questions[]">Radio Question:</label>
            <input type="text" name="questions[]" required>
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
    `;
    questionsDiv.appendChild(newQuestionDiv);
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}
</script>

@endsection
