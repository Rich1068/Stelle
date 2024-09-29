<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'status_id',
        'form_name'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'form_id');
    }

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

    public function status()
    {
        return $this->belongsTo(FormStatus::class, 'status_id');
    }

}
