<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomicilioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Crear domicilios requiere permiso de escritura operativa
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    public function rules(): array
    {
        return [
            // Campos de dirección (todos opcionales para máxima flexibilidad)
            'calle' => 'nullable|string|max:255',
            'altura' => 'nullable|string|max:20',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'barrio' => 'nullable|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            
            // Coordenadas geográficas (mapa)
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            
            // Campos adicionales opcionales
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|string|max:100',
            'manzana' => 'nullable|string|max:20',
            'lote' => 'nullable|string|max:20',
            'casa' => 'nullable|string|max:20',
            'sector' => 'nullable|string|max:100',
            
            // Vinculación a persona
            'persona_id' => 'nullable|exists:personas,id',
            'observacion' => 'nullable|string|max:1000',
            'es_habitual' => 'nullable|boolean',
            
            // Vinculación a procedimiento
            'procedimiento_id' => 'nullable|exists:procedimientos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.exists' => 'La persona no existe.',
            'procedimiento_id.exists' => 'El procedimiento no existe.',
            'latitud.numeric' => 'La latitud debe ser un número válido.',
            'longitud.numeric' => 'La longitud debe ser un número válido.',
        ];
    }
}
