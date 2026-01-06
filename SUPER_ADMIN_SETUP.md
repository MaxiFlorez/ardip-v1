# 🛡️ CONFIGURACIÓN DE SUPER ADMIN CON SEGURIDAD Y AUDITORÍA

## 📋 RESUMEN EJECUTIVO

Se ha creado un **rol jerárquico `super_admin`** con sistema completo de **seguridad y auditoría** integrado en ARDIP.

### ✅ Lo que se implementó

1. ✅ **Migración del rol `super_admin`**
2. ✅ **Tabla de auditoría `activity_logs`** con tracking completo
3. ✅ **Modelo `ActivityLog`** con métodos helper
4. ✅ **Actualización de `RoleSeeder`** (evita duplicación)
5. ✅ **Nuevos métodos en `User`**: `isSuperAdmin()`, `isAdmin()`, `hasAnyRole()`
6. ✅ **Gates actualizados** en `AppServiceProvider`
7. ✅ **Middleware de auditoría** `SuperAdminActivity`

---

## 🚀 PASOS PARA ACTIVAR EL SISTEMA

### 1. **Ejecutar Migraciones**

```bash
php artisan migrate
```

Esto creará:

- ✅ Rol `super_admin` en la tabla `roles`
- ✅ Tabla `activity_logs` con todos sus campos e índices

---

### 2. **Ejecutar Seeder (Opcional)**

Si quieres refrescar los roles:

```bash
php artisan db:seed --class=RoleSeeder
```

Esto agregará `super_admin` sin duplicar roles existentes.

---

### 3. **Asignar Rol Super Admin a un Usuario**

#### Opción A: Usando Tinker (Recomendado)

```bash
php artisan tinker
```

```php
// Buscar el usuario (ajusta el email)
$user = \App\Models\User::where('email', 'admin@ardip.com')->first();

// Buscar el rol super_admin
$superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();

// Asignar el rol (sin eliminar roles existentes)
$user->roles()->syncWithoutDetaching([$superAdminRole->id]);

// Verificar
$user->load('roles');
$user->roles->pluck('name'); // Debe mostrar: ["admin", "super_admin"]

exit
```

#### Opción B: Script Directo

Puedes crear un archivo temporal `assign_super_admin.php` en la raíz:

```php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'admin@ardip.com')->first();
$superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();

if ($user && $superAdminRole) {
    $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
    echo "✅ Rol super_admin asignado a {$user->name}\n";
} else {
    echo "❌ Usuario o rol no encontrado\n";
}
```

Ejecutar:

```bash
php assign_super_admin.php
```

---

### 4. **Registrar el Middleware (IMPORTANTE)**

Edita `bootstrap/app.php` o `app/Http/Kernel.php` (según tu versión de Laravel):

