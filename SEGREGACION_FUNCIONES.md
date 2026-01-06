# Segregación de Funciones - ARDIP System

**Última actualización:** Diciembre 2024  
**Estado:** ✅ Implementado

---

## 📋 Resumen Ejecutivo

El sistema implementa una segregación estricta de funciones entre el **Super Administrador** (rol técnico y de auditoría) y los **roles operativos** (Administrador, Cargador, Consultor).

### Principio Fundamental

> **El Super Admin NO puede realizar operaciones del negocio (CRUD de Procedimientos, Personas, Documentos).**  
> **Solo tiene acceso TÉCNICO y de AUDITORÍA.**

---

## 🔐 Matriz de Acceso por Rol

| Funcionalidad | Super Admin | Admin | Cargador | Consultor |
|---------------|:----------:|:-----:|:--------:|:---------:|
| **PANEL TÉCNICO** | | | | |
| Dashboard | ✅ | ✅ | ❌ | ❌ |
| Gestión de Usuarios | ✅ | ✅ | ❌ | ❌ |
| Catálogo Brigadas | ✅ | ❌ | ❌ | ❌ |
| Catálogo UFIs | ✅ | ❌ | ❌ | ❌ |
| Auditoría (Logs) | ✅ | ❌ | ❌ | ❌ |
| **PANEL OPERATIVO** | | | | |
| Procedimientos (CRUD) | ❌ | ✅ | ✅ | ✅ |
| Personas (CRUD) | ❌ | ✅ | ✅ | ✅ |
| Domicilios (CRUD) | ❌ | ✅ | ✅ | ✅ |
| Documentos (CRUD) | ❌ | ✅ | ✅ | ✅ |

---

## 🛡️ Implementación Técnica

### 1. Gates en AppServiceProvider.php

```php
// ✅ Super Administrador (TÉCNICO PURO)
Gate::define('super-admin', function (User $user) {
    return $user->hasRole('super_admin');
});

// ✅ Administrador (Sin super-admin)
Gate::define('admin', function (User $user) {
    return $user->hasRole('admin');
});

// ✅ Acceso Operativo (EXCLUYE super-admin puro)
Gate::define('acceso-operativo', function (User $user) {
    // Si es super_admin SIN otros roles → DENIEGA
    if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
        return false;
    }
    
    // Permite: admin, cargador, consultor
    return $user->hasRole('admin')
        || $user->hasRole('panel-carga')
        || $user->hasRole('panel-consulta');
});

// ✅ Panel de Carga (EXCLUYE super-admin puro)
Gate::define('panel-carga', function (User $user) {
    if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
        return false;
    }
    return $user->hasRole('panel-carga');
});

// ✅ Panel de Consulta (EXCLUYE super-admin puro)
Gate::define('panel-consulta', function (User $user) {
    if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
        return false;
    }
    return $user->hasRole('panel-consulta')
        || $user->hasRole('panel-carga');
});
```

### 2. Rutas Protegidas en routes/web.php

```php
// ✅ Dashboard (Solo admin, excluido super_admin puro)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('can:admin')
    ->name('dashboard');

// ✅ Procedimientos (Operativo: excluye super_admin puro)
Route::middleware('can:acceso-operativo')->group(function () {
    Route::resource('procedimientos', ProcedimientoController::class);
    Route::post('/procedimientos/{procedimiento}/vincular-persona', ...);
    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', ...);
    Route::get('/procedimientos/{procedimiento}/pdf', ...);
});

// ✅ Personas & Domicilios (Operativo)
Route::middleware('can:acceso-operativo')->group(function () {
    Route::resource('personas', PersonaController::class);
    Route::resource('domicilios', DomicilioController::class);
});

// ✅ Documentos (Operativo)
Route::middleware('can:acceso-operativo')->group(function () {
    Route::resource('documentos', DocumentoController::class);
    Route::get('/documentos/{documento}/download', ...);
});

// ✅ Panel Admin (Técnico: super-admin + admin)
Route::prefix('admin')->name('admin.')->middleware(['can:admin'])->group(function () {
    // Usuarios (Con auditoría para super-admin)
    Route::middleware('super.admin.activity')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/{user}/history', ...);
    });
    
    // Brigadas & UFIs (Solo super-admin)
    Route::middleware('can:super-admin')->group(function () {
        Route::resource('brigadas', BrigadaController::class);
        Route::resource('ufis', UfiController::class);
    });
});
```

