<?php

namespace App\Http\Controllers;
use App\Models\EvaluationForm;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create($id, $formId)
    {
        return view('event.evaluation_form.questions.create', compact('id','formId'));
    }

    public function store(Request $request,$id, $formId)
    {
        $request->validate([
            'questions.*' => 'required|string',
            'question_type.*' => 'required|string|in:essay,radio',
        ]);

        foreach ($request->input('questions') as $index => $question) {
            $type = $request->input('question_type')[$index];
            $typeId = ($type === 'essay') ? 1 : 2;

            Question::create([
                'form_id' => $formId,
                'question' => $question,
                'type_id' => $typeId,
            ]);
        }

        return redirect()->route('event.view', ['id' => EvaluationForm::find($formId)->event_id]);
    }

    public function edit($id, $form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $questions = Question::where('form_id', $form)->get(); 

        return view('event.evaluation_form.questions.edit', compact('id', 'form', 'questions','evaluationForm'), ['id'=> $id, 'form' => $evaluationForm, 'questions' =>$questions, 'evaluationForm' => $evaluationForm]);
    }


    public function update(Request $request, $id, $form)
    {
    $request->validate([
        'questions.*' => 'required|string',
        'question_type.*' => 'required|string|in:essay,radio',
    ]);

    // Delete existing questions
    Question::where('form_id', $form)->delete();

    // Save updated questions
    foreach ($request->input('questions') as $index => $question) {
        $type = $request->input('question_type')[$index];
        $typeId = ($type === 'essay') ? 1 : 2;

        Question::create([
            'form_id' => $form,
            'question' => $question,
            'type_id' => $typeId,
        ]);
    }

    // Redirect to the event view page
    return redirect()->route('event.view', ['id' => $id]);
    }


}

