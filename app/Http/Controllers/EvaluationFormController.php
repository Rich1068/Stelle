<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\EvaluationForm;
use Illuminate\Http\Request;

class EvaluationFormController extends Controller
{
    public function store(Request $request, $eventId)
    {

        $form = EvaluationForm::create([
            'event_id' => $eventId,
        ]);

        return redirect()->route('questions.create', ['form' => $form->id]);
    }
    public function update(Request $request, $id, $form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $evaluationForm->touch(); // This updates the `updated_at` timestamp

        return redirect()->route('questions.edit', ['id'=> $id, 'form' => $evaluationForm]);
    }
 
}
