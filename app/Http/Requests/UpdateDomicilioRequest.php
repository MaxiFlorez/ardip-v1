<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDomicilioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Actualizar domicilios requiere permiso de carga
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    public function rules(): array
    {
        return [
            // Campos de dirección (todos opcionales)
            'calle' => 'nullable|string|max:255',
            'altura' => 'nullable|string|max:20',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'barrio' => 'nullable|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            
            // Coordenadas geográficas
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            
            // Campos adicionales opcionales
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|string|max:100',
            'manzana' => 'nullable|string|max:20',
            'lote' => 'nullable|string|max:20',
            'casa' => 'nullable|string|max:20',
            'sector' => 'nullable|string|max:100',
        ];
    }
}
