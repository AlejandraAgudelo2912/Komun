<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high'],
            'deadline' => ['required', 'date', 'after:today'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'location' => ['nullable', 'string', 'max:255'],
            'max_applications' => ['nullable', 'integer', 'min:1', 'max:10'],
            'help_notes' => ['nullable', 'string'],
            'is_urgent' => ['sometimes', 'boolean'],
            'is_verified' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede tener más de 255 caracteres',
            'description.required' => 'La descripción es obligatoria',
            'category_id.required' => 'Debe seleccionar una categoría',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'priority.required' => 'Debe seleccionar una prioridad',
            'priority.in' => 'La prioridad seleccionada no es válida',
            'deadline.required' => 'La fecha límite es obligatoria',
            'deadline.date' => 'La fecha límite debe ser una fecha válida',
            'deadline.after' => 'La fecha límite debe ser posterior a hoy',
            'status.required' => 'Debe seleccionar un estado',
            'status.in' => 'El estado seleccionado no es válido',
            'location.max' => 'La ubicación no puede tener más de 255 caracteres',
            'max_applications.integer' => 'El número máximo de aplicaciones debe ser un número entero',
            'max_applications.min' => 'El número máximo de aplicaciones debe ser al menos 1',
            'max_applications.max' => 'El número máximo de aplicaciones no puede ser mayor a 10',
            'is_urgent.boolean' => 'El campo urgente debe ser verdadero o falso',
            'is_verified.boolean' => 'El campo verificado debe ser verdadero o falso',
        ];
    }
}
