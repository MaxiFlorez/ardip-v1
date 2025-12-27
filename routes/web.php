<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController; // <-- Importación ya presente
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\DomicilioController;

// Home -> redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
<<<<<<< HEAD

    // Recursos principales
    Route::resource('procedimientos', ProcedimientoController::class);
    Route::resource('personas', PersonaController::class);
    Route::resource('domicilios', DomicilioController::class);

    // Rutas de vinculación para procedimientos
    Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
        ->name('procedimientos.vincularPersona');

    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class,'vincularDomicilio'])
        ->name('procedimientos.vincularDomicilio');

    // Biblioteca Digital
    Route::resource('documentos', \App\Http\Controllers\DocumentoController::class);
    Route::get('/documentos/{documento}/download', [\App\Http\Controllers\DocumentoController::class, 'download'])
        ->name('documentos.download');

    // Biblioteca Digital (placeholder para rutas futuras)
    // Route::get('/biblioteca', function () { /* TODO: Implementar Biblioteca Digital */ })->name('biblioteca.index');
=======
    
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
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1
});

require __DIR__.'/auth.php';