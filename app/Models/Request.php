<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assistant_id',
        'category_id',
        'location',
        'deadline',
        'is_urgent',
        'is_verified',
        'max_applications',
        'help_notes',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_urgent' => 'boolean',
        'is_verified' => 'boolean',
        'max_applications' => 'integer',
    ];
   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'request_application')
                    ->withPivot('status', 'message', 'proposed_price', 'estimated_duration', 'availability')
                    ->withTimestamps();
    }
} 