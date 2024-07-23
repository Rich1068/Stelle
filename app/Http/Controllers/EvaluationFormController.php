<?php

namespace App\Http\Controllers;

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
}
