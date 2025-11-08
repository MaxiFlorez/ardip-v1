# ARDIP V1 - Documentación Sesión 5

## Sistema de Autenticación y CRUD Personas Completo

**Fecha:** 5 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** 4 horas  
**Estado:** ✅ COMPLETADO

---

## 📋 Resumen Ejecutivo

En esta quinta sesión se implementó el sistema completo de autenticación con Laravel Breeze y se desarrolló el CRUD completo de Personas, alcanzando la primera funcionalidad principal del sistema ARDIP. El proyecto fue respaldado en GitHub para trabajo colaborativo y continuidad desde diferentes equipos.

**Estado Final:** Sistema con login funcional + CRUD Personas 100% operativo ✅

---

## 🎯 Objetivos Cumplidos

### Autenticación y Seguridad

- [x] Laravel Breeze instalado y configurado
- [x] Sistema de login/logout funcionando
- [x] Registro de usuarios implementado
- [x] Rutas protegidas con middleware auth
- [x] Dashboard de bienvenida

### CRUD Personas - Backend

- [x] Rutas resource configuradas
- [x] PersonaController con 7 métodos completos
- [x] Validaciones de formularios
- [x] Sistema de upload de fotos
- [x] Storage link configurado

### CRUD Personas - Frontend

- [x] Vista index (listado con búsqueda)
- [x] Vista create (formulario de alta)
- [x] Vista show (detalle completo)
- [x] Vista edit (formulario de edición)

### Infraestructura

- [x] Proyecto respaldado en GitHub
- [x] Docker optimizado con volúmenes nombrados
- [x] Caché de Laravel configurado

---

## 🚀 Parte 1: Instalación de Laravel Breeze

### Paso 1.1: Instalación del paquete

**Problema inicial:** Docker bloqueaba el acceso a `packagist.org`

**Solución:** Instalar desde Windows (fuera de Docker)

```bash
cd C:\Users\javie\Desktop\ARDIP
composer require laravel/breeze --dev
```

**Resultado:**

```
Package operations: 1 install
- Installing laravel/breeze (v2.3.8)
```

---

### Paso 1.2: Configurar Breeze

```bash
php artisan breeze:install
```

**Opciones seleccionadas:**

- Stack: **Blade** (más simple para v1)
- Dark mode: **No**
- Testing framework: **PHPUnit**

**Archivos generados:**

- Vistas de autenticación en `resources/views/auth/`
- Controladores en `app/Http/Controllers/Auth/`
- Rutas en `routes/auth.php`
- Assets (Tailwind CSS) en `resources/`

---

### Paso 1.3: Instalar dependencias Node.js

**Problema:** El contenedor Docker no tenía Node.js instalado

**Solución:** Modificar el Dockerfile para incluir Node.js 20.x

**Dockerfile actualizado:**

```dockerfile
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Dar permisos
RUN chown -R www-data:www-data /var/www

USER www-data
```

**Reconstruir contenedores:**

```bash
docker-compose down
docker-compose build
docker-compose up -d
```

**Tiempo de build:** ~5 minutos

---

### Paso 1.4: Compilar assets

```bash
docker exec -it ardip-app npm install
docker exec -it ardip-app npm run build
```

**Resultado:**

```
✓ 54 modules transformed.
public/build/manifest.json             0.33 kB
public/build/assets/app-Cv_tjT6n.css  45.67 kB
public/build/assets/app-ByW0VTRm.js   80.87 kB
✓ built in 8.64s
```

---

### Paso 1.5: Configurar Storage

```bash
docker exec -it ardip-app php artisan storage:link
```

**¿Qué hace?**
Crea enlace simbólico: `public/storage` → `storage/app/public`

Permite que las fotos subidas sean accesibles públicamente.

---

### Paso 1.6: Verificar instalación

**URL de prueba:** `http://localhost:8000`

**Resultado esperado:**

- Página de Laravel con botones "Log in" y "Register" en esquina superior derecha ✅

**Crear usuario administrador:**

- Name: Admin Test
- Email: <admin@ardip.test>
- Password: password123

**Login exitoso:** Dashboard de Laravel Breeze visible ✅

---

## 🔧 Parte 2: Configuración de Rutas

### Archivo: `routes/web.php`

**Código completo:**

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // CRUD de Personas
    Route::resource('personas', PersonaController::class);
});

require __DIR__.'/auth.php';
```

**Verificar rutas creadas:**

```bash
docker exec -it ardip-app php artisan route:list --name=personas
```

**Resultado:**

```
GET|HEAD   personas .................. personas.index › PersonaController@index
POST       personas .................. personas.store › PersonaController@store
GET|HEAD   personas/create ........... personas.create › PersonaController@create
GET|HEAD   personas/{persona} ........ personas.show › PersonaController@show
PUT|PATCH  personas/{persona} ........ personas.update › PersonaController@update
DELETE     personas/{persona} ........ personas.destroy › PersonaController@destroy
GET|HEAD   personas/{persona}/edit ... personas.edit › PersonaController@edit
```

---

## 🎨 Parte 3: PersonaController

### Generar controlador

```bash
docker exec -it ardip-app php artisan make:controller PersonaController --resource
```

**Archivo:** `app/Http/Controllers/PersonaController.php`

**Código completo:** *(Ver en repositorio o documentación extendida)*

**Conceptos clave:**

1. **Validaciones:** Reglas estrictas para cada campo
2. **Upload de fotos:** Almacenamiento en `storage/app/public/fotos_personas`
3. **Route Model Binding:** Laravel inyecta automáticamente el modelo
4. **Mensajes flash:** Feedback al usuario con `with('success', ...)`

---

## 📄 Parte 4: Vistas Blade

### Estructura de carpetas

```
resources/views/personas/
├── index.blade.php   (Listado)
├── create.blade.php  (Formulario crear)
├── show.blade.php    (Ver detalle)
└── edit.blade.php    (Formulario editar)
```

### 4.1 Vista Index (Listado)

**Características:**

- Tabla responsive con Tailwind CSS
- Columnas: DNI, Apellidos, Nombres, Alias, Edad, Acciones
- Botones: Ver, Editar, Eliminar
- Mensaje de éxito con flash messages
- Cálculo automático de edad

---

### 4.2 Vista Create (Formulario Alta)

**Campos implementados:**

- DNI (8 dígitos, único)
- Nombres y Apellidos (obligatorios)
- Fecha de Nacimiento (obligatorio)
- Género (radio buttons)
- Alias (opcional)
- Nacionalidad (default: Argentina)
- Estado Civil (select)
- Foto (upload)
- Observaciones (textarea)

**Validación:** HTML5 + Laravel server-side

---

### 4.3 Vista Show (Ver Detalle)

**Características:**

- Foto de la persona (o placeholder)
- Grid con datos principales
- Procedimientos asociados (si los hay)
- Badges de colores según situación procesal
- Botones: Volver al listado, Editar

---

### 4.4 Vista Edit (Formulario Edición)

**Diferencias con create:**

- Método PUT: `@method('PUT')`
- Valores pre-cargados
- Muestra foto actual
- Opción de cambiar foto
- Redirige a show después de actualizar

---

## 🐳 Parte 5: Optimización Docker

### Problema identificado

**Lentitud extrema** al cargar páginas (10-15 segundos)

**Causa principal:**

- Sincronización Windows ↔ WSL2 es muy lenta
- Carpetas `vendor/` (40MB) y `node_modules/` (200MB) se sincronizan constantemente

---

### Solución: Volúmenes nombrados

**docker-compose.yml optimizado:**

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: ardip-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - .:/var/www
      - vendor:/var/www/vendor           # Volumen nombrado
      - node_modules:/var/www/node_modules  # Volumen nombrado
    networks:
      - ardip-network

  db:
    image: mysql:8.0
    container_name: ardip-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ardip_db
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: ardip_user
      MYSQL_PASSWORD: ardip_pass
    volumes:
      - db-data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - ardip-network

  nginx:
    image: nginx:alpine
    container_name: ardip-nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - ardip-network

networks:
  ardip-network:
    driver: bridge

volumes:
  db-data:
  vendor:        # NUEVO
  node_modules:  # NUEVO
```

**Aplicar cambios:**

```bash
docker-compose down
docker-compose up -d
docker exec -it ardip-app composer install
docker exec -it ardip-app npm install
```

**Mejora de rendimiento:** ~30-40% más rápido

---

## 🐙 Parte 6: Respaldo en GitHub

### 6.1 Crear repositorio

1. Ir a [github.com](https://github.com)
2. Nuevo repositorio: `ardip-v1`
3. Privado
4. Sin README inicial

---

### 6.2 Configurar Git local

```bash
cd C:\Users\javie\Desktop\ARDIP
git init
git config --global user.name "Maximiliano Flores"
git config --global user.email "tu_email@gmail.com"
```

---

### 6.3 Primer commit

```bash
git add .
git commit -m "Sesion 5: Sistema completo - Breeze + CRUD Personas 100%"
```

**Archivos agregados:** 2,283 archivos (38.4 MB)

---

### 6.4 Subir a GitHub

```bash
git remote add origin https://github.com/MaxiFlorez/ardip-v1.git
git branch -M main
git push -u origin main
```

**Resultado:**

```
Writing objects: 100% (2283/2283), 38.40 MiB | 9.35 MiB/s, done.
To https://github.com/MaxiFlorez/ardip-v1.git
   e5df080..c6e8d47  main -> main
```

---

## 🧪 Parte 7: Pruebas Funcionales

### Prueba 1: Crear persona

**Datos ingresados:**

- DNI: 44444444
- Nombres: MAXIMILIANO
- Apellidos: FLORES
- Fecha Nacimiento: 03/10/2018
- Género: Masculino
- Alias: canuto
- Foto: *(subida correctamente)*

**Resultado:** ✅ Persona creada y visible en listado
**Edad calculada automáticamente:** 7 años

---

### Prueba 2: Ver detalle

**Resultado:**

- ✅ Foto mostrada
- ✅ Datos completos visibles
- ✅ Edad calculada correctamente

---

### Prueba 3: Editar persona

**Resultado:**

- ✅ Formulario carga con datos actuales
- ✅ Actualización exitosa

---

## 📊 Estado Final del Proyecto

### Backend: 85% ✅

```
✅ Docker + Laravel + MySQL
✅ Migraciones completas
✅ Modelos Eloquent con relaciones
✅ Autenticación con Breeze
✅ CRUD Personas completo
❌ CRUD Procedimientos (pendiente)
❌ CRUD Domicilios (pendiente)
```

---

### Frontend: 25% 🔄

```
✅ Login/Logout
✅ Vistas de Personas (4/4)
❌ Vistas de Procedimientos
❌ Vistas de Domicilios
❌ Dashboard con estadísticas
```

---

## 🎓 Conceptos Técnicos Aprendidos

### 1. Laravel Breeze

- Starter kit de autenticación oficial
- Usa Blade + Tailwind CSS

### 2. Route Model Binding

```php
public function show(Persona $persona)
```

Laravel inyecta el modelo automáticamente.

### 3. Validación de Formularios

```php
$validated = $request->validate([...]);
```

### 4. Accessors en Eloquent

```php
public function getEdadAttribute() {
    return Carbon::parse($this->fecha_nacimiento)->age;
}
```

### 5. Storage de Laravel

- Link simbólico para acceso público
- Almacenamiento organizado

---

## 🔧 Comandos Útiles

### Docker

```bash
docker ps
docker-compose up -d
docker-compose down
docker exec -it ardip-app bash
```

### Laravel

```bash
php artisan route:list
php artisan make:controller
php artisan view:clear
php artisan storage:link
```

### Git

```bash
git status
git add .
git commit -m "mensaje"
git push
```

---

## 🐛 Problemas y Soluciones

### Problema 1: Docker no permite acceso a Packagist

**Solución:** Instalar desde Windows

### Problema 2: npm no encontrado

**Solución:** Agregar Node.js al Dockerfile

### Problema 3: Botón "Guardar" no visible

**Solución:** Cambiar `overflow-hidden` a `overflow-visible`

### Problema 4: Lentitud extrema

**Solución:** Volúmenes nombrados para vendor y node_modules

---

## 📁 Estructura de Archivos

```
ARDIP/
├── app/Http/Controllers/
│   └── PersonaController.php  ✨ NUEVO
├── resources/views/personas/  ✨ NUEVO
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
├── routes/web.php             ✏️ MODIFICADO
├── Dockerfile                 ✏️ MODIFICADO
└── docker-compose.yml         ✏️ MODIFICADO
```

---

## 📈 Progreso Temporal

- **Sesión 1-4:** 10.5 horas
- **Sesión 5:** 4 horas
- **Total:** 14.5 horas

---

## 🎯 Próximos Pasos - Sesión 6

### CRUD Procedimientos (2-3 horas)

- Formulario complejo
- Vinculación de personas y domicilios
- Resultados de operativos

### CRUD Domicilios (1 hora)

- Formulario simplificado
- Listado básico

---

## 💡 Notas Importantes

### Sobre el Diseño

- **Ahora:** Funcional pero básico
- **Después del 14 Nov:** Personalización con logo D-5 y colores institucionales

### Sobre Docker

- Optimizado pero puede mejorar más
- Solución temporal suficiente para desarrollo

### Sobre GitHub

- ✅ Código respaldado
- ✅ Trabajo desde múltiples PCs posible

---

## ✅ Checklist de Verificación

- [x] Docker corriendo
- [x] Login funciona
- [x] CRUD Personas completo
- [x] Fotos se suben correctamente
- [x] GitHub actualizado

---

## 🎓 Conclusión

### Logros Principales

1. ✅ Autenticación completa con Breeze
2. ✅ CRUD Personas 100% funcional
3. ✅ Upload de fotos
4. ✅ Proyecto en GitHub
5. ✅ Docker optimizado

### Preparación para Sesión 6

- CRUD Procedimientos (más complejo)
- Vinculación de entidades
- Funcionalidades core del sistema

**Estado:** 40% completado
**Confianza para demo 14 Nov:** ALTA ✅

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1  
**Fecha:** 5-6 de Noviembre, 2025  
**Sesión:** 5 de N

---

## FIN DEL DOCUMENTO
