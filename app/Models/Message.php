<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'receiver_id',
        'request_model_id',
        'message',
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
}
