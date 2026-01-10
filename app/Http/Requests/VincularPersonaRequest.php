<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VincularPersonaRequest extends FormRequest
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
            'persona_id' => 'required|exists:personas,id',
            'situacion_procesal' => 'required|in:detenido,notificado,no_hallado,contravencion',
            'pedido_captura' => 'sometimes|boolean',
            'observaciones' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'persona_id.required' => 'Debe seleccionar una persona.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'situacion_procesal.required' => 'Debe seleccionar una situación procesal.',
            'situacion_procesal.in' => 'La situación procesal seleccionada no es válida.',
            'pedido_captura.boolean' => 'El campo pedido de captura debe ser un valor booleano.',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
        ];
    }
}
