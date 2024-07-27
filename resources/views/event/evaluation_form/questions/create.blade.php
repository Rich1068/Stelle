@extends('layouts.app')

@section('body')

<form action="{{ route('questions.store', ['id' => $id, 'form' => $formId]) }}" method="POST">
    @csrf
    <div id="questions">
        <div class="form-group question-group">
            <label for="question_type[]">Question Type:</label>
            <select name="question_type[]" onchange="changeQuestionType(this)">
                <option value="essay">Essay</option>
                <option value="radio">Radio</option>
            </select>
            <div class="question-content">
                <!-- Essay question by default -->
                <label for="questions[]">Question:</label>
                <input type="text" name="questions[]" required>
                <div class="essay-underline" style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
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
            <option value="radio">Radio</option>
        </select>
        <div class="question-content">
            <!-- Essay question by default -->
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
            <div class="essay-underline" style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
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
            <div class="essay-underline" style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
        `;
    } else if (select.value === 'radio') {
        questionContentDiv.innerHTML = `
            <label for="questions[]">Question:</label>
            <input type="text" name="questions[]" required>
            <div class="radio-options">
                <label>Options:</label>
                <div>
                    <input type="radio" name="radio_${Date.now()}" value="1" disabled> 1
                    <input type="radio" name="radio_${Date.now()}" value="2" disabled> 2
                    <input type="radio" name="radio_${Date.now()}" value="3" disabled> 3
                    <input type="radio" name="radio_${Date.now()}" value="4" disabled> 4
                    <input type="radio" name="radio_${Date.now()}" value="5" disabled> 5
                </div>
            </div>
        `;
    }
}
</script>

@endsection
