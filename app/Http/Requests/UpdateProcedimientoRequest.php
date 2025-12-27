<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProcedimientoRequest extends FormRequest
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
        $procedimientoId = $this->route('procedimiento')?->id ?? null;

        return [
            'legajo_fiscal' => [
                'required', 'string', 'max:255',
                Rule::unique('procedimientos', 'legajo_fiscal')->ignore($procedimientoId),
            ],
            'caratula' => 'required|string|max:255',
            'fecha_hecho' => 'required|date',
            'ufi_interviniente' => 'nullable|string|max:255',
        ];
    }
}
