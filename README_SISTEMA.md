# 🚀 ARDIP - Sistema de Gestión Policial

## 📋 Descripción

Sistema web completo para la gestión de procedimientos policiales, brigadas, UFIs y personal administrativo.

---

## ✨ Características Principales

### 🔐 Sistema de Roles Jerárquico

- **Super Admin**: Control total del sistema
- **Admin**: Gestión de usuarios y configuración
- **Panel Carga**: Creación y edición de datos
- **Panel Consulta**: Solo lectura

### 👥 Gestión de Usuarios

- CRUD completo con auditoría
- Asignación de roles y brigadas
- Activación/desactivación de cuentas
- Historial completo de actividad por usuario
- Prevención de auto-eliminación
- Protección del último super admin

### 📊 Catálogos Administrativos

- **Brigadas**: Gestión de equipos de trabajo
- **UFIs**: Unidades Fiscales de Investigación
- Prevención de eliminación con datos asociados
- Contador de registros relacionados

### 📝 Módulo de Procedimientos

- Gestión completa de procedimientos
- Vinculación con personas y domicilios
- Generación de PDF
- Biblioteca digital integrada

### 🔍 Sistema de Auditoría

- Registro automático de todas las acciones
- Niveles de severidad (info, warning, critical)
- Tracking de IP y dispositivo
- Historial detallado por usuario
- Propiedades JSON para datos complejos

### 📱 Diseño Responsive

- Mobile-first approach
- Breakpoint principal: 768px
- Vistas duales: tabla desktop / cards mobile
- Optimizado para todos los dispositivos

---

## 🛠️ Tecnologías

- **Framework**: Laravel 11+
- **Frontend**: Blade + Tailwind CSS v3.1.0 + Alpine.js v3.4.2
- **Base de Datos**: MySQL/MariaDB
- **PHP**: 8.2+

---

## 📦 Instalación

### 1. Clonar Repositorio

```bash
git clone [URL_REPOSITORIO]
cd ARDIP
```

### 2. Instalar Dependencias

```bash
composer install
npm install
```

### 3. Configurar Entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales de base de datos.

### 4. Migrar Base de Datos

```bash
php artisan migrate
php artisan db:seed
```

### 5. Compilar Assets

```bash
npm run dev
# o para producción:
npm run build
```

### 6. Registrar Middleware SuperAdminActivity

Edita `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'super.admin.activity' => \App\Http\Middleware\SuperAdminActivity::class,
    ]);
    // ... resto del código
})
```

### 7. Asignar Rol Super Admin

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@ardip.com')->first();
$role = App\Models\Role::where('name', 'super_admin')->first();
$user->roles()->syncWithoutDetaching([$role->id]);
exit
```

---

## 🗂️ Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── UserController.php       # Gestión usuarios
│   │   │   ├── BrigadaController.php    # Gestión brigadas
│   │   │   └── UfiController.php        # Gestión UFIs
│   │   ├── ProcedimientoController.php
│   │   ├── PersonaController.php
│   │   └── DocumentoController.php
│   ├── Middleware/
│   │   └── SuperAdminActivity.php       # Auditoría automática
│   └── Requests/
│       ├── StoreUserRequest.php
│       └── UpdateUserRequest.php
├── Models/
│   ├── User.php                         # Usuario con métodos de rol
│   ├── Role.php                         # Roles del sistema
│   ├── ActivityLog.php                  # Auditoría
│   ├── Brigada.php
│   ├── Ufi.php
│   ├── Procedimiento.php
│   └── Persona.php
└── Providers/
    └── AppServiceProvider.php           # Gates de autorización

database/
├── migrations/
│   ├── 2026_01_06_020214_insert_super_admin_role.php
│   ├── 2026_01_06_020236_create_activity_logs_table.php
│   └── 2026_01_06_021120_add_active_and_last_login_to_users_table.php
└── seeders/
    └── RoleSeeder.php

resources/
└── views/
    ├── admin/
    │   ├── users/                       # 5 vistas usuarios
    │   ├── brigadas/                    # 3 vistas brigadas
    │   └── ufis/                        # 3 vistas UFIs
    ├── procedimientos/
    ├── personas/
    └── layouts/
        └── navigation.blade.php         # Menú principal

routes/
└── web.php                              # 20 rutas admin
```

