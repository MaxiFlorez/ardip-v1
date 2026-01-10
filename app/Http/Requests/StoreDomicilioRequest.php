<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomicilioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Crear/editar domicilios requiere permiso de carga
        return $this->user()?->can('operativo-escritura') ?? false;
    }

    public function rules(): array
    {
        return [
            'departamento' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|string|max:100',
            'manzana' => 'nullable|string|max:20',
            'lote' => 'nullable|string|max:20',
            'casa' => 'nullable|string|max:20',
            'barrio' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
            'coordenadas_gps' => 'nullable|string|max:100',
        ];
    }
}
