<?php

namespace App\Http\Controllers;
use App\Models\EventEvaluationForm;
use App\Models\Question;
use App\Models\Event;
use App\Models\User;
use App\Models\EvaluationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluationFormController extends Controller
{

    public function evalList()
    {
        $evaluationForms = EvaluationForm::with('creator', 'status')
                                          ->where('created_by', Auth::id())
                                          ->where('status_id', 1)
                                          ->get();
        
        // Return the view with the evaluation forms
        return view('evaluation_form.evaluationlist', compact('evaluationForms'));
    }

    public function create()
    {
        return view('evaluation_form.questions.create');
    }


    public function store(Request $request)
    {
        // Validate the form name and questions
        $request->validate([
            'form_name' => 'required|string|max:255',
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);

        DB::beginTransaction();

        try {
            // Create the evaluation form
            $form = EvaluationForm::create([
                'form_name' => $request->input('form_name'),  // Keep 'form_name' as your database column
                'created_by' => auth()->id(), // Store the creator's ID
                'status_id' => 1, // Assuming status ID 2 is 'Inactive' or the default status
            ]);

            // Handle the questions
            $questions = $request->input('questions');

            foreach ($questions as $question) {
                $typeId = ($question['type'] === 'radio') ? 2 : 1; // 1 for 'essay', 2 for 'radio'

                Question::create([
                    'form_id' => $form->id, // Use the newly created form's ID
                    'question' => $question['text'],
                    'type_id' => $typeId,
                ]);
            }

            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            Log::error('Form creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors('An error occurred while saving the form. Please try again.');
        }

        // Redirect to the evaluation form list or wherever appropriate
        return redirect()->route('evaluation.evaluationlist');
    }

    public function event_create($id)
    {
        return view('evaluation_form.questions.event_create', compact('id'));
    }

    public function event_store(Request $request, $id)
    {
        // Validate the form name and questions
        $request->validate([
            'form_name' => 'required|string|max:255',
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);

        DB::beginTransaction();

        try {
            // Create the evaluation form
            $form = EvaluationForm::create([
                'form_name' => $request->input('form_name'),  // Keep 'form_name' as your database column
                'created_by' => auth()->id(), // Store the creator's ID
                'status_id' => 1, // Assuming status ID 2 is 'Inactive' or the default status
            ]);

            EventEvaluationForm::create([
                'event_id' => $id,
                'form_id'=> $form->id,
                'status_id' => 2
            ]);

            // Handle the questions
            $questions = $request->input('questions');

            foreach ($questions as $question) {
                $typeId = ($question['type'] === 'radio') ? 2 : 1; // 1 for 'essay', 2 for 'radio'

                Question::create([
                    'form_id' => $form->id, // Use the newly created form's ID
                    'question' => $question['text'],
                    'type_id' => $typeId,
                ]);
            }

            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            Log::error('Form creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors('An error occurred while saving the form. Please try again.');
        }

        // Redirect to the evaluation form list or wherever appropriate
        return redirect()->route('event.view', compact('id'));
    }

    public function edit($form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $questions = Question::where('form_id', $form)->get(); 

        return view('evaluation_form.questions.edit', compact( 'form', 'questions','evaluationForm'));
    }

    public function update(Request $request, $formId)
    {
        // Validate the request
        $request->validate([
            'form_name' => 'required|string|max:255',
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.id' => 'nullable|integer', // The ID is optional (for existing questions)
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);
    
        // Begin a database transaction
        DB::beginTransaction();
    
        try {
            // Update the form's `updated_at` timestamp
            $evaluationForm = EvaluationForm::findOrFail($formId);
            $evaluationForm->update([
                'form_name' => $request->input('form_name'), // Update form name
            ]);
            $evaluationForm->touch(); // This updates the `updated_at` timestamp
    
            $updatedQuestionIds = [];
    
            // Loop through each question in the request
            foreach ($request->input('questions') as $question) {
                $typeId = ($question['type'] === 'radio') ? 2 : 1;
    
                if (isset($question['id'])) {
                    // Update existing question
                    $existingQuestion = Question::find($question['id']);
    
                    if ($existingQuestion) {
                        $existingQuestion->update([
                            'question' => $question['text'],
                            'type_id' => $typeId,
                        ]);
                        $updatedQuestionIds[] = $existingQuestion->id;
                    }
                } else {
                    // Create a new question if no ID exists
                    $newQuestion = Question::create([
                        'form_id' => $formId,
                        'question' => $question['text'],
                        'type_id' => $typeId,
                    ]);
                    $updatedQuestionIds[] = $newQuestion->id;
                }
            }
    
            // Delete any questions that were not part of the update
            Question::where('form_id', $formId)
                    ->whereNotIn('id', $updatedQuestionIds)
                    ->delete();
    
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
    
            return redirect()->back()->withErrors(['msg' => 'Failed to update questions. Please try again.']);
        }
    
        // Redirect to the evaluation form list
        return redirect()->route('evaluation.evaluationlist')
                         ->with('success', 'Evaluation Form and questions updated successfully!');
    }

    public function duplicate($id)
    {
        DB::beginTransaction(); // Start the transaction
    
        try {
            // Find the form to duplicate
            $evaluationForm = EvaluationForm::with('questions')->findOrFail($id); // Load related questions
    
            // Duplicate the form
            $newEvaluationForm = $evaluationForm->replicate(); // Clone the original form
            $newEvaluationForm->form_name = $evaluationForm->form_name . ' copy'; // Modify the name
            $newEvaluationForm->created_at = now(); // Set a new created_at timestamp
            $newEvaluationForm->updated_at = null; // Set updated_at to null
            $newEvaluationForm->save(); // Save the new form
    
            // Loop through the associated questions and duplicate them
            foreach ($evaluationForm->questions as $question) {
                $newQuestion = $question->replicate(); // Clone the original question
                $newQuestion->form_id = $newEvaluationForm->id; // Assign it to the new evaluation form
                $newQuestion->created_at = now(); // Set a new created_at timestamp for each question
                $newQuestion->updated_at = null; // Set updated_at to null for each question
                $newQuestion->save(); // Save the new question
            }
    
            DB::commit(); // Commit the transaction if everything is successful
    
            // Redirect back with a success message
            return redirect()->route('evaluation.evaluationlist')->with('success', 'Evaluation form and questions duplicated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if an error occurs
            return redirect()->route('evaluation.evaluationlist')->with('error', 'Error duplicating evaluation form: ' . $e->getMessage());
        }
    }

    public function event_edit($id, $form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $questions = Question::where('form_id', $form)->get(); 

        return view('evaluation_form.questions.event_edit', compact( 'form', 'questions','evaluationForm', 'id'));
    }

    public function event_update(Request $request, $id, $formId)
    {
        // Validate the request
        $request->validate([
            'form_name' => 'required|string|max:255',
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.id' => 'nullable|integer', // The ID is optional (for existing questions)
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);
    
        // Begin a database transaction
        DB::beginTransaction();
    
        try {
            // Update the form's `updated_at` timestamp
            $evaluationForm = EvaluationForm::findOrFail($formId);
            $evaluationForm->update([
                'form_name' => $request->input('form_name'), // Update form name
            ]);
            $evaluationForm->touch(); // This updates the `updated_at` timestamp
    
            $updatedQuestionIds = [];
    
            // Loop through each question in the request
            foreach ($request->input('questions') as $question) {
                $typeId = ($question['type'] === 'radio') ? 2 : 1;
    
                if (isset($question['id'])) {
                    // Update existing question
                    $existingQuestion = Question::find($question['id']);
    
                    if ($existingQuestion) {
                        $existingQuestion->update([
                            'question' => $question['text'],
                            'type_id' => $typeId,
                        ]);
                        $updatedQuestionIds[] = $existingQuestion->id;
                    }
                } else {
                    // Create a new question if no ID exists
                    $newQuestion = Question::create([
                        'form_id' => $formId,
                        'question' => $question['text'],
                        'type_id' => $typeId,
                    ]);
                    $updatedQuestionIds[] = $newQuestion->id;
                }
            }
    
            // Delete any questions that were not part of the update
            Question::where('form_id', $formId)
                    ->whereNotIn('id', $updatedQuestionIds)
                    ->delete();
    
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
    
            return redirect()->back()->withErrors(['msg' => 'Failed to update questions. Please try again.']);
        }
    
        // Redirect to the event view
        return redirect()->route('event.view', compact('id'))
                         ->with('success', 'Evaluation Form and questions updated successfully!');
    }

    public function useExistingForm(Request $request, $id)
    {
        // Validate the selected form
        $request->validate([
            'form_id' => 'required|exists:evaluation_forms,id',
        ]);
    
        // Start a transaction
        DB::beginTransaction();
    
        try {
            // Check if the event already has an associated evaluation form
            $existingAssociation = EventEvaluationForm::where('event_id', $id)->first();
    
            if ($existingAssociation) {
                // Update the existing association
                $existingAssociation->update([
                    'form_id' => $request->input('form_id'),
                    'status_id' => 2, 
                ]);
            } else {
                // Create a new association
                EventEvaluationForm::create([
                    'event_id' => $id,
                    'form_id' => $request->input('form_id'),
                    'status_id' => 2, 
                ]);
            }
    
            // Commit the transaction if everything is successful
            DB::commit();
    
            // Redirect with success message
            return redirect()->route('event.view', compact('id'))->with('success', 'Evaluation form associated successfully!');
        
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            \Log::error('Error associating evaluation form: '.$e->getMessage());
    
            // Redirect back with an error message
            return redirect()->route('event.view', compact('id'))->with('error', 'There was an issue in the process. Please try again.');
        }
    }

    public function deactivate($id)
    {
        // Find the form by ID
        $evaluationForm = EvaluationForm::findOrFail($id);
        
        $linkedEvents = EventEvaluationForm::where('form_id', $evaluationForm->id)->exists();

        if ($linkedEvents) {
            return back()->with('error', 'This form is associated with an event and cannot be deleted.');
        }

        $evaluationForm->update([
            'status_id' => 2, // Setting status to 'Inactive'
        ]);

        // Redirect with a success message
        return redirect()->route('evaluation.evaluationlist')->with('success', 'Evaluation Form deactivated successfully.');
    }
    public function toggleActivation(Request $request, $id, $formId)
    {
        $evaluationForm = EventEvaluationForm::findOrFail($formId);
    
        // Set the status based on `is_active` checkbox value
        $evaluationForm->status_id = $request->is_active ? 1 : 2;
        $evaluationForm->save();
    
        // Return a JSON response instead of a redirect
        return response()->json([
            'success' => true,
            'message' => $evaluationForm->status_id == 1 
                ? 'The evaluation form is now activated and open to participants.' 
                : 'The evaluation form is now deactivated and closed to participants.',
        ]);
    }

    public function take($id, $formId)
    {
        $event = Event::findOrFail($id);
        $evaluationForm = $event->evaluationForm;
        
        // Ensure the form is active
        if ($evaluationForm && $evaluationForm->status_id == 1) {
            $questions = Question::where('form_id', $formId)->get();
            return view('evaluation_form.answer', compact('event', 'questions'));
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
    
        // Begin the transaction
        DB::beginTransaction();
    
        try {
            foreach ($request->answers as $questionId => $answer) {
                Answer::create([
                    'event_form_id' => $event->evaluationForm->id,
                    'question_id' => $questionId,
                    'user_id' => $user->id,
                    'answer' => $answer
                ]);
            }
    
            // Commit the transaction if everything is successful
            DB::commit();
    
            return redirect()->route('event.view', $event->id)->with('success', 'Your evaluation has been submitted successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
    
            \Log::error('Error submitting evaluation: '.$e->getMessage());
    
            return redirect()->route('event.view', $event->id)->with('error', 'There was an issue submitting your evaluation. Please try again.');
        }
    }

    public function showEvaluationResults($id)
    {
        // Fetch the event with its related form, questions, and answers
        $event = Event::with('evaluationForm.evalForm.questions.answers')
            ->where('id', $id)
            ->firstOrFail();

        $eventFormId = $event->evaluationForm->id ?? null; // Get the event's specific form ID

        // Get the number of participants
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->whereHas('user')
            ->count();

        // Define the static radio options (1, 2, 3, 4, 5)
        $staticRadioOptions = [1, 2, 3, 4, 5];

        $form = $event->evaluationForm;
        
        $questionsData = [];

        // Total users who answered, filtered by event_form_id
        $answeredUsers = User::whereHas('answers', function ($query) use ($eventFormId) {
                $query->where('event_form_id', $eventFormId);
            })
            ->distinct()
            ->count();

        if ($form && $form->evalForm->questions) {
            // Process questions for comments and radio questions
            foreach ($form->evalForm->questions as $question) {
                if ($question->isComment()) {
                    $answers = $question->answers()
                        ->where('event_form_id', $eventFormId) // Filter by event_form_id
                        ->pluck('answer');
                    
                    $questionsData[] = [
                        'type' => 'comment',
                        'question' => $question->question,
                        'answers' => $answers,
                    ];
                } elseif ($question->isRadio()) {
                    $answerCounts = $question->answers()
                        ->where('event_form_id', $eventFormId) // Filter by event_form_id
                        ->select('answer', DB::raw('count(*) as count'))
                        ->groupBy('answer')
                        ->get()
                        ->pluck('count', 'answer');

                    $compiledCounts = collect($staticRadioOptions)->mapWithKeys(function ($option) use ($answerCounts) {
                        return [$option => $answerCounts->get($option, 0)];
                    });

                    $totalResponses = $question->answers()->where('event_form_id', $eventFormId)->count();
                    $totalScore = $question->answers()->where('event_form_id', $eventFormId)->sum('answer'); // Assuming answers are numeric
                    $averageScore = $totalResponses > 0 ? round($totalScore / $totalResponses, 2) : 0;

                    $questionsData[] = [
                        'type' => 'radio',
                        'question' => $question->question,
                        'labels' => $staticRadioOptions,
                        'values' => $compiledCounts->values(),
                        'average' => $averageScore
                    ];
                }
            }
        }

        // Calculate participation rate
        $participationRate = $currentParticipants > 0 ? round(($answeredUsers / $currentParticipants) * 100, 2) : 0;

        // Send data to the view
        return view('event.partials.evaluation', compact(
            'questionsData', 'staticRadioOptions', 'answeredUsers', 'currentParticipants', 
            'participationRate'
        ));
    }

 
}
