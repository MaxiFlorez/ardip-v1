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
        // Puedes habilitar gate: return $this->user()?->can('panel-carga') ?? false;
        return true;
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
            'fecha_hecho' => 'required|date',
            'ufi_id' => ['required', 'exists:ufis,id'],
        ];
    }
}
