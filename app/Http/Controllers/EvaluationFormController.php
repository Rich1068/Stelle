<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Event;
use App\Models\EvaluationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\DB;

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

    public function showEvaluationResults($id)
    {
        // Fetch the event with its related form, questions, and answers
        $event = Event::with('evaluationForm.questions.answers')
            ->where('id', $id)
            ->firstOrFail();
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1) // Only accepted will show
            ->count();
        // Define the static radio options (1, 2, 3, 4, 5)
        $staticRadioOptions = [1, 2, 3, 4, 5];

        // Access the single form associated with the event
        $form = $event->evaluationForm;

        // Initialize an empty array to hold the unified list of questions (comments and radio)
        $questionsData = [];
        $totalUsers = 0;

        if ($form) {
            // Collect unique users who answered the form
            $totalUsers = DB::table('answers')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->where('questions.form_id', $form->id)
                ->distinct('answers.user_id') // Count distinct user IDs
                ->count('answers.user_id');

            foreach ($form->questions as $question) {
                // Check if the question is a comment or radio type
                if ($question->isComment()) {
                    // Collect comment answers in the unified list
                    $questionsData[] = [
                        'type' => 'comment',
                        'question' => $question->question,
                        'answers' => $question->answers->pluck('answer'),
                    ];
                } elseif ($question->isRadio()) {
                    // Compile data for the radio question into a bar chart format
                    $answerCounts = $question->answers()
                        ->select('answer', DB::raw('count(*) as count'))
                        ->groupBy('answer')
                        ->get()
                        ->pluck('count', 'answer'); // Keyed collection: answer => count

                    // Ensure all static radio options are present, with a count of 0 if not selected
                    $compiledCounts = collect($staticRadioOptions)->mapWithKeys(function ($option) use ($answerCounts) {
                        return [$option => $answerCounts->get($option, 0)];
                    });

                    $questionsData[] = [
                        'type' => 'radio',
                        'question' => $question->question,
                        'labels' => $staticRadioOptions,
                        'values' => $compiledCounts->values(),  // Extract counts in the correct order
                    ];
                }
            }
        }
        $participationRate = $currentParticipants > 0 ? round(($totalUsers / $currentParticipants) * 100, 2) : 0;

        // Send the unified list of questions (comments and radio) and the total user count to the view
        return view('event.partials.evaluation', compact('questionsData', 'staticRadioOptions', 'totalUsers', 'currentParticipants'));
    }
 
}
