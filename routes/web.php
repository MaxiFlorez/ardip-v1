<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrigadaController;
use App\Http\Controllers\Admin\UfiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

// Redirección inicial según el rol del usuario
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->hasRole('super_admin')) {
        return redirect()->route('admin.users.index');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('dashboard');
    }

    if ($user->hasRole('panel-carga')) {
        return redirect()->route('procedimientos.index');
    }

    if ($user->hasRole('panel-consulta')) {
        return redirect()->route('personas.index');
    }

    return redirect()->route('login');
});


// Grupo de rutas protegidas (requieren login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (solo admin, EXCLUIDO super_admin puro)
    // Nota: La verificación del gate se hace en el controller después de cargar relaciones
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('can:admin')
        ->name('dashboard');

    // Dashboard para consultores
    Route::get('/dashboard-consultor', function () {
        return view('dashboard-consultor');
    })->middleware('can:panel-consulta')->name('dashboard.consultor');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MÓDULO DE PROCEDIMIENTOS (OPERATIVO: Cargador, Consultor, Admin) ---
    // EXCLUIDO: Super Admin puro
    Route::middleware('can:acceso-operativo')->group(function () {
        Route::resource('procedimientos', ProcedimientoController::class);
        
        // Vinculaciones (Personas y Domicilios a un Procedimiento)
        Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
            ->name('procedimientos.vincularPersona');

        Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])
            ->name('procedimientos.vincularDomicilio');

        // PDF del procedimiento
        Route::get('/procedimientos/{procedimiento}/pdf', [ProcedimientoController::class, 'generarPdf'])
            ->name('procedimientos.pdf');
    });
    
    // --- MÓDULOS BASE (OPERATIVO: Cargador, Consultor, Admin) ---
    // EXCLUIDO: Super Admin puro
    Route::middleware('can:acceso-operativo')->group(function () {
        Route::resource('personas', PersonaController::class);
        Route::resource('domicilios', DomicilioController::class);
    });

    // --- BIBLIOTECA DIGITAL (OPERATIVO: Cargador, Consultor, Admin) ---
    // EXCLUIDO: Super Admin puro
    Route::middleware('can:acceso-operativo')->group(function () {
        Route::resource('documentos', DocumentoController::class);
        Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])
            ->name('documentos.download');
    });

    // --- PANEL DE ADMINISTRACIÓN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        // Catálogos y Gestión de Usuarios (Solo Super Admin)
        Route::middleware('can:super-admin')->group(function () {
            // Gestión de Usuarios (Solo Super Admin con auditoría)
            Route::middleware('super.admin.activity')->group(function () {
                Route::resource('users', UserController::class);
                Route::get('/users/{user}/history', [UserController::class, 'history'])->name('users.history');
            });

            // Catálogos (Solo Super Admin)
            Route::resource('brigadas', BrigadaController::class)->except(['show']);
            Route::resource('ufis', UfiController::class)->except(['show']);
        });
    });
});

require __DIR__.'/auth.php';