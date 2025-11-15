<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController; // <-- Importación ya presente
use App\Http\Controllers\CargaCompletaController;
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
    
    // CRUD de Procedimientos (sin create/store - usar Asistente de Carga)
    Route::resource('procedimientos', ProcedimientoController::class)->except(['create','store']);
    
        // Rutas de vinculación eliminadas en V1 Híbrida (se usa Asistente de Carga)
            
    // CRUD de Domicilios (sin create/store - usar Asistente de Carga)
    Route::resource('domicilios', DomicilioController::class)->except(['create','store']);

    // Carga unificada (Personas + Domicilios + Procedimiento)
    Route::get('/carga/nueva', [CargaCompletaController::class, 'create'])->name('carga.create');
    Route::post('/carga', [CargaCompletaController::class, 'store'])->name('carga.store');
});

require __DIR__.'/auth.php';