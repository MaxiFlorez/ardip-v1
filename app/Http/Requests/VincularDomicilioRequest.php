<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VincularDomicilioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('panel-carga') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'domicilio_id' => 'required|exists:domicilios,id',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'domicilio_id.required' => 'Debe seleccionar un domicilio.',
            'domicilio_id.exists' => 'El domicilio seleccionado no existe.',
        ];
    }
}
