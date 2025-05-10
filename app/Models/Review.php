<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'user_id',
        'assistant_id',
        'rating',
        'comment',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }
} 