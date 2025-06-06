<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'priority' => 'required|in:low,medium,high',
            'max_applications' => 'nullable|integer|min:1|max:10',
            'help_notes' => 'nullable|string',
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
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad debe ser baja, media o alta.',
            'max_applications.min' => 'El número mínimo de aplicaciones es 1.',
            'max_applications.max' => 'El número máximo de aplicaciones es 10.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }
} 