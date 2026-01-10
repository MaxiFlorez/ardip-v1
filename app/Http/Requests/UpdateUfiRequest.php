<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUfiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Gestión de UFIs accesible para 'admin'
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        $ufi = $this->route('ufi');

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ufis', 'nombre')->ignore($ufi?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la UFI es obligatorio.',
            'nombre.unique' => 'Ya existe una UFI con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre de la UFI',
        ];
    }
}
