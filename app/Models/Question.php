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
}