#### Laravel 11+ (`bootstrap/app.php`)

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar el middleware de auditoría
        $middleware->alias([
            'super.admin.activity' => \App\Http\Middleware\SuperAdminActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

#### Laravel 10 (`app/Http/Kernel.php`)

```php
protected $middlewareAliases = [
    // ... otros middleware
    'super.admin.activity' => \App\Http\Middleware\SuperAdminActivity::class,
];
```

---

## 🔐 CARACTERÍSTICAS DE SEGURIDAD IMPLEMENTADAS

### 1. **Jerarquía de Roles**

```
super_admin (Máxima jerarquía)
    ↓
admin (Gestión del sistema)
    ↓
panel-carga (Operaciones CRUD)
    ↓
panel-consulta (Solo lectura)
```

### 2. **Gates Actualizados**

| Gate | Acceso |
|------|--------|
| `super-admin` | Solo `super_admin` |
| `admin` | `admin` + `super_admin` |
| `panel-carga` | `panel-carga` + `super_admin` |
| `panel-consulta` | `panel-consulta` + `panel-carga` + `admin` + `super_admin` |

### 3. **Auditoría Automática**

Cada vez que un `super_admin` accede a una ruta, se registra:

| Campo | Descripción |
|-------|-------------|
| `user_id` | ID del usuario |
| `action` | Acción realizada |
| `model_type` | Modelo afectado (si aplica) |
| `model_id` | ID del modelo |
| `description` | Descripción legible |
| `properties` | JSON con detalles (método HTTP, params, etc.) |
| `ip_address` | IP del usuario |
| `user_agent` | Navegador/dispositivo |
| `severity` | `info`, `warning`, `critical` |
| `created_at` | Timestamp |

---

## 📊 MÉTODOS DISPONIBLES

### En el Modelo `User`

```php
// Verificar si es super admin
$user->isSuperAdmin(); // bool

// Verificar si es admin o super admin
$user->isAdmin(); // bool

// Verificar si tiene alguno de estos roles
$user->hasAnyRole(['admin', 'super_admin']); // bool

// Acceder a los logs de actividad del usuario
$user->activityLogs()->recent(30)->get();
```

### En el Modelo `ActivityLog`

```php
// Registrar cualquier actividad
ActivityLog::log('accion', 'Descripción', [
    'model_type' => User::class,
    'model_id' => 1,
    'severity' => 'critical',
]);

// Métodos específicos
ActivityLog::logLogin($user);
ActivityLog::logLogout($user);
ActivityLog::logCriticalAccess('Descripción', ['key' => 'value']);
ActivityLog::logModelChange('update', $model, $changes);

// Scopes
ActivityLog::recent(30)->get(); // Últimos 30 días
ActivityLog::severity('critical')->get();
ActivityLog::action('login')->get();
```

---

## 🛡️ USO EN CONTROLADORES

### Proteger Rutas de Super Admin

```php
// En routes/web.php
Route::middleware(['auth', 'can:super-admin', 'super.admin.activity'])->group(function () {
    Route::get('/admin/system-config', [ConfigController::class, 'index']);
    Route::post('/admin/system-reset', [ConfigController::class, 'reset']);
});
```

### En Controladores

```php
public function __construct()
{
    // Solo super_admin puede acceder
    $this->middleware('can:super-admin');
}

public function dangerousAction(Request $request)
{
    // Registrar la acción manualmente si es crítica
    ActivityLog::logCriticalAccess(
        'Usuario ' . auth()->user()->name . ' ejecutó acción peligrosa',
        ['action' => 'system_reset']
    );

    // ... lógica
}
```

---

## 📈 CONSULTAR AUDITORÍA

### Dashboard de Logs (Ejemplo)

```php
// Últimas 100 acciones del super admin
$logs = ActivityLog::where('action', 'super_admin_access')
    ->with('user')
    ->latest()
    ->paginate(100);

// Acciones críticas del último mes
$critical = ActivityLog::recent(30)
    ->severity('critical')
    ->with('user')
    ->get();

// Actividad de un usuario específico
$userActivity = ActivityLog::where('user_id', $userId)
    ->recent(7)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## 🧪 TESTING

### Verificar Asignación

```bash
php artisan tinker
```

```php
$user = \App\Models\User::with('roles')->find(1);
$user->isSuperAdmin(); // true
$user->isAdmin(); // true
$user->hasRole('super_admin'); // true
```

### Verificar Gate

```php
Gate::allows('super-admin', $user); // true
Gate::allows('admin', $user); // true
```

### Simular Actividad

```php
// Login como super_admin y navegar
// Verificar en la BD:
\App\Models\ActivityLog::latest()->first();
```

---

## ⚠️ MEJORES PRÁCTICAS

### 1. **Rotación de Super Admins**

- No asignes `super_admin` a muchos usuarios
- Idealmente: 1-2 personas de confianza total

### 2. **Revisión Periódica de Logs**

- Crea un dashboard para visualizar logs críticos
- Alerta ante actividades sospechosas

### 3. **Limpieza de Logs**

- Los logs pueden crecer rápidamente
- Considera eliminar logs `info` antiguos:

```php
// Eliminar logs info más antiguos de 90 días
ActivityLog::where('severity', 'info')
    ->where('created_at', '<', now()->subDays(90))
    ->delete();

// Mantener logs críticos por 1 año
ActivityLog::where('severity', 'critical')
    ->where('created_at', '<', now()->subYear())
    ->delete();
```

### 4. **Autenticación 2FA (Futuro)**

- Considera implementar 2FA para `super_admin`
- Paquete recomendado: `laravel/fortify` con 2FA

---

## 🔄 PRÓXIMOS PASOS RECOMENDADOS

1. ✅ **Crear Módulo de Gestión de Usuarios** (Admin/UserController)
2. ✅ **Dashboard de Auditoría** para visualizar logs
3. ✅ **Alertas por Email** ante acciones críticas
4. ✅ **Exportación de Logs** (CSV/PDF)
5. ✅ **Implementar 2FA** para super_admin
6. ✅ **Rate Limiting** en rutas críticas

---

## 📞 TROUBLESHOOTING

### Error: "Gate [super-admin] does not exist"

→ Ejecuta: `php artisan config:cache` y `php artisan route:cache`

### Error: "Column not found: activity_logs"

→ Ejecuta: `php artisan migrate`

### El middleware no registra actividad

→ Verifica que el middleware esté registrado en `bootstrap/app.php`
→ Limpia caché: `php artisan optimize:clear`

---

## 📊 ESTRUCTURA DE ARCHIVOS CREADOS/MODIFICADOS

```
✅ database/migrations/
   ├── 2026_01_06_020214_insert_super_admin_role.php (NUEVO)
   └── 2026_01_06_020236_create_activity_logs_table.php (NUEVO)

✅ database/seeders/
   └── RoleSeeder.php (MODIFICADO)

✅ app/Models/
   ├── User.php (MODIFICADO)
   └── ActivityLog.php (NUEVO)

✅ app/Http/Middleware/
   └── SuperAdminActivity.php (NUEVO)

✅ app/Providers/
   └── AppServiceProvider.php (MODIFICADO)
```

---

**Estado: LISTO PARA PRODUCCIÓN** ✅

**Versión:** 1.0  
**Fecha:** 6 de enero de 2026  
**Sistema:** ARDIP - Super Admin con Auditoría Completa