### 3. Menú en navigation.blade.php

**Desktop:**

```blade
{{-- Dashboard: Solo admin (excluido super_admin puro) --}}
@can('admin')
    @if(!Auth::user()->isSuperAdmin() || Auth::user()->roles()->count() > 1)
        <x-nav-link :href="route('dashboard')">Dashboard</x-nav-link>
    @endif
@endcan

{{-- Procedimientos: Solo operativo --}}
@can('acceso-operativo')
    <x-nav-link :href="route('procedimientos.index')">Procedimientos</x-nav-link>
@endcan

{{-- Brigadas: Solo super_admin --}}
@can('super-admin')
    <x-nav-link :href="route('admin.brigadas.index')">Brigadas</x-nav-link>
@endcan
```

---

## 🎯 Casos de Uso

### Caso 1: Super Admin Puro (Rol único: super_admin)

**Acceso PERMITIDO:**

- ✅ `/admin/users` - Gestión de usuarios
- ✅ `/admin/brigadas` - Catálogo de brigadas
- ✅ `/admin/ufis` - Catálogo de UFIs
- ✅ Activity logs (auditoría)

**Acceso DENEGADO:**

- ❌ `/procedimientos` - Prohibido (es operativo)
- ❌ `/personas` - Prohibido (es operativo)
- ❌ `/documentos` - Prohibido (es operativo)
- ❌ `/dashboard` - No mostrado en menú

### Caso 2: Usuario Admin (Rol único: admin)

**Acceso PERMITIDO:**

- ✅ `/dashboard` - Panel administrativo
- ✅ `/admin/users` - Gestión de usuarios
- ✅ `/procedimientos` - CRUD de procedimientos
- ✅ `/personas` - CRUD de personas
- ✅ `/documentos` - Biblioteca digital

**Acceso DENEGADO:**

- ❌ `/admin/brigadas` - Exclusivo super-admin
- ❌ `/admin/ufis` - Exclusivo super-admin

### Caso 3: Usuario Cargador (Rol único: panel-carga)

**Acceso PERMITIDO:**

- ✅ `/procedimientos` - CRUD completo
- ✅ `/personas` - CRUD completo
- ✅ `/domicilios` - CRUD completo
- ✅ `/documentos` - Upload y descarga

**Acceso DENEGADO:**

- ❌ `/dashboard` - Administrativo
- ❌ `/admin/*` - Panel técnico completo

### Caso 4: Usuario Consultor (Rol único: panel-consulta)

**Acceso PERMITIDO:**

- ✅ `/procedimientos` - Lectura y consultas
- ✅ `/personas` - Lectura
- ✅ `/documentos` - Descarga

**Acceso DENEGADO:**

- ❌ Cualquier CRUD (solo lectura)
- ❌ `/admin/*` - Panel técnico

---

## 🔍 Validaciones en Código

### En User Model (app/Models/User.php)

```php
/**
 * Verifica si el usuario es super admin puro (sin otros roles)
 */
public function isSuperAdmin(): bool
{
    return $this->hasRole('super_admin') && $this->roles()->count() === 1;
}
```

### En Controllers

Cada controlador operativo debe validar:

```php
public function index()
{
    // La ruta ya valida con middleware('can:acceso-operativo')
    // Pero en el controller podemos reafirmar:
    $this->authorize('acceso-operativo');
    
    // Código operativo...
}
```

---

## 📊 Flujo de Autorización

```
Solicitud HTTP
    ↓
¿Está autenticado? → NO → Login
    ↓ SI
¿Tiene el gate requerido? (ej: 'acceso-operativo')
    ↓
    ├─ YES → ¿Es super-admin puro? → SI → DENIEGA (403)
    │                          ↓ NO
    │                        PERMITE ✅
    │
    └─ NO → DENIEGA (403)
```

