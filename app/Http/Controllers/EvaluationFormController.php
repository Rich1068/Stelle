<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Event;
use App\Models\EvaluationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\DB;

class EvaluationFormController extends Controller
{

    public function evalList()
    {
        // Fetch all evaluation forms with their creator and status
        $evaluationForms = EvaluationForm::with('creator', 'status')->get();
        
        // Return the view with the evaluation forms
        return view('evaluation_form.evaluationlist', compact('evaluationForms'));
    }

    
    public function store(Request $request)
    {
        // Create the evaluation form without tying it to a specific event
        $form = EvaluationForm::create([
            'created_by' => auth()->id(), // Store the creator's ID
            'status_id' => 2, // Assuming status ID 2 is 'Inactive' or the default status
        ]);
    
        return redirect()->route('questions.create', ['id' => $form->id]);
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
    
        // Get the number of participants
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1) // Only accepted participants
            ->count();
    
        // Define the static radio options (1, 2, 3, 4, 5)
        $staticRadioOptions = [1, 2, 3, 4, 5];
    
        // Access the form associated with the event
        $form = $event->evaluationForm;
    
        // Initialize variables for user data
        $questionsData = [];
        $totalUsers = 0;
    
        // Initialize userAges as a Collection, not an array
        $userAges = collect(); // Laravel collection to store individual ages
        $ageRanges = [
            '10-15' => 0,
            '16-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '66+'   => 0,
            'Unknown' => 0, // New label for users with no birthdate
        ];
    
        // Initialize gender count
        $genderDistribution = [
            'Male' => 0,
            'Female' => 0,
            'Unknown' => 0, // To handle users without specified gender
        ];
    
        if ($form) {
            // Collect unique users who answered the form
            $users = DB::table('answers')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->join('users', 'answers.user_id', '=', 'users.id')
                ->where('questions.form_id', $form->id)
                ->distinct('answers.user_id') // Count distinct user IDs
                ->select('users.birthdate', 'users.gender')   // Select user birthdate and gender
                ->get();
    
            // Calculate individual ages and group them into ranges, and track gender distribution
            $users->each(function ($user) use (&$userAges, &$ageRanges, &$genderDistribution) {
                // Handle age
                if ($user->birthdate) {
                    $age = Carbon::parse($user->birthdate)->age;
    
                    // Store individual age in the Collection
                    $userAges->push($age);
    
                    // Group the age into a range
                    if ($age >= 10 && $age <= 15) {
                        $ageRanges['10-15']++;
                    } elseif ($age >= 16 && $age <= 25) {
                        $ageRanges['16-25']++;
                    } elseif ($age >= 26 && $age <= 35) {
                        $ageRanges['26-35']++;
                    } elseif ($age >= 36 && $age <= 45) {
                        $ageRanges['36-45']++;
                    } elseif ($age >= 46 && $age <= 55) {
                        $ageRanges['46-55']++;
                    } elseif ($age >= 56 && $age <= 65) {
                        $ageRanges['56-65']++;
                    } else {
                        $ageRanges['66+']++;
                    }
                } else {
                    $ageRanges['Unknown']++;
                }
    
                // Handle gender
                switch (strtolower($user->gender)) {
                    case 'male':
                        $genderDistribution['Male']++;
                        break;
                    case 'female':
                        $genderDistribution['Female']++;
                        break;
                    default:
                        $genderDistribution['Unknown']++;
                        break;
                }
            });
    
            // Process questions for comments and radio questions
            foreach ($form->questions as $question) {
                if ($question->isComment()) {
                    $questionsData[] = [
                        'type' => 'comment',
                        'question' => $question->question,
                        'answers' => $question->answers->pluck('answer'),
                    ];
                } elseif ($question->isRadio()) {
                    $answerCounts = $question->answers()
                        ->select('answer', DB::raw('count(*) as count'))
                        ->groupBy('answer')
                        ->get()
                        ->pluck('count', 'answer');
    
                    $compiledCounts = collect($staticRadioOptions)->mapWithKeys(function ($option) use ($answerCounts) {
                        return [$option => $answerCounts->get($option, 0)];
                    });
    
                    $questionsData[] = [
                        'type' => 'radio',
                        'question' => $question->question,
                        'labels' => $staticRadioOptions,
                        'values' => $compiledCounts->values(),
                    ];
                }
            }
        }
    
        // Calculate participation rate
        $participationRate = $currentParticipants > 0 ? round(($users->count() / $currentParticipants) * 100, 2) : 0;
    
        // Send data to the view
        return view('event.partials.evaluation', compact(
            'questionsData', 'staticRadioOptions', 'totalUsers', 'currentParticipants', 
            'participationRate', 'userAges', 'ageRanges', 'genderDistribution'
        ));
    }
 
}
