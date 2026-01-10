<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcedimientoRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'legajo_fiscal' => 'required|string|max:255|unique:procedimientos,legajo_fiscal',
            'caratula' => 'required|string|max:255',
            'fecha_procedimiento' => 'required|date',
            'ufi_id' => ['required', 'exists:ufis,id'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'legajo_fiscal.required' => 'El legajo fiscal es obligatorio.',
            'legajo_fiscal.unique' => 'Este legajo fiscal ya existe en el sistema.',
            'caratula.required' => 'La carátula es obligatoria.',
            'fecha_procedimiento.required' => 'La fecha del procedimiento es obligatoria.',
            'fecha_procedimiento.date' => 'La fecha debe ser una fecha válida.',
            'ufi_id.required' => 'Debe seleccionar una UFI.',
            'ufi_id.exists' => 'La UFI seleccionada no existe.',
        ];
    }
}
