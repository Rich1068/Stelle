@extends('layouts.app')

@section('body')

<form action="{{ route('questions.update', ['id' => $id, 'form' => $form]) }}" method="POST">
    @method('PUT')
    @csrf
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
                        <div class="essay-underline" style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
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
    <button type="button" onclick="addEssayQuestion()">Add Essay Question</button>
    <button type="button" onclick="addRadioQuestion()">Add Radio Question</button>
    <button type="submit">Update Questions</button>
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
        <input type="hidden" name="question_type[]" value="essay">
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
        <input type="hidden" name="question_type[]" value="radio">
    `;
    questionsDiv.appendChild(newQuestionDiv);
}

function removeQuestion(button) {
    const questionDiv = button.parentElement;
    questionDiv.remove();
}
</script>

@endsection
