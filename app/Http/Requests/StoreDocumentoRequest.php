<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            // Validación por MIME type real, no solo extensión (más seguro)
            'archivo' => 'required|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:20480',
            'descripcion' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título del documento es obligatorio.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'archivo.required' => 'Debe seleccionar un archivo para subir.',
            'archivo.file' => 'El archivo seleccionado no es válido.',
            'archivo.mimetypes' => 'El archivo debe ser PDF, Word (.doc) o Word (.docx).',
            'archivo.max' => 'El archivo no puede exceder 20 MB (20480 KB).',
            'descripcion.string' => 'La descripción debe ser texto válido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'archivo' => 'archivo',
            'descripcion' => 'descripción',
        ];
    }
}
