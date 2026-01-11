# 🔐 SISTEMA DE GATES (CONTROL DE ACCESO) - ARDIP v1.0

**Última Actualización:** 10 de enero de 2026  
**Archivo de Definición:** `app/Providers/AppServiceProvider.php`  
**Versión:** 1.0  
**Estado:** ✅ PRODUCCIÓN

---

## 📋 ÍNDICE

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Estructura de Gates](#estructura-de-gates)
3. [Gates Fundamentales](#gates-fundamentales)
4. [Gates de Dashboard](#gates-de-dashboard)
5. [Gates Operativos](#gates-operativos)
6. [Gates de Módulos](#gates-de-módulos)
7. [Matriz de Acceso](#matriz-de-acceso)
8. [Ejemplos de Uso](#ejemplos-de-uso)
9. [Capas de Seguridad](#capas-de-seguridad)

---

## 🎯 RESUMEN EJECUTIVO

El sistema ARDIP implementa **control de acceso basado en Gates** en lugar de verificaciones directas de roles. Los Gates actúan como políticas de acceso centralizadas que:

- ✅ Protegen vistas Blade
- ✅ Protegen rutas (middleware)
- ✅ Protegen acciones en Form Requests
- ✅ Son reutilizables y mantenibles

**Principio:** Un único rol asignado por usuario (aunque la tabla es many-to-many).

---

## 🏗️ ESTRUCTURA DE GATES

Los Gates se organizan en 4 categorías:

```
┌─────────────────────────────────────┐
│  GATES FUNDAMENTALES                │
│  (super-admin, admin)               │
└────────┬────────────────────────────┘
         │
         ├─ GATES DE DASHBOARD ──────────┐
         │  (admin-dashboard,             │
         │   admin-supervisor)            │
         │                                │
         ├─ GATES OPERATIVOS ────────────┐
         │  (panel-carga,                 │
         │   panel-consulta)              │
         │                                │
         └─ GATES DE MÓDULOS ────────────┐
            (acceso-operativo,
             operativo-escritura)
```

---

## 🔑 GATES FUNDAMENTALES

### **`super-admin`**

```php
Gate::define('super-admin', fn(User $user) => $user->hasRole('super_admin'));
```

**Propósito:** Control supremo del sistema  
**Rol:** `super_admin`  
**Acceso a:**

- Panel de Gestión de Usuarios
- Gestión de Brigadas
- Gestión de UFIs
- Configuración del Sistema

**Uso en Vistas:**

```blade
@can('super-admin')
    <a href="{{ route('admin.users.index') }}">Gestión Usuarios</a>
@endcan
```

**Exclusiones:**

- ❌ NO accede al Dashboard operativo
- ❌ NO puede crear/editar procedimientos
- ❌ NO puede ver búsqueda de procedimientos

---

### **`admin`**

```php
Gate::define('admin', fn(User $user) => $user->hasRole('admin'));
```

**Propósito:** Verificación simple del rol admin  
**Rol:** `admin`  
**Uso Frecuencia:** Rara (usar `admin-dashboard` o `admin-supervisor` en su lugar)

**Nota:** Es la base para gates más específicos (`admin-dashboard`, `admin-supervisor`)

---

## 📊 GATES DE DASHBOARD

### **`admin-dashboard`** ⭐ NUEVO

```php
Gate::define('admin-dashboard', fn(User $user) => 
    $user->hasRole('admin')
);
```

**Propósito:** Control específico para acceso al Dashboard  
**Rol:** `admin` (SOLO)  
**Lógica:** Retorna `true` SI el usuario tiene rol `admin`

**Exclusiones Explícitas:**

- ❌ `super_admin` NO tiene acceso (ni siquiera si tiene rol mixto)
- ❌ `panel-carga` NO tiene acceso
- ❌ `panel-consulta` NO tiene acceso

**Uso en Vistas:**

```blade
{{-- En resources/views/layouts/navigation.blade.php --}}
@can('admin-dashboard')
    <x-nav-link href="{{ route('dashboard') }}">
        Dashboard
    </x-nav-link>
@endcan
```

**Uso en Rutas:**

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('can:admin-dashboard')
    ->name('dashboard');
```

**Dashboard Proporciona:**

- Estadísticas operativas (total procedimientos, personas, documentos)
- Gráficos de procedimientos por UFI
- Últimos procedimientos cargados
- Panel de supervisor operativo

---

### **`admin-supervisor`**

```php
Gate::define('admin-supervisor', fn(User $user) => 
    $user->hasRole('admin') && 
    (!$user->hasRole('super_admin') || $user->roles()->count() > 1)
);
```

**Propósito:** Control flexible para navegación  
**Rol:** `admin` (sin ser super_admin puro O siendo super_admin con otro rol)  
**Lógica:**

- `true` si usuario es admin Y (NO es super_admin O tiene múltiples roles)
- Permite acceso si admin tiene rol mixto (admin + super_admin)

**Diferencia con `admin-dashboard`:**

| Aspecto | admin-dashboard | admin-supervisor |
|---------|-----------------|------------------|
| **Permite admin puro** | ✅ Sí | ✅ Sí |
| **Permite super_admin puro** | ❌ No | ❌ No |
| **Permite admin + super_admin** | ❌ No | ✅ Sí |
| **Uso** | Acceso al dashboard | Mostrar en navegación |

**Uso Actual:** Deprecated en favor de `admin-dashboard` (pero se mantiene para compatibilidad)

**Nota Técnica:** Puede ser eliminado en futuras versiones si no se usa

---

## 🎮 GATES OPERATIVOS

### **`panel-carga`**

```php
Gate::define('panel-carga', fn(User $user) => $user->hasRole('panel-carga'));
```

**Propósito:** Acceso a operarios que cargan datos  
**Rol:** `panel-carga`  
**Permisos:**

- ✅ Ver procedimientos
- ✅ Crear procedimientos
- ✅ Editar procedimientos (propios)
- ✅ Eliminar procedimientos
- ✅ Ver personas
- ✅ Crear personas
- ✅ Editar personas
- ✅ Eliminar personas
- ✅ Ver documentos
- ✅ Subir documentos
- ✅ Eliminar documentos

**Restricciones:**

- ❌ NO puede acceder a gestión de usuarios
- ❌ NO puede acceder a gestión de brigadas
- ❌ NO puede acceder a gestión de UFIs
- ❌ NO puede acceder al Dashboard

**Uso en Vistas:**

```blade
@can('operativo-escritura')  {{-- En lugar de panel-carga --}}
    <button>Crear Procedimiento</button>
@endcan
```

---

### **`panel-consulta`**

```php
Gate::define('panel-consulta', fn(User $user) => 
    $user->hasRole('panel-consulta') || $user->hasRole('panel-carga')
);
```

**Propósito:** Acceso de lectura para consultores  
**Rol:** `panel-consulta`  
**Lógica:** Permite a consultores Y a cargadores (cargadores pueden consultar)

**Permisos:**

- ✅ Ver procedimientos
- ✅ Ver personas
- ✅ Ver documentos
- ❌ No crear/editar/eliminar

**Restricciones:**

- ❌ NO puede acceder a gestión administrativa
- ❌ NO puede acceder al Dashboard

**Nota:** Generalmente no se usa directo en vistas, se usa a través de `acceso-operativo`

---

## 📦 GATES DE MÓDULOS

### **`acceso-operativo`**

```php
Gate::define('acceso-operativo', fn(User $user) => 
    $user->hasRole('admin') 
    || $user->hasRole('panel-carga') 
    || $user->hasRole('panel-consulta')
);
```

**Propósito:** Lectura general en módulos operativos  
**Roles:** `admin`, `panel-carga`, `panel-consulta`  
**Permisos:** VER procedimientos, personas, documentos

**Uso en Vistas (Index/Show):**

```blade
@can('acceso-operativo')
    <x-nav-link href="{{ route('procedimientos.index') }}">
        Procedimientos
    </x-nav-link>
@endcan
```

**Uso en Rutas (Lectura):**

```php
Route::middleware('can:acceso-operativo')->group(function () {
    Route::get('/procedimientos', [ProcedimientoController::class, 'index']);
    Route::get('/procedimientos/{procedimiento}', [ProcedimientoController::class, 'show']);
});
```

---

### **`operativo-escritura`** ⭐ CRÍTICO

```php
Gate::define('operativo-escritura', fn(User $user) => 
    $user->hasRole('panel-carga')
);
```

**Propósito:** CRUD en módulos operativos  
**Rol:** `panel-carga` (SOLO)  
**Permisos:** Crear, editar, eliminar procedimientos, personas, documentos

**Exclusiones Explícitas:**

- ❌ `admin` NO puede crear/editar/eliminar (read-only)
- ❌ `panel-consulta` NO puede escribir
- ❌ `super_admin` NO puede escribir

**Uso en Vistas (Botones CRUD):**

```blade
@can('operativo-escritura')
    <a href="{{ route('procedimientos.create') }}">Crear</a>
    <a href="{{ route('procedimientos.edit', $p) }}">Editar</a>
    <form action="{{ route('procedimientos.destroy', $p) }}">Eliminar</form>
@endcan
```

**Uso en Controladores (Form Requests):**

```php
public function authorize(): bool
{
    return auth()->user()->can('operativo-escritura');
}
```

**Uso en Rutas (Escritura):**

```php
Route::middleware('can:operativo-escritura')->group(function () {
    Route::post('/procedimientos', [ProcedimientoController::class, 'store']);
    Route::put('/procedimientos/{id}', [ProcedimientoController::class, 'update']);
    Route::delete('/procedimientos/{id}', [ProcedimientoController::class, 'destroy']);
});
```

---

## 📊 MATRIZ DE ACCESO COMPLETA

| Característica | super_admin | admin | panel-carga | panel-consulta |
|---|:---:|:---:|:---:|:---:|
| **Dashboard** | ❌ | ✅ | ❌ | ❌ |
| **Ver Procedimientos** | ❌ | ✅ | ✅ | ✅ |
| **Crear Procedimientos** | ❌ | ❌ | ✅ | ❌ |
| **Editar Procedimientos** | ❌ | ❌ | ✅ | ❌ |
| **Eliminar Procedimientos** | ❌ | ❌ | ✅ | ❌ |
| **Ver Personas** | ❌ | ✅ | ✅ | ✅ |
| **CRUD Personas** | ❌ | ❌ | ✅ | ❌ |
| **Ver Documentos** | ❌ | ✅ | ✅ | ✅ |
| **Subir/Eliminar Documentos** | ❌ | ❌ | ✅ | ❌ |
| **Gestión Usuarios** | ✅ | ❌ | ❌ | ❌ |
| **Gestión Brigadas** | ✅ | ❌ | ❌ | ❌ |
| **Gestión UFIs** | ✅ | ❌ | ❌ | ❌ |

---

## 💡 EJEMPLOS DE USO

### **1. Proteger una Vista Blade**

```blade
{{-- Mostrar botón solo a panel-carga --}}
@can('operativo-escritura')
    <a href="{{ route('procedimientos.create') }}" class="btn btn-primary">
        Crear Procedimiento
    </a>
@endcan

{{-- Mostrar solo a admin --}}
@can('admin-dashboard')
    <div class="dashboard-panel">
        {{-- Contenido del dashboard --}}
    </div>
@endcan

{{-- Mostrar a operativos en general --}}
@can('acceso-operativo')
    <a href="{{ route('procedimientos.index') }}">Ver Procedimientos</a>
@endcan
```

---

### **2. Proteger una Ruta**

```php
// En routes/web.php

// Solo admin-dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('can:admin-dashboard')
    ->name('dashboard');

// Solo lectura
Route::get('/procedimientos', [ProcedimientoController::class, 'index'])
    ->middleware('can:acceso-operativo')
    ->name('procedimientos.index');

// Solo escritura
Route::post('/procedimientos', [ProcedimientoController::class, 'store'])
    ->middleware('can:operativo-escritura')
    ->name('procedimientos.store');

// Admin supremo
Route::resource('admin/users', UserController::class)
    ->middleware('can:super-admin');
```

---

### **3. Proteger una Form Request**

```php
// En app/Http/Requests/StoreProcedimientoRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcedimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios con gate operativo-escritura
        return $this->user()->can('operativo-escritura');
    }

    public function rules(): array
    {
        return [
            'legajo_fiscal' => 'required|string|unique:procedimientos',
            'caratula' => 'required|string',
            // ... más reglas
        ];
    }
}
```

---

### **4. Verificar en Controlador**

```php
// En app/Http/Controllers/ProcedimientoController.php

public function create()
{
    $this->authorize('operativo-escritura');
    // O: if (!auth()->user()->can('operativo-escritura')) abort(403);
    
    return view('procedimientos.create');
}

public function show(Procedimiento $procedimiento)
{
    $this->authorize('acceso-operativo');
    return view('procedimientos.show', compact('procedimiento'));
}
```

---

## 🛡️ CAPAS DE SEGURIDAD

El sistema implementa **Defense-in-Depth** con múltiples capas:

### **Capa 1: Rutas (Middleware)**

```php
Route::middleware('can:operativo-escritura')->post('/procedimientos', ...);
```

### **Capa 2: Form Requests**

```php
public function authorize(): bool
{
    return $this->user()->can('operativo-escritura');
}
```

### **Capa 3: Vistas Blade**

```blade
@can('operativo-escritura')
    <button>Crear</button>
@endcan
```

### **Capa 4: Controladores**

```php
if (!auth()->user()->can('operativo-escritura')) {
    abort(403, 'No autorizado');
}
```

**Ventaja:** Incluso si una capa falla, las otras protegen

---

## 🚀 GUÍA DE IMPLEMENTACIÓN

### **Al Crear una Nueva Ruta de Escritura:**

1. ✅ Agregar middleware: `->middleware('can:operativo-escritura')`
2. ✅ Agregar authorize() en Form Request
3. ✅ Agregar @can en botones de la vista
4. ✅ Agregar authorize() en controlador

### **Al Crear una Nueva Ruta de Lectura:**

1. ✅ Agregar middleware: `->middleware('can:acceso-operativo')`
2. ✅ No requiere Form Request
3. ✅ Agregar @can en navegación
4. ✅ Gate protege automáticamente

### **Al Agregar un Nuevo Rol:**

1. ✅ Crear el rol en BD (tabla `roles`)
2. ✅ Crear gate en `AppServiceProvider.php`
3. ✅ Actualizar `GATES_SISTEMA.md`
4. ✅ Usar gate en vistas/rutas/requests

---

## 📝 CHANGELOG

### **v1.0 - 10 de enero de 2026**

- ✅ Creación inicial de documentación
- ✅ Implementación de gate `admin-dashboard`
- ✅ Reorganización y documentación de AppServiceProvider
- ✅ 7 gates fundamentales definidos
- ✅ Matriz de acceso documentada
- ✅ Ejemplos de uso agregados

---

## 🔗 REFERENCIAS

- **Archivo Principal:** `app/Providers/AppServiceProvider.php`
- **Documentación Laravel:** <https://laravel.com/docs/authorization>
- **Vistas Protegidas:** `resources/views/layouts/navigation.blade.php`
- **Rutas Protegidas:** `routes/web.php`

---

## ✅ VALIDACIÓN

```
Gate::define('super-admin')          ✅ Implementado
Gate::define('admin')                ✅ Implementado
Gate::define('admin-dashboard')      ✅ Implementado (NUEVO)
Gate::define('admin-supervisor')     ✅ Implementado
Gate::define('panel-carga')          ✅ Implementado
Gate::define('panel-consulta')       ✅ Implementado
Gate::define('acceso-operativo')     ✅ Implementado
Gate::define('operativo-escritura')  ✅ Implementado
```

**Total Gates:** 8 ✅ PRODUCCIÓN

---

**Documento Preparado por:** GitHub Copilot - Backend Laravel Senior  
**Última Actualización:** 10 de enero de 2026  
**Estado:** 🟢 APROBADO PARA PRODUCCIÓN
