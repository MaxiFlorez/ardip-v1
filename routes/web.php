<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\DocumentoController;
use Illuminate\Support\Facades\Route;

// Home -> redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de rutas protegidas (requieren login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MÓDULO DE PROCEDIMIENTOS ---
    Route::resource('procedimientos', ProcedimientoController::class);
    
    // Vinculaciones (Personas y Domicilios a un Procedimiento)
    Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
        ->name('procedimientos.vincularPersona');

    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])
        ->name('procedimientos.vincularDomicilio');

    // --- MÓDULOS BASE ---
    Route::resource('personas', PersonaController::class);
    Route::resource('domicilios', DomicilioController::class);

    // --- BIBLIOTECA DIGITAL ---
    Route::resource('documentos', DocumentoController::class);
    Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])
        ->name('documentos.download');
});

require __DIR__.'/auth.php';