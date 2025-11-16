import './bootstrap';

import Alpine from 'alpinejs';

// Exponer Alpine globalmente (una vez)
window.Alpine = window.Alpine || Alpine;

// Patrón recomendado por Livewire v3: diferir el arranque de Alpine
// para que Livewire coordine una única inicialización.
window.deferLoadingAlpine = (alpineInitCallback) => {
	window.addEventListener('livewire:init', () => {
		alpineInitCallback(); // Livewire pasará Alpine.start() aquí
	});
};

// Si Livewire no está presente (proyecto sin Livewire), iniciar Alpine inmediatamente
if (!window.Livewire) {
    Alpine.start();
}

// Limpiar y reinicializar Alpine cuando la página cambia (SPA-like behavior)
document.addEventListener('DOMContentLoaded', () => {
	if (window.Alpine) {
		// Asegurar que Alpine se reinicializa después de cambios de contenido
		Alpine.nextTick(() => {
			// Forzar reevaluación de directivas Alpine
			document.querySelectorAll('[x-data]').forEach(el => {
				Alpine.clone(el);
			});
		});
	}
});

