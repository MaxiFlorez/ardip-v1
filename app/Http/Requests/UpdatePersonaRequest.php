<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    public function rules(): array
    {
        $persona = $this->route('persona');

        return [
            'dni' => ['sometimes', 'nullable', 'string', 'max:8', 'unique:personas,dni,' . ($persona?->id ?? 'NULL')],
            'nombres' => ['sometimes', 'nullable', 'string', 'max:100'],
            'apellidos' => ['sometimes', 'nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'genero' => ['sometimes', 'nullable', 'string'],
            'alias' => ['sometimes', 'nullable', 'array'],
            'alias.*' => ['nullable', 'string', 'max:100'],
            'nacionalidad' => ['sometimes', 'nullable', 'string', 'max:50'],
            'estado_civil' => ['sometimes', 'nullable', 'string'],
            'foto' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'observaciones' => ['sometimes', 'nullable', 'string'],
            'procedimiento_id' => ['sometimes', 'nullable'],
            'situacion_procesal' => ['sometimes', 'nullable', 'string'],
            'observaciones_vinculo' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.max' => 'El DNI debe tener máximo 8 caracteres.',
            'dni.unique' => 'Este DNI ya está registrado en el sistema.',
            'nombres.required' => 'Los nombres son obligatorios.',
            'nombres.max' => 'Los nombres no pueden exceder 100 caracteres.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no pueden exceder 100 caracteres.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser masculino, femenino u otro.',
            'alias.array' => 'Los alias deben ser un array.',
            'alias.*.string' => 'Cada alias debe ser texto.',
            'alias.*.max' => 'Cada alias no puede exceder 100 caracteres.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe ser JPEG, PNG o JPG.',
            'foto.max' => 'La foto no puede exceder 2048 KB.',
        ];
    }
}