---

## 🔑 URLs Principales

### Panel Admin

```
/admin/users           # Gestión usuarios
/admin/brigadas        # Gestión brigadas
/admin/ufis            # Gestión UFIs
```

### Módulos

```
/procedimientos        # Procedimientos
/personas              # Personas
/domicilios            # Domicilios
/documentos            # Biblioteca digital
```

### Dashboard

```
/dashboard             # Dashboard admin
/dashboard-consultor   # Dashboard consultor
```

---

## 🔐 Sistema de Permisos

### Gates Definidos

```php
super-admin    → Solo super_admin
admin          → admin O super_admin
panel-carga    → panel-carga O super_admin
panel-consulta → panel-consulta O panel-carga O admin O super_admin
```

### Middleware

- `auth`: Usuario autenticado
- `verified`: Email verificado
- `can:admin`: Requiere permiso admin
- `can:super-admin`: Requiere permiso super-admin
- `super.admin.activity`: Auditoría automática para super admins

---

## 📊 Base de Datos

### Tablas Principales

**users**

- id, name, email, password
- brigada_id (FK)
- active (boolean)
- last_login_at
- timestamps

**roles**

- id, name, label
- timestamps

**role_user** (pivot)

- role_id, user_id

**activity_logs**

- id, user_id (FK)
- action, model_type, model_id
- description
- properties (JSON)
- ip_address, user_agent
- severity (info/warning/critical)
- timestamps

**brigadas**

- id, nombre
- timestamps

**ufis**

- id, nombre
- NO timestamps (datos maestros)

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Verificar rutas
php artisan route:list --path=admin

# Limpiar caché
php artisan optimize:clear
```

---

## 📝 Comandos Útiles

```bash
# Ver últimos logs de auditoría
php artisan tinker
ActivityLog::latest()->limit(10)->get(['action', 'description', 'created_at']);

# Verificar usuarios con roles
User::with('roles')->get(['id', 'name', 'email']);

# Listar brigadas con contador de usuarios
Brigada::withCount('users')->get();

# Listar UFIs con contador de procedimientos
Ufi::withCount('procedimientos')->get();
```

---

## 🚨 Troubleshooting

### Error: "Middleware not found"

→ Verifica que registraste el middleware en `bootstrap/app.php`

### Error: "Gate does not exist"

→ Ejecuta `php artisan config:cache`

### No puedo acceder a /admin/users

→ Verifica que tu usuario tenga rol `admin` o `super_admin`

### Errores en IDE (rojo)

→ Los métodos `hasRole()`, `isSuperAdmin()` tienen anotaciones PHPDoc, es un falso positivo del IDE

---

## 📚 Documentación Adicional

- `INICIO_RAPIDO_USUARIOS.md` - Guía rápida módulo usuarios
- `MODULO_GESTION_USUARIOS.md` - Documentación completa usuarios
- `SUPER_ADMIN_SETUP.md` - Setup super admin detallado
- `docs_obsoletos/` - Documentación legacy (archivo)

---

## 👨‍💻 Desarrollo

### Agregar Nueva Funcionalidad

1. Crear migración: `php artisan make:migration`
2. Crear modelo: `php artisan make:model`
3. Crear controlador: `php artisan make:controller`
4. Agregar rutas en `routes/web.php`
5. Crear vistas responsive (desktop + mobile)
6. Integrar auditoría con `ActivityLog::log()`

### Patrón de Controladores Admin

```php
class MiController extends Controller
{
    public function index() {
        ActivityLog::log('accion', 'descripción');
        // lógica...
    }
}
```

### Patrón de Vistas Responsive

```blade
<!-- Desktop: Tabla -->
<div class="hidden md:block">
    <table>...</table>
</div>

<!-- Mobile: Cards -->
<div class="md:hidden">
    @foreach($items as $item)
        <div class="card">...</div>
    @endforeach
</div>
```

---

## 📄 Licencia

[Especificar licencia del proyecto]

---

## 👥 Contribuidores

[Lista de contribuidores]

---

## 📞 Contacto

[Información de contacto]

---

**Última actualización**: 5 de enero de 2026  
**Versión**: 2.0 (con Super Admin y Auditoría completa)
