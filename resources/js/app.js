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
