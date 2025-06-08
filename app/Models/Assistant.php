<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class Assistant extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'user_id',
        'bio',
        'availability',
        'skills',
        'experience_years',
        'is_verified',
        'rating',
        'total_reviews',
        'status',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'availability' => 'array',
        'skills' => 'array',
        'experience_years' => 'integer',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot([
                'experience_level',
                'years_of_experience',
                'notes',
            ])
            ->withTimestamps();
    }

    public function verification()
    {
        return $this->hasOne(AssistantVerificationDocument::class);
    }
}
