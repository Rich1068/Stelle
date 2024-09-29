<?php

namespace App\Http\Controllers;
use App\Models\EvaluationForm;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function create($formId)
    {
        return view('evaluation_form.questions.create', compact('formId'));
    }

    public function store(Request $request, $formId)
    {
        $request->validate([
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);
    
        $questions = $request->input('questions');
    
        foreach ($questions as $question) {
            $typeId = ($question['type'] === 'radio') ? 2 : 1; // 1 for 'essay', 2 for 'radio'
    
            Question::create([
                'form_id' => $formId,
                'question' => $question['text'],
                'type_id' => $typeId,
            ]);
        }
    
        return redirect()->route('evaluation.evaluationlist');
    }

    public function edit($id, $form)
    {
        $evaluationForm = EvaluationForm::findOrFail($form);
        $questions = Question::where('form_id', $form)->get(); 

        return view('event.evaluation_form.questions.edit', compact('id', 'form', 'questions','evaluationForm'), ['id'=> $id, 'form' => $evaluationForm, 'questions' =>$questions, 'evaluationForm' => $evaluationForm]);
    }


    public function update(Request $request, $id, $formId)
    {
        // Validate the request
        $request->validate([
            'questions' => 'required|array|min:1', // Ensure at least one question is present
            'questions.*.id' => 'nullable|integer', // The ID is optional (for existing questions)
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);

        // Begin a database transaction
        DB::beginTransaction();

        try {
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

        // Redirect to the event view page
        return redirect()->route('event.view', ['id' => EvaluationForm::find($formId)->event_id])
                        ->with('success', 'Questions updated successfully!');
    }


    


}

