<?php
/**
 * Archivo de traducciones de validación (Español)
 *
 * Propósito:
 * - Proveer mensajes de error legibles para las reglas de validación de Laravel.
 * - Mapear nombres de atributos técnicos a etiquetas amigables para el usuario (sección 'attributes').
 *
 * Notas:
 * - Las claves siguen la convención de Laravel (p.ej., 'required', 'min.string', 'password.mixed').
 * - Personaliza/añade entradas según nuevas reglas o campos del proyecto.
 */

return [
    'accepted' => 'Debe aceptar :attribute.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña actual no es correcta.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'exists' => 'El :attribute seleccionado no es válido.',
    'max' => [
        'string' => ':attribute no debe superar :max caracteres.',
        'numeric' => ':attribute no debe ser mayor que :max.',
        'file' => ':attribute no debe ser mayor que :max kilobytes.',
        'array' => ':attribute no debe tener más de :max elementos.',
    ],
    'min' => [
        'string' => ':attribute debe tener al menos :min caracteres.',
        'numeric' => ':attribute debe ser al menos :min.',
        'file' => ':attribute debe tener al menos :min kilobytes.',
        'array' => ':attribute debe tener al menos :min elementos.',
    ],
    'required' => 'El campo :attribute es obligatorio.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'string' => ':attribute debe tener :size caracteres.',
        'numeric' => ':attribute debe ser :size.',
        'file' => ':attribute debe tener :size kilobytes.',
        'array' => ':attribute debe contener :size elementos.',
    ],
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'El :attribute ya está en uso.',
    'url' => 'El formato de :attribute no es válido.',
    'lowercase' => ':attribute debe estar en minúsculas.',

    // Reglas específicas de contraseñas
    'password' => [
        'letters' => 'La contraseña debe contener al menos una letra.',
        'mixed' => 'La contraseña debe contener al menos una mayúscula y una minúscula.',
        'numbers' => 'La contraseña debe contener al menos un número.',
        'symbols' => 'La contraseña debe contener al menos un símbolo.',
        'uncompromised' => 'La contraseña dada aparece en una filtración de datos. Por favor elige otra diferente.',
    ],

    // Mapeo de atributos: nombre_campo => etiqueta amigable
    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        'password_confirmation' => 'confirmación de contraseña',
        'name' => 'nombre',
    ],
];
