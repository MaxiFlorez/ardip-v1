<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    /**
     * Reglas flexibles para parches: 'sometimes' + 'nullable'.
     */
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
}
