<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrigadaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Gestión de brigadas accesible para admin y super_admin (defensa en profundidad)
        $user = $this->user();

        return $user?->can('admin') || $user?->can('super-admin');
    }

    public function rules(): array
    {
        $brigada = $this->route('brigada');
        $brigadaId = is_object($brigada) ? $brigada->id : $brigada;

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brigadas', 'nombre')->ignore($brigadaId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la brigada es obligatorio.',
            'nombre.unique' => 'Ya existe una brigada con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre de la brigada',
        ];
    }
}
