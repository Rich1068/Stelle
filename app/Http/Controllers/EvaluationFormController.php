<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Event;
use App\Models\EvaluationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
class EvaluationFormController extends Controller
{
    public function store(Request $request, $eventId)
    {

        $form = EvaluationForm::create([
            'event_id' => $eventId,
            'status_id' => 2
        ]);

        return redirect()->route('questions.create', ['id' => $eventId,'form' => $form->id]);
    }
    public function update(Request $request, $id, $form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $evaluationForm->touch(); // This updates the `updated_at` timestamp

        return redirect()->route('questions.edit', ['id'=> $id, 'form' => $evaluationForm]);
    }
    public function toggleActivation(Request $request, $id, $formId)
    {
        $evaluationForm = EvaluationForm::findOrFail($formId);
    
        // Set the status based on the checkbox state
        $evaluationForm->status_id = $request->has('is_active') ? 1 : 2;
        $evaluationForm->save();
    
        // Redirect back to the same page
        return redirect()->route('event.view', ['id' => $evaluationForm->event_id]);
    }

    public function take($id, $formId)
    {
        $event = Event::findOrFail($id);
        $evaluationForm = $event->evaluationForm;
        
        // Ensure the form is active
        if ($evaluationForm && $evaluationForm->status_id == 1) {
            $questions = Question::where('form_id', $evaluationForm->id)->get();
            return view('event.evaluation_form.answer', compact('event', 'questions'));
        } else {
            return redirect()->back()->with('error', 'The evaluation form is not available.');
        }
    }

    public function submit(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);

        foreach ($request->answers as $questionId => $answer) {
            Answer::create([
                'form_id' => $event->evaluationForm->id,
                'question_id' => $questionId,
                'event_id' => $event->id,
                'user_id' => $user->id,
                'answer' => $answer
            ]);
        }

        return redirect()->route('event.view', $event->id)->with('success', 'Your evaluation has been submitted successfully.');
    }

 
}
