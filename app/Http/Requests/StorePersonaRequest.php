<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'dni' => ['nullable', 'string', 'max:8', 'unique:personas,dni'],
            'nombres' => ['nullable', 'string', 'max:100'],
            'apellidos' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'genero' => ['nullable', 'in:masculino,femenino,otro'],
            'alias' => ['nullable', 'array'],
            'alias.*' => ['nullable', 'string', 'max:100'],
            'nacionalidad' => ['nullable', 'string', 'max:50'],
            'estado_civil' => ['nullable', 'in:soltero,casado,divorciado,viudo,concubinato'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'observaciones' => ['nullable', 'string'],
            'procedimiento_id' => ['nullable', 'integer', 'exists:procedimientos,id'],
            'situacion_procesal' => ['nullable', 'string'],
            'observaciones_vinculo' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
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
