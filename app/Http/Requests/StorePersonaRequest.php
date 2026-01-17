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
     * RESTRICCIÓN CRÍTICA: reglas flexibles sin 'required'.
     * NOTA: Los campos críticos (dni, nombres, apellidos) son nullable para permitir
     * flexibilidad en el flujo del Hub de Procedimientos. La validación de completitud
     * se debe manejar a nivel de aplicación según el contexto de uso.
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
            // Contexto Hub
            'procedimiento_id' => ['nullable'],
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
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe ser JPEG, PNG o JPG.',
            'foto.max' => 'La foto no puede exceder 2048 KB.',
        ];
    }
}
