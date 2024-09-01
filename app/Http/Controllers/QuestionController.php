<?php

namespace App\Http\Controllers;
use App\Models\EvaluationForm;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function create($id, $formId)
    {
        return view('event.evaluation_form.questions.create', compact('id','formId'));
    }

    public function store(Request $request, $id, $formId)
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
        // Validate the request
        $request->validate([
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:essay,radio',
        ]);
    
        // Begin a database transaction to ensure data consistency
        DB::beginTransaction();
    
        try {
            // Delete existing questions for the form
            Question::where('form_id', $form)->delete();
    
            // Save updated questions
            foreach ($request->input('questions') as $question) {
                $type = $question['type'];
                $typeId = ($type === 'essay') ? 1 : 2;
    
                Question::create([
                    'form_id' => $form,
                    'question' => $question['text'],
                    'type_id' => $typeId,
                ]);
            }
    
            // Commit the transaction if everything is fine
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
    
            // Redirect if error
            return redirect()->back()->withErrors(['msg' => 'Failed to update questions. Please try again.']);
        }
    
        // Redirect to the event view page
        return redirect()->route('event.view', ['id' => $id])->with('success', 'Questions updated successfully!');
    }
    


}

