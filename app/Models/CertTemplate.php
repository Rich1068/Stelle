<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'path'
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

}
