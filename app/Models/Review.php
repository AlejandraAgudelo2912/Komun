<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_models_id',
        'user_id',
        'assistant_id',
        'rating',
        'comment',
    ];

    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'request_models_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    // Añadir índices únicos para evitar reseñas duplicadas
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            // Verificar si ya existe una reseña para este usuario y asistente en esta solicitud
            $exists = static::where('request_models_id', $review->request_models_id)
                          ->where('user_id', $review->user_id)
                          ->where('assistant_id', $review->assistant_id)
                          ->exists();
            
            if ($exists) {
                throw new \Exception('Ya has calificado a este asistente para esta solicitud.');
            }
        });
    }
}
