<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController; // <-- Importación ya presente
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // CRUD de Personas
    Route::resource('personas', PersonaController::class);
    
    // CRUD de Procedimientos
    Route::resource('procedimientos', ProcedimientoController::class);
    
    // Ruta personalizada para vincular personas
    Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
            ->name('procedimientos.vincularPersona');

    // Ruta personalizada para vincular domicilios
    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])
            ->name('procedimientos.vincularDomicilio');
            
    // --- RUTA AÑADIDA ---
    // CRUD de Domicilios
    Route::resource('domicilios', DomicilioController::class);
});

require __DIR__.'/auth.php';