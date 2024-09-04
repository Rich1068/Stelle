@extends('layouts.app')
@section('body')
<h1>Take Evaluation Form for {{ $event->title }}</h1>

<form action="{{ route('evaluation-form.submit', ['id' => $event->id]) }}" method="POST">
    @csrf
    @foreach($questions as $question)
        <div class="form-group">
            <label>{{ $question->question }}</label>
            @if ($question->type_id == 1) <!-- Essay -->
                <textarea name="answers[{{ $question->id }}]" class="form-control" required></textarea>
            @elseif ($question->type_id == 2) <!-- Radio -->
                <div>
                    @for ($i = 1; $i <= 5; $i++)
                        <label>
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" required>
                            {{ $i }}
                        </label>
                    @endfor
                </div>
            @endif
        </div>
    @endforeach
    <button type="submit" class="btn btn-success">Submit Answers</button>
</form>
@endsection