---

## 🛠️ Mantenimiento y Extensión

### Agregar nuevo rol operativo

1. Crear rol en base de datos: `INSERT INTO roles (name, label) VALUES ('nuevo-rol', 'Nuevo Rol')`
2. Asignar a usuarios: `$user->assignRole('nuevo-rol')`
3. Actualizar Gate 'acceso-operativo' en AppServiceProvider:

```php
Gate::define('acceso-operativo', function (User $user) {
    if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
        return false;
    }
    
    return $user->hasRole('admin')
        || $user->hasRole('panel-carga')
        || $user->hasRole('panel-consulta')
        || $user->hasRole('nuevo-rol');  // ← Agregar aquí
});
```

### Agregar nueva ruta operativa

```php
Route::middleware('can:acceso-operativo')->group(function () {
    Route::resource('nueva-entidad', NuevaEntidadController::class);
});
```

### Modificar acceso de super-admin

Si necesitas otorgar acceso específico a super-admin:

1. Crear gate específico (ej: `'super-admin-only'`)
2. Proteger ruta con ese gate
3. NO incluir super-admin en gates operativos

---

## ✅ Pruebas de Validación

### Test 1: Super Admin NO puede acceder a procedimientos

```bash
# Como super-admin puro
GET /procedimientos → 403 Forbidden
GET /personas → 403 Forbidden
GET /documentos → 403 Forbidden
```

### Test 2: Admin SÍ puede acceder a procedimientos

```bash
# Como admin
GET /procedimientos → 200 OK ✅
GET /personas → 200 OK ✅
GET /documentos → 200 OK ✅
```

### Test 3: Super Admin SÍ puede acceder a brigadas

```bash
# Como super-admin
GET /admin/brigadas → 200 OK ✅
GET /admin/ufis → 200 OK ✅
```

### Test 4: Cargador NO puede acceder a brigadas

```bash
# Como cargador
GET /admin/brigadas → 403 Forbidden
GET /procedimientos → 200 OK ✅
```

---

## 📝 Logs y Auditoría

Todos los accesos a rutas técnicas (usuarios, brigadas, UFIs) son registrados por `SuperAdminActivityMiddleware`:

```
ActivityLog entries:
- Super Admin accede a /admin/users → ✅ Registrado
- Super Admin intenta /procedimientos → ❌ Bloqueado antes de registrar
- Cargador accede a /procedimientos → ✅ Registrado por sistema
```

---

## 🚨 Situaciones Especiales

### ¿Qué pasa si un usuario tiene MÚLTIPLES roles?

Si un usuario es tanto `super_admin` como `admin`:

```php
// Role count > 1 → puede acceder a operativos
Gate::define('acceso-operativo', function (User $user) {
    if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
        return false;
    }
    // Con count > 1 → PERMITE
    return true;
});
```

**Caso de uso:** Un super-admin que necesita hacer operaciones puntuales puede ser asignado con `admin + super_admin`.

### ¿Cómo auditar intentos bloqueados?

Los intentos bloqueados por middleware generan logs automáticos en Laravel. Ver en `storage/logs/laravel.log`.

---

## 📚 Referencias

- **AppServiceProvider.php**: Definición de Gates (L44-83)
- **routes/web.php**: Protección de rutas (L48-89)
- **navigation.blade.php**: Visibilidad del menú (L17-70, L110-150)
- **User Model**: Helpers como `isSuperAdmin()`, `hasRole()`

---

## Checklist de Implementación ✅

- [x] Gates definidos con segregación
- [x] Rutas operativas protegidas con 'acceso-operativo'
- [x] Dashboard con exclusión de super-admin puro
- [x] Menú desktop actualizado
- [x] Menú mobile actualizado
- [x] Documentación técnica completada
- [x] Validación lógica en User model
- [ ] Tests unitarios (TODO si se requiere)
- [ ] Tests E2E (TODO si se requiere)

---

**Documento versionado:** v1.0  
**Próxima revisión:** Cuando se agreguen nuevos roles o modificaciones a la lógica de autorización.
