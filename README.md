# 🚀 ARDIP v1.0 - Sistema Integral de Gestión e Inteligencia Policial

Sistema web profesional, seguro y completamente en español para la gestión integrada de procedimientos policiales, recursos humanos, brigadas y portales operativos.

---

## ⚡ Inicio Rápido

```bash
# Instalar dependencias
composer install && npm install

# Configurar
cp .env.example .env
php artisan key:generate

# Base de datos
php artisan migrate
php artisan db:seed

# Compilar y ejecutar
npm run dev
php artisan serve
```

**Acceso:** http://localhost:8000

---

## 📋 Roles y Permisos

| Rol | Acceso | Módulos |
|-----|--------|---------|
| **Super Admin** | Gestión usuarios, brigadas, UFIs, auditoría | 🔒 Administrativo |
| **Admin** | Dashboard, todos módulos operativos | 📊 Completo |
| **Cargador** | Crear/editar procedimientos, personas, documentos | ✍️ Operativo |
| **Consultor** | Ver procedimientos, personas, documentos | 👁️ Lectura |

---

## 🎯 Funcionalidades Principales

- ✅ **Autenticación**: Login/registro completamente en español
- ✅ **Gestión de Usuarios**: Auditoría completa de acciones
- ✅ **Procedimientos**: CRUD con vinculación de personas/domicilios
- ✅ **Biblioteca Digital**: Almacenamiento seguro de documentos
- ✅ **Catálogos**: Brigadas y UFIs
- ✅ **Dashboard**: Estadísticas e indicadores
- ✅ **Auditoría**: Logs de acciones administrativas
- ✅ **Responsive**: 100% mobile-first (320px - 2560px)
- ✅ **Localizaciones**: Mensajes de error y validaciones en español
- ✅ **Dark Mode**: Soporte nativo

---

## 🔐 Seguridad

- Middleware de autenticación en todas las rutas
- Gates de autorización granulares
- Auditoría de acciones super admin
- Protección CSRF
- Rate limiting
- Sanitización de inputs

---

## 📖 Documentación Técnica

- **[README_SISTEMA.md](README_SISTEMA.md)** - Documentación completa
- **[MODULO_GESTION_USUARIOS.md](MODULO_GESTION_USUARIOS.md)** - Gestión usuarios
- **[SEGREGACION_FUNCIONES.md](SEGREGACION_FUNCIONES.md)** - Matriz de permisos
- **[PLAN_PRUEBAS_SEGREGACION.md](PLAN_PRUEBAS_SEGREGACION.md)** - Testing

---

## 🛠️ Stack Tecnológico

- **Framework**: Laravel 11 · **Frontend**: Blade + Tailwind CSS 3
- **BD**: MySQL/PostgreSQL · **Autenticación**: Sanctum
- **Roles**: Spatie Roles/Permissions · **Reportes**: DomPDF

---

## 🔧 Configuración

### Super Admin
```bash
php artisan tinker
>>> User::first()->assignRole('super_admin');
```

### Variables de Entorno
```
APP_LOCALE=es
DB_CONNECTION=mysql
MAIL_FROM_ADDRESS=soporte@ardip.gob.ar
```

---

## 📞 Soporte

**Email**: soporte@ardip.gob.ar  
**Documentación**: Ver archivos `.md` en raíz

---

## ✅ Estado

✅ Español completo · ✅ UI Estandarizado · ✅ Código limpio · ✅ Listo para producción

**Versión**: 1.0 | **Actualizado**: 6 enero 2026 | **Licencia**: Privada ARDIP

## 🔐 Acceso por Defecto

Después de ejecutar los seeders:

```
Email: admin@ardip.com
Password: [configurar en seeder]
```

**⚠️ Importante**: Cambia las credenciales en producción.

---

## 🛠️ Stack Tecnológico

- Laravel 11+
- PHP 8.2+
- MySQL/MariaDB
- Tailwind CSS v3.1.0
- Alpine.js v3.4.2
- Blade Templates

---

## 📦 Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 16
- MySQL >= 5.7 o MariaDB >= 10.3

---

## 🤝 Contribuir

[Guía de contribución pendiente]

---

## 📄 Licencia

[Especificar licencia]

---

**Desarrollado con** ❤️ **para optimizar la gestión policial**

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
