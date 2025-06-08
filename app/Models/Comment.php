<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_model_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestModel()
    {
        return $this->belongsTo(RequestModel::class);
    }
}
