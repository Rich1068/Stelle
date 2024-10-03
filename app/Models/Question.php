<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id',
        'question',
        'type_id'
    ];
    public function evaluationForm()
    {
        return $this->hasOne(EvaluationForm::class);
    }

    public function eval_form()
    {
        return $this->belongsTo(EvaluationForm::class, 'form_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }


    public function isComment()
    {
        return $this->type_id === 1;
    }

    public function isRadio()
    {
        return $this->type_id === 2;
    }
}
