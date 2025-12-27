<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\DocumentoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

// Home -> redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de rutas protegidas (requieren login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (solo admin-general)
    // Nota: La verificación del gate se hace en el controller después de cargar relaciones
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Panel de Carga (para cargadores)
    Route::get('/panel-carga', function () {
        // Verificar que tenga permiso
        if (! Gate::allows('panel-carga')) {
            abort(403);
        }
        
        // Redirigir al listado de procedimientos
        return redirect()->route('procedimientos.index');
    })->name('panel.carga');

    // Panel de Consulta (para operarios)
    Route::get('/panel-consulta', function () {
        // Verificar que tenga permiso
        if (! Gate::allows('panel-consulta')) {
            abort(403);
        }
        
        // Redirigir al buscador de personas
        return redirect()->route('personas.index');
    })->name('panel.consulta');

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

    // PDF del procedimiento
    Route::get('/procedimientos/{procedimiento}/pdf', [ProcedimientoController::class, 'generarPdf'])
        ->name('procedimientos.pdf');

    // --- MÓDULOS BASE ---
    Route::resource('personas', PersonaController::class);
    Route::resource('domicilios', DomicilioController::class);

    // --- BIBLIOTECA DIGITAL ---
    Route::resource('documentos', DocumentoController::class);
    Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])
        ->name('documentos.download');
});

require __DIR__.'/auth.php';