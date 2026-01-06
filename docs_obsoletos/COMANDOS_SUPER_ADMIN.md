# ⚡ COMANDOS RÁPIDOS - SUPER ADMIN

## 🚀 INSTALACIÓN

```bash
# 1. Ejecutar migraciones
php artisan migrate

# 2. (Opcional) Ejecutar seeder
php artisan db:seed --class=RoleSeeder

# 3. Limpiar caché
php artisan optimize:clear
```

---

## 👤 ASIGNAR SUPER ADMIN A UN USUARIO

### Método 1: Tinker (Recomendado)

```bash
php artisan tinker
```

```php
// Asignar a usuario existente por email
$user = \App\Models\User::where('email', 'admin@ardip.com')->first();
$role = \App\Models\Role::where('name', 'super_admin')->first();
$user->roles()->syncWithoutDetaching([$role->id]);

// Verificar
$user->load('roles')->roles->pluck('name');

exit
```

### Método 2: Comando SQL Directo

```bash
php artisan tinker
```

```php
DB::table('role_user')->insert([
    'user_id' => 1, // ID del usuario
    'role_id' => DB::table('roles')->where('name', 'super_admin')->value('id'),
]);

exit
```

---

## 🔍 VERIFICAR ROLES

```bash
php artisan tinker
```

```php
// Ver todos los roles
\App\Models\Role::all(['id', 'name', 'label']);

// Ver usuarios con super_admin
\App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'super_admin');
})->get(['id', 'name', 'email']);

// Verificar roles de un usuario específico
$user = \App\Models\User::find(1);
$user->roles->pluck('name');
$user->isSuperAdmin(); // true/false

exit
```

---

## 📊 CONSULTAR LOGS DE AUDITORÍA

```bash
php artisan tinker
```

```php
// Últimos 10 logs
\App\Models\ActivityLog::latest()->limit(10)->get(['id', 'action', 'description', 'created_at']);

// Logs de super_admin
\App\Models\ActivityLog::where('action', 'super_admin_access')->latest()->limit(20)->get();

// Logs críticos
\App\Models\ActivityLog::where('severity', 'critical')->latest()->get();

// Actividad de un usuario
\App\Models\ActivityLog::where('user_id', 1)->recent(7)->get();

exit
```

---

## 🧹 LIMPIAR LOGS ANTIGUOS

```bash
php artisan tinker
```

```php
// Eliminar logs info mayores a 90 días
\App\Models\ActivityLog::where('severity', 'info')
    ->where('created_at', '<', now()->subDays(90))
    ->delete();

// Eliminar logs warning mayores a 180 días
\App\Models\ActivityLog::where('severity', 'warning')
    ->where('created_at', '<', now()->subDays(180))
    ->delete();

// Mantener solo logs críticos del último año
\App\Models\ActivityLog::where('severity', 'critical')
    ->where('created_at', '<', now()->subYear())
    ->delete();

exit
```

---

## 🔐 REVOCAR SUPER ADMIN

```bash
php artisan tinker
```

```php
$user = \App\Models\User::where('email', 'usuario@ardip.com')->first();
$role = \App\Models\Role::where('name', 'super_admin')->first();

// Quitar solo el rol super_admin
$user->roles()->detach($role->id);

// Verificar
$user->load('roles')->roles->pluck('name');

exit
```

---

## 📈 ESTADÍSTICAS RÁPIDAS

```bash
php artisan tinker
```

```php
// Total de logs por severidad
\App\Models\ActivityLog::selectRaw('severity, COUNT(*) as total')
    ->groupBy('severity')
    ->get();

// Usuarios más activos
\App\Models\ActivityLog::selectRaw('user_id, COUNT(*) as total')
    ->groupBy('user_id')
    ->orderBy('total', 'desc')
    ->limit(10)
    ->get();

// Acciones más comunes
\App\Models\ActivityLog::selectRaw('action, COUNT(*) as total')
    ->groupBy('action')
    ->orderBy('total', 'desc')
    ->get();

exit
```

---

## 🛠️ TROUBLESHOOTING

```bash
# Limpiar todas las cachés
php artisan optimize:clear

# Ver todas las rutas
php artisan route:list

# Ver todos los gates
php artisan tinker
Gate::abilities();
exit

# Verificar configuración
php artisan config:show
```

---

## 🔄 ROLLBACK (Deshacer Cambios)

```bash
# Revertir última migración
php artisan migrate:rollback --step=1

# Revertir migración específica
php artisan migrate:rollback --path=database/migrations/2026_01_06_020214_insert_super_admin_role.php

# Revertir tabla de logs
php artisan migrate:rollback --path=database/migrations/2026_01_06_020236_create_activity_logs_table.php
```

---

## 📝 CREAR USUARIO SUPER ADMIN DESDE CERO

```bash
php artisan tinker
```

```php
// Crear usuario
$user = \App\Models\User::create([
    'name' => 'Super Administrador',
    'email' => 'superadmin@ardip.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
]);

// Asignar rol
$role = \App\Models\Role::where('name', 'super_admin')->first();
$user->roles()->attach($role->id);

// Verificar
$user->isSuperAdmin(); // true

echo "✅ Usuario super_admin creado: {$user->email}\n";

exit
```

---

## 🔍 BUSCAR EN LOGS

```bash
php artisan tinker
```

```php
// Buscar por IP
\App\Models\ActivityLog::where('ip_address', '192.168.1.100')->get();

// Buscar por descripción
\App\Models\ActivityLog::where('description', 'LIKE', '%eliminó%')->get();

// Logs entre fechas
\App\Models\ActivityLog::whereBetween('created_at', [
    now()->subDays(7),
    now()
])->get();

// Logs con propiedades específicas
\App\Models\ActivityLog::whereJsonContains('properties->method', 'DELETE')->get();

exit
```

---

**Guía rápida para administración del rol Super Admin**  
**Sistema:** ARDIP  
**Versión:** 1.0
