<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController; // <-- Importación ya presente
// use App\Http\Controllers\CargaCompletaController; // Asistente desmantelado
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // CRUD de Personas (sin create/store - usar Asistente de Carga)
    Route::resource('personas', PersonaController::class)->except(['create','store']);

    // Rutas de domicilios conocidos eliminadas en V1 Híbrida
    
    // CRUD de Procedimientos (Flujo 3 activo: habilitar create/store)
    Route::resource('procedimientos', ProcedimientoController::class);
    // Vinculación de Persona al Procedimiento (Flujo 3 - Paso 3)
    Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
        ->name('procedimientos.vincularPersona');
    // Vinculación de Domicilio del Hecho (Flujo 3 - Paso 4)
    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])
        ->name('procedimientos.vincularDomicilio');
    
        // Rutas de vinculación eliminadas en V1 Híbrida (se usa Asistente de Carga)
            
    // CRUD de Domicilios (sin create/store - usar Asistente de Carga)
    Route::resource('domicilios', DomicilioController::class)->except(['create','store']);

    // Carga unificada desmantelada: se elimina el Asistente de Carga
});

require __DIR__.'/auth.php';