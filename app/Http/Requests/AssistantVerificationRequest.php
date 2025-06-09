<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssistantVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        $dayRule = ['nullable', 'string', 'regex:/^([0-9]{1,2}:[0-9]{2}-[0-9]{1,2}:[0-9]{2}(,\s*[0-9]{1,2}:[0-9]{2}-[0-9]{1,2}:[0-9]{2})*)?$|^([0-9]{1,2}-[0-9]{1,2}(,\s*[0-9]{1,2}-[0-9]{1,2})*)?$/'];

        return [
            'bio' => ['nullable', 'string', 'max:1000'],
            'availability' => ['required', 'array'],
            'availability.lunes' => $dayRule,
            'availability.martes' => $dayRule,
            'availability.miercoles' => $dayRule,
            'availability.jueves' => $dayRule,
            'availability.viernes' => $dayRule,
            'skills' => ['required', 'string', 'min:3'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'dni_front' => ['required', 'image', 'max:5120'],
            'dni_back' => ['required', 'image', 'max:5120'],
            'selfie' => ['required', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'availability.required' => 'Debes especificar al menos un horario de disponibilidad',
            'availability.*.regex' => 'El formato del horario debe ser como: 9-13 o 09:00-13:00. Puedes agregar varios horarios separados por coma, por ejemplo: 9-13, 14-18',
            'skills.required' => 'Debes especificar al menos una habilidad',
            'skills.min' => 'Debes especificar al menos una habilidad',
            'experience_years.required' => 'Debes especificar tus años de experiencia',
            'experience_years.min' => 'Los años de experiencia no pueden ser negativos',
            'dni_front.required' => 'Debes subir una foto del frente de tu DNI',
            'dni_back.required' => 'Debes subir una foto del reverso de tu DNI',
            'selfie.required' => 'Debes subir una selfie con tu DNI',
            '*.max' => 'El archivo no puede pesar más de 5MB',
            '*.image' => 'El archivo debe ser una imagen (JPG o PNG)',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
