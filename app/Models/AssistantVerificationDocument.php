<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistantVerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_id',
        'dni_front_path',
        'dni_back_path',
        'selfie_path',
        'status',
        'rejection_reason',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class);
    }
}
