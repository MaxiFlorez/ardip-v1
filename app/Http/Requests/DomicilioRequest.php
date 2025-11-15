<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Provincia;

class DomicilioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Nota: si todas las rutas requieren auth, podemos usar auth()->check().
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sanJuanId = Provincia::where('nombre', 'San Juan')->value('id');

        return [
            'provincia_id' => 'required|exists:provincias,id',
            'departamento_id' => [
                'nullable',
                'exists:departamentos,id',
                Rule::requiredIf(fn () => (int) $this->input('provincia_id') === (int) $sanJuanId),
            ],
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|numeric',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|alpha_num|max:100',
            'manzana' => 'nullable|alpha_num|max:20',
            'lote' => 'nullable|alpha_num|max:20',
            'casa' => 'nullable|alpha_num|max:20',
            'barrio' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
            'coordenadas_gps' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'numero.numeric' => 'El número debe contener solo dígitos.',
            'monoblock.alpha_num' => 'El monoblock debe contener solo letras y números.',
            'manzana.alpha_num' => 'La manzana debe contener solo letras y números.',
            'lote.alpha_num' => 'El lote debe contener solo letras y números.',
            'casa.alpha_num' => 'La casa debe contener solo letras y números.',
        ];
    }
}
