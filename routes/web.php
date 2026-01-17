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

// Redirección inicial centralizada según el rol del usuario
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Usar la nueva lógica centralizada
    return redirect()->route($user->getHomeRoute());
})->name('home');


// Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('can:admin')->name('dashboard');
    Route::get('/dashboard-consultor', fn() => view('dashboard-consultor'))->middleware('can:panel-consulta')->name('dashboard.consultor');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulos Operativos (excluyen super_admin puro)
    Route::middleware('can:acceso-operativo')->group(function () {
        Route::resource('procedimientos', ProcedimientoController::class);
        Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])->name('procedimientos.vincularPersona');
        Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])->name('procedimientos.vincularDomicilio');
        Route::get('/procedimientos/{procedimiento}/pdf', [ProcedimientoController::class, 'generarPdf'])->name('procedimientos.pdf');

        Route::resource('personas', PersonaController::class);
        Route::resource('domicilios', DomicilioController::class);
        Route::resource('documentos', DocumentoController::class);
        Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');
    });

    // Panel Administrativo (super_admin)
    Route::prefix('admin')->name('admin.')->middleware('can:super-admin')->group(function () {
        Route::middleware('super.admin.activity')->group(function () {
            Route::resource('users', UserController::class);
            Route::get('/users/{user}/history', [UserController::class, 'history'])->name('users.history');
        });
        Route::resource('brigadas', BrigadaController::class)->except(['show']);
        Route::resource('ufis', UfiController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';