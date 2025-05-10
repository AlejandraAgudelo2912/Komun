<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'La puntuación es obligatoria.',
            'rating.integer' => 'La puntuación debe ser un número entero.',
            'rating.min' => 'La puntuación mínima es 1.',
            'rating.max' => 'La puntuación máxima es 5.',
            'comment.max' => 'El comentario no puede tener más de 1000 caracteres.',
        ];
    }
} 