<?php

namespace App\Http\Controllers;
use App\Models\EvaluationForm;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create($formId)
    {
        return view('event.evaluation_form.questions.create', compact('formId'));
    }

    public function store(Request $request, $formId)
    {
        $request->validate([
            'questions.*' => 'required|string',
        ]);

        foreach ($request->input('questions') as $question) {
            Question::create([
                'form_id' => $formId,
                'question' => $question,
            ]);
        }

        return redirect()->route('event.view', ['id' => EvaluationForm::find($formId)->event_id]);
    }
}
