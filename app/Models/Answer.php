<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'form_id',
        'user_id',
        'answer',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
