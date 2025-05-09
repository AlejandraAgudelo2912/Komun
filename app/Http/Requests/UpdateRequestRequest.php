<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (in_array($this->user()->role, ['admin', 'god'])) {
            return true;
        }

        return $this->user()->id === $this->route('request')->user_id;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
        ];

        if (in_array($this->user()->role, ['admin', 'god'])) {
            $rules['status'] = 'required|in:pending,approved,rejected,completed';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'location.required' => 'La ubicación es obligatoria.',
            'location.max' => 'La ubicación no puede tener más de 255 caracteres.',
            'deadline.required' => 'La fecha límite es obligatoria.',
            'deadline.date' => 'La fecha límite debe ser una fecha válida.',
            'deadline.after' => 'La fecha límite debe ser posterior a hoy.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }
} 