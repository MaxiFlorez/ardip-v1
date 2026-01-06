# 🚀 ARDIP - Sistema de Gestión Policial

Sistema web profesional para la gestión de procedimientos policiales, brigadas, UFIs y personal administrativo.

---

## ⚡ Inicio Rápido

```bash
# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate
php artisan db:seed

# Compilar assets
npm run dev

# Iniciar servidor
php artisan serve
```

---

## 📚 Documentación

- **[README_SISTEMA.md](README_SISTEMA.md)** - Documentación completa del sistema
- **[INICIO_RAPIDO_USUARIOS.md](INICIO_RAPIDO_USUARIOS.md)** - Guía rápida módulo usuarios
- **[MODULO_GESTION_USUARIOS.md](MODULO_GESTION_USUARIOS.md)** - Gestión de usuarios detallada
- **[SUPER_ADMIN_SETUP.md](SUPER_ADMIN_SETUP.md)** - Configuración super admin

---

## ✨ Características Principales

- ✅ Sistema de roles jerárquico (Super Admin, Admin, Carga, Consulta)
- ✅ Gestión completa de usuarios con auditoría
- ✅ Catálogos administrativos (Brigadas, UFIs)
- ✅ Módulo de procedimientos policiales
- ✅ Sistema de auditoría avanzado
- ✅ Diseño 100% responsive (mobile-first)
- ✅ Biblioteca digital integrada

---

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
