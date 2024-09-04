@extends('layouts.app')
@section('body')

<!-- Top Container for Event Title -->
<div class="answer-forms-event-title-container">
    <h1 class="answer-forms-event-title">Evaluation Form For</h1>
    <p class="answer-forms-event-subtitle">{{ $event->title }}</p>
</div>

<!-- Main Form -->
<form action="{{ route('evaluation-form.submit', ['id' => $event->id]) }}" method="POST" class="answer-forms-container">
    @csrf
    @foreach($questions as $question)
        <!-- Individual Question Container -->
        <div class="answer-forms-question-container">
            <!-- Top Dark Blue Header -->
            <div class="answer-forms-question-header">
                <label class="answer-forms-question-title">{{ $loop->iteration }}. {{ $question->question }}</label>
            </div>
            <!-- Body of the Question -->
            <div class="answer-forms-question-body">
                @if ($question->type_id == 1) <!-- Essay -->
                    <textarea name="answers[{{ $question->id }}]}" class="form-control answer-forms-textarea" placeholder="Insert Answer Here" required></textarea>
                @elseif ($question->type_id == 2) <!-- Radio -->
                <div class="answer-forms-radio-group">
    @for ($i = 1; $i <= 5; $i++)
        <label class="answer-forms-radio-label">
            <span class="answer-forms-radio-number">{{ $i }}</span> <!-- Number on top -->
            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" class="answer-forms-radio-input" required>
            <span class="answer-forms-radio-circle"></span> <!-- Radio circle -->
        </label>
    @endfor
</div>

                @endif
            </div>
        </div>
    @endforeach
    <button type="submit" class="btn answer-forms-submit">Submit Answers</button>
</form>

@endsection
