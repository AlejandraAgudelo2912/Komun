<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'request_model_id',
        'message',
        'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    public function scopeRecent(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function requestModel()
    {
        return $this->belongsTo(RequestModel::class);
    }

    public function scopeBetweenUsersAndRequest($query, $userId, $receiverId, $requestModelId = null)
    {
        return $query->where(function ($q) use ($userId, $receiverId) {
            $q->where('user_id', $userId)
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($userId, $receiverId) {
                $q->where('user_id', $receiverId)
                    ->where('receiver_id', $userId);
            })
            ->when($requestModelId, function ($q) use ($requestModelId) {
                $q->where('request_model_id', $requestModelId);
            })
            ->orderBy('created_at');
    }
}
