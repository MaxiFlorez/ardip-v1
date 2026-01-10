@props(['title'])
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    {{-- Título: Gris oscuro forzado para evitar problemas de contraste --}}
    <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
        {{ $title }}
    </h2>
    {{-- Slot de Acciones (Botones) --}}
    @if (isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
