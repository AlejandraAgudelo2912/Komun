<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class RequestModel extends Model
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('deadline')
                  ->orWhere('deadline', '>', now());
        })->where('status', 'pending');
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeNoApplicants(Builder $query): Builder
    {
        return $query->whereDoesntHave('appliedRequests');
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('deadline', '<=', now()->addDay())
                    ->where('deadline', '>', now())
                    ->where('status', 'pending');
    }

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
        return $this->belongsToMany(User::class, 'request_model_application')
                    ->withPivot('message', 'status')
                    ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'request_models_id');
    }
}
