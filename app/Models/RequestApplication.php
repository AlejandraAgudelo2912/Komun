<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'user_id',
        'status',
        'message',
        'proposed_price',
        'estimated_duration',
        'availability',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'estimated_duration' => 'integer',
        'availability' => 'array',
    ];

} 