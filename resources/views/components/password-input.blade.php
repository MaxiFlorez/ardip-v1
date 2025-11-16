<!--
    Componente: x-password-input
    Descripción: Campo de contraseña con botón para mostrar/ocultar el valor.
    Accesibilidad: Incluye etiqueta aria-label dinámica y mantiene el focus en el input.
    Uso:
        <x-password-input id="password" name="password" required autocomplete="new-password" />
    Props:
        - id: string | null
        - name: string (por defecto: "password")
        - autocomplete: string | null (p.ej. "current-password" | "new-password")
        - placeholder: string | null
        - required: bool
        - disabled: bool
        - class: string (clases Tailwind adicionales)
-->

@props([
    'id' => null,
    'name' => 'password',
    'autocomplete' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'class' => '',
])

<div class="relative" x-data="{ show: false }">
    <input
        {{ $disabled ? 'disabled' : '' }}
        id="{{ $id }}"
        name="{{ $name }}"
        :type="show ? 'text' : 'password'"
        @class([
            'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10',
            $class,
        ])
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
    />
    <button
        type="button"
        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
        :aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'"
        @click="show = !show"
    >
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.26-3.955M6.223 6.223A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.28 5.223M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 21m9-12L21 3" />
        </svg>
    </button>
</div>
