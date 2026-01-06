# ⚡ INICIO RÁPIDO - Módulo de Gestión de Usuarios

## 🚀 2 PASOS PARA ACTIVAR EL MÓDULO

### **PASO 1: Registrar el Middleware** (IMPORTANTE)

Edita el archivo correspondiente según tu versión de Laravel:

#### **Laravel 11+ (`bootstrap/app.php`)**

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
        // 👇 AGREGA ESTO
        $middleware->alias([
            'super.admin.activity' => \App\Http\Middleware\SuperAdminActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

#### **Laravel 10 (`app/Http/Kernel.php`)**

```php
protected $middlewareAliases = [
    // ... otros middleware
    'super.admin.activity' => \App\Http\Middleware\SuperAdminActivity::class,
];
```

---

### **PASO 2: Asignar Rol Super Admin o Admin**

```bash
php artisan tinker
```

```php
// Buscar tu usuario
$user = \App\Models\User::where('email', 'TU_EMAIL@ardip.com')->first();

// Buscar el rol admin o super_admin
$role = \App\Models\Role::where('name', 'admin')->first();
// O para super_admin:
// $role = \App\Models\Role::where('name', 'super_admin')->first();

// Asignar rol
$user->roles()->syncWithoutDetaching([$role->id]);

// Verificar
$user->load('roles')->roles->pluck('name'); // Debe mostrar ["admin"] o ["super_admin"]

exit
```

---

## ✅ VERIFICAR QUE FUNCIONA

### 1. **Limpiar Caché**

```bash
php artisan optimize:clear
```

### 2. **Ver las Rutas**

```bash
php artisan route:list --path=admin
```

Deberías ver 8 rutas:

- `GET /admin/users` (index)
- `GET /admin/users/create`
- `POST /admin/users` (store)
- `GET /admin/users/{user}` (show)
- `GET /admin/users/{user}/edit`
- `PUT /admin/users/{user}` (update)
- `DELETE /admin/users/{user}` (destroy)
- `GET /admin/users/{user}/history`

### 3. **Acceder al Módulo**

1. Inicia sesión con tu usuario admin
2. Ve al menú superior
3. Click en **"Gestión Usuarios"**
4. Deberías ver la lista de usuarios

---

## 🎯 PRUEBA RÁPIDA

### **Crear un Usuario de Prueba**

1. En `/admin/users`, click en "➕ Nuevo Usuario"
2. Completa:
   - Nombre: `Test Usuario`
   - Email: `test@ardip.com`
   - Password: `password123`
   - Confirmar Password: `password123`
   - Rol: Consultor
   - Brigada: (deja vacío)
   - Cuenta Activa: ✓ (marcado)
3. Click en "💾 Crear Usuario"
4. Deberías ver el mensaje: "Usuario Test Usuario creado exitosamente."

### **Ver el Historial**

1. En la fila del usuario test, click en 📊
2. Deberías ver el log: "Usuario {tu nombre} creó el usuario: Test Usuario"

### **Editar el Usuario**

1. Click en ✏️ Editar
2. Cambia el nombre a "Test Usuario Editado"
3. Guarda
4. Verifica que el cambio se registró en el historial

### **Eliminar el Usuario**

1. Click en 🗑️ Eliminar
2. Confirma
3. El usuario desaparece de la lista
4. Verifica que se registró en `activity_logs` con severidad **critical**

---

## 📊 VERIFICAR AUDITORÍA

```bash
php artisan tinker
```

```php
// Ver últimos 10 logs
\App\Models\ActivityLog::latest()->limit(10)->get(['id', 'action', 'description', 'created_at']);

// Ver logs de usuarios
\App\Models\ActivityLog::where('action', 'LIKE', '%user%')->latest()->get();

// Ver logs críticos
\App\Models\ActivityLog::where('severity', 'critical')->latest()->get();

exit
```

---

## 🐛 TROUBLESHOOTING

### **Error: "Gate [admin] does not exist"**

→ Ejecuta: `php artisan config:cache`

### **Error: "Middleware [super.admin.activity] not found"**

→ Verifica que registraste el middleware en `bootstrap/app.php` o `app/Http/Kernel.php`

### **No puedo acceder a `/admin/users`**

→ Verifica que tu usuario tenga rol `admin` o `super_admin`:

```bash
php artisan tinker
$user = \App\Models\User::find(1);
$user->roles->pluck('name'); // Debe mostrar ["admin"] o ["super_admin"]
```

### **El menú no muestra "Gestión Usuarios"**

→ Verifica que tengas la directiva `@can('admin')` en `navigation.blade.php`

---

## 📱 URLs IMPORTANTES

```
Lista de Usuarios:      /admin/users
Crear Usuario:          /admin/users/create
Ver Perfil:             /admin/users/{id}
Editar Usuario:         /admin/users/{id}/edit
Ver Historial Completo: /admin/users/{id}/history
```

---

## 🎨 CAPTURAS DE PANTALLA

### **Lista de Usuarios (Desktop)**

```
┌─────────────────────────────────────────────────────────┐
│ 👥 Gestión de Usuarios        [➕ Nuevo Usuario]       │
├─────────────────────────────────────────────────────────┤
│ Buscar: [___________] Rol: [Todos ▼] Estado: [Todos ▼] │
├─────────────────────────────────────────────────────────┤
│ Avatar │ Nombre  │ Rol    │ Estado │ Acciones          │
├────────┼─────────┼────────┼────────┼───────────────────┤
│   J    │ Juan P. │ Admin  │ Activo │ 📊 👁️ ✏️ 🗑️      │
│   M    │ María G.│ Carga. │ Activo │ 📊 👁️ ✏️ 🗑️      │
└─────────────────────────────────────────────────────────┘
```

### **Historial de Usuario**

```
┌──────────────────────────────────────────────────────────┐
│ 📊 Historial Completo: Juan Pérez                       │
├──────────────────────────────────────────────────────────┤
│ Fecha/Hora      │ Acción      │ Severidad │ IP           │
├─────────────────┼─────────────┼───────────┼──────────────┤
│ 06/01 15:30:22 │ user_login  │ INFO      │ 192.168.1.10 │
│ 06/01 15:29:15 │ user_update │ WARNING   │ 192.168.1.10 │
│ 06/01 14:20:00 │ user_login  │ INFO      │ 192.168.1.10 │
└──────────────────────────────────────────────────────────┘
```

---

## ✅ CHECKLIST FINAL

- [ ] Middleware registrado en `bootstrap/app.php`
- [ ] Rol admin/super_admin asignado a tu usuario
- [ ] Caché limpiada con `optimize:clear`
- [ ] Acceso a `/admin/users` funciona
- [ ] Menú "Gestión Usuarios" visible
- [ ] Puedes crear un usuario
- [ ] Puedes editar un usuario
- [ ] Puedes ver historial
- [ ] Puedes eliminar un usuario
- [ ] Los logs se registran en `activity_logs`

---

**¡Listo para usar!** 🚀

Si todo está ✅, el módulo está **100% funcional**.
