# ARDIP V1 - Documentación Sesión 1

## Setup Inicial: Docker + Laravel + MySQL

**Fecha:** 15 de Octubre 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan
---

## 📋 Resumen Ejecutivo

En esta primera sesión se logró configurar exitosamente el entorno de desarrollo completo del proyecto ARDIP utilizando:

- Laravel 12.34.0
- Docker con 3 contenedores (app, mysql, nginx)
- MySQL 8.0
- PHP 8.2
**Estado Final:** Sistema corriendo en `http://localhost:8000` ✅

---

## 🎯 Objetivos Cumplidos

- [x] Verificar herramientas instaladas (PHP, Composer, Docker)
- [x] Crear proyecto Laravel desde cero
- [x] Configurar Docker con 3 contenedores
- [x] Conectar Laravel con MySQL
- [x] Ejecutar migraciones iniciales
- [x] Verificar funcionamiento del sistema

---

## 🛠️ Herramientas Verificadas

### Versiones Instaladas

```bash
PHP: 8.2.12 (XAMPP)
Composer: 2.8.10
Docker: 28.3.2
```

### Ubicación del Proyecto

``` text
C:\Users\javie\Desktop\ARDIP
```

---

## 📝 Paso a Paso - Lo Que Se Hizo

### 1. Instalación de Laravel

**Comando ejecutado:**

```bash
cd C:\Users\javie\Desktop\ARDIP
composer create-project laravel/laravel .
```

**Resultado:**

- Laravel instalado con todas sus dependencias
- Estructura de carpetas creada automáticamente
- Migraciones por defecto generadas

---

### 2. Configuración de Docker

#### Archivo 1: `Dockerfile`

**Ubicación:** Raíz del proyecto (`C:\Users\javie\Desktop\ARDIP\Dockerfile`)

**Contenido:**

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

**¿Qué hace este archivo?**

- Define cómo construir el contenedor de la aplicación Laravel
- Instala PHP 8.2 con las extensiones necesarias (MySQL, GD, etc.)
- Copia Composer para manejar dependencias
- Establece permisos correctos para los archivos

---

#### Archivo 2: `docker-compose.yml`

**Ubicación:** Raíz del proyecto (`C:\Users\javie\Desktop\ARDIP\docker-compose.yml`)

**Contenido:**

```yaml
version: '3.8'

services:
  # Servicio de la aplicación Laravel
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: ardip-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - .:/var/www
    networks:
      - ardip-network

  # Servicio de base de datos MySQL
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

  # Servicio de servidor web Nginx
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
```

**¿Qué hace este archivo?**

- Orquesta 3 contenedores que trabajan juntos
- **app:** Contenedor con Laravel/PHP
- **db:** Contenedor con MySQL 8.0
- **nginx:** Servidor web que sirve la aplicación
- Crea una red interna (`ardip-network`) para que se comuniquen
- Mapea el puerto 8000 de Windows al puerto 80 de Nginx

---

#### Archivo 3: `docker/nginx/conf.d/default.conf`

**Ubicación:** `C:\Users\javie\Desktop\ARDIP\docker\nginx\conf.d\default.conf`

**Contenido:**

```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

**¿Qué hace este archivo?**

- Configura Nginx para servir Laravel correctamente
- Define que la raíz del sitio es `/var/www/public`
- Redirige peticiones PHP al contenedor `app` en el puerto 9000

---

### 3. Configuración de Variables de Entorno

**Archivo modificado:** `.env`

**Cambios realizados:**

**ANTES (SQLite):**

```env
DB_CONNECTION=sqlite
```

**DESPUÉS (MySQL en Docker):**

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ardip_db
DB_USERNAME=ardip_user
DB_PASSWORD=ardip_pass
```

**¿Por qué estos valores?**

- `DB_HOST=db` → Nombre del contenedor MySQL en docker-compose.yml
- `DB_DATABASE=ardip_db` → Base de datos creada automáticamente por Docker
- Usuario y contraseña definidos en docker-compose.yml

---

### 4. Levantar los Contenedores

**Comando ejecutado:**

```bash
docker-compose up -d
```

**¿Qué hizo este comando?**

1. Descargó las imágenes de MySQL y Nginx
2. Construyó la imagen de Laravel según el Dockerfile
3. Creó 3 contenedores:
   - `ardip-app` (Laravel/PHP)
   - `ardip-mysql` (MySQL 8.0)
   - `ardip-nginx` (Servidor web)
4. Creó la red `ardip-network`
5. Inició todos los contenedores en segundo plano (`-d`)

**Tiempo total:** ~4 minutos

**Resultado:**

```text
✔ Container ardip-app          Started
✔ Container ardip-nginx        Started
✔ Container ardip-mysql        Started
```

---

### 5. Verificar Contenedores Corriendo

**Comando ejecutado:**

```bash
docker ps
```

**Resultado:**

```text
CONTAINER ID   IMAGE          PORTS                    NAMES
768a3be05807   mysql:8.0      0.0.0.0:3306->3306/tcp   ardip-mysql
73e9ab683c64   nginx:alpine   0.0.0.0:8000->80/tcp     ardip-nginx
8d4c305c82e3   ardip-app      9000/tcp                 ardip-app
```

**Interpretación:**

- Los 3 contenedores están corriendo (`Up`)
- MySQL está accesible en puerto 3306
- Nginx está accesible en puerto 8000
- Laravel/PHP está en puerto interno 9000

---

### 6. Ejecutar Migraciones en MySQL

**Problema encontrado:**
Al acceder a `http://localhost:8000`, apareció error:

```text
SQLSTATE[42S02]: La tabla 'ardip_db.sessions' no existe
```

**Causa:**
Las tablas no existían en MySQL porque las migraciones se ejecutaron solo durante la instalación inicial en SQLite.

**Solución:**

```bash
docker exec -it ardip-app php artisan migrate
```

**¿Qué hace este comando?**

- `docker exec` → Ejecutar comando en un contenedor
- `-it` → Modo interactivo
- `ardip-app` → Nombre del contenedor donde ejecutar
- `php artisan migrate` → Comando de Laravel para crear tablas

**Resultado:**

```text
INFO  Preparing database.
Creating migration table ........................ DONE

INFO  Running migrations.
0001_01_01_000000_create_users_table ........... DONE
0001_01_01_000001_create_cache_table ........... DONE
0001_01_01_000002_create_jobs_table ............ DONE
```

**Tablas creadas en MySQL:**

- `migrations` (control de versiones de migraciones)
- `users` (usuarios del sistema)
- `cache` (sistema de caché)
- `jobs` (trabajos en cola)

---

## 🎉 Resultado Final

**URL del sistema:**

``` text
http://localhost:8000
```

**Página visible:**
✅ Página de bienvenida de Laravel funcionando correctamente

**Servicios activos:**

- ✅ Laravel corriendo en contenedor Docker
- ✅ MySQL 8.0 con base de datos `ardip_db`
- ✅ Nginx sirviendo la aplicación

---

## 🧠 Conceptos Clave Aprendidos

### 1. Docker - Contenedores vs Imágenes

- **Imagen:** Plantilla o "molde" (como una clase en POO)
- **Contenedor:** Instancia corriendo (como un objeto en POO)
- Analogía: Receta de torta (imagen) vs Torta cocinada (contenedor)

### 2. Docker Compose

- Orquesta múltiples contenedores
- Define cómo se comunican entre sí
- Simplifica el despliegue (un solo comando: `docker-compose up`)

### 3. Docker Networks

- Los contenedores se comunican por **nombres** (no IPs)
- Docker DNS traduce automáticamente: `db` → IP interna del contenedor
- Red aislada: `ardip-network`

### 4. Mapeo de Puertos

- Formato: `puerto_windows:puerto_contenedor`
- Ejemplo: `8000:80` → Puerto 8000 en Windows redirige al 80 del contenedor
- Permite acceder a servicios desde el host

### 5. Migraciones de Laravel

- Archivos PHP que crean/modifican tablas
- Control de versiones para la base de datos (como Git para código)
- Dos funciones clave:
  - `up()` → Crea/modifica estructuras
  - `down()` → Revierte cambios

### 6. Variables de Entorno (.env)

- Archivo con configuración sensible
- No se sube a GitHub (está en .gitignore)
- Cada entorno (desarrollo, producción) tiene su propio .env

---

## 📂 Estructura de Archivos Creados/Modificados

``` text
ARDIP/
├── .env                           (MODIFICADO - Config MySQL)
├── Dockerfile                     (NUEVO - Imagen de Laravel)
├── docker-compose.yml             (NUEVO - Orquestación)
├── docker/
│   └── nginx/
│       └── conf.d/
│           └── default.conf       (NUEVO - Config Nginx)
├── app/                           (Laravel)
├── database/
│   ├── migrations/                (Migraciones por defecto)
│   └── database.sqlite            (Ya no se usa)
├── public/                        (Punto de entrada web)
└── ... (otros archivos de Laravel)
```

---

## 🔧 Comandos Útiles para Recordar

### Ver contenedores corriendo

```bash
docker ps
```

### Ver todos los contenedores (incluso detenidos)

```bash
docker ps -a
```

### Ver logs de un contenedor

```bash
docker logs ardip-app
docker logs ardip-mysql
docker logs ardip-nginx
```

### Detener todos los contenedores

```bash
docker-compose down
```

### Levantar contenedores nuevamente

```bash
docker-compose up -d
```

### Ejecutar comando en un contenedor

```bash
docker exec -it ardip-app [comando]
```

Ejemplos:

```bash
# Ejecutar migraciones
docker exec -it ardip-app php artisan migrate

# Ver versión de Laravel
docker exec -it ardip-app php artisan --version

# Entrar al contenedor (bash)
docker exec -it ardip-app bash
```

### Reconstruir contenedores (si modificás Dockerfile)

```bash
docker-compose build
docker-compose up -d
```

---

## 🚀 Próximos Pasos - Sesión 2

Según la documentación de ARDIP, los siguientes pasos son:

### 1. Configurar Sistema de Autenticación

- Instalar Laravel Breeze o Jetstream
- Crear sistema de login/logout
- Configurar rutas protegidas

### 2. Crear Migraciones de ARDIP

Tablas pendientes según documentación:

- `brigadas` (brigadas policiales)
- `personas` (fichas de personas)
- `domicilios` (domicilios acumulativos)
- `procedimientos` (allanamientos, actuaciones)
- `participaciones` (relación persona-procedimiento)
- `auditoria` (registro de acciones)

### 3. Configurar Roles y Permisos

- Instalar `spatie/laravel-permission`
- Crear roles: ADMIN, ADMIN_BRIGADA, INVESTIGADOR, AUDITOR
- Definir permisos por rol

### 4. Crear Modelos Eloquent

- Modelo Persona
- Modelo Procedimiento
- Modelo Domicilio
- Etc.

---

## 📊 Tiempo Estimado de Desarrollo

**Sprint 1-2 (Base):** ✅ COMPLETADO

- Setup proyecto ✅
- Autenticación (pendiente)
- Dashboard básico (pendiente)

**Sprint 2-3 (CRUD Personas):**

- CRUD completo de personas
- Sistema de búsqueda
- Upload de fotos

**Sprint 3-4 (Procedimientos):**

- CRUD procedimientos
- Vincular personas a procedimientos
- Sistema de domicilios

**Sprint 4-5 (Sistema Avanzado):**

- Sistema de reportes
- Auditoría completa
- RBAC (Control de acceso basado en roles)

**Sprint 5-6 (Testing y Deploy):**

- Tests unitarios y de integración
- Optimizaciones
- Deploy en servidor del D-5

---

## 💡 Notas Importantes

### Diferencias SQLite vs MySQL

- **SQLite:** Archivo único, perfecto para desarrollo rápido
- **MySQL:** Servidor completo, mejor para producción y múltiples usuarios
- ARDIP usa MySQL porque será multi-usuario en el D-5

### ¿Por qué Docker?

- **Portabilidad:** El mismo entorno en tu PC y en el servidor del D-5
- **Aislamiento:** No interfiere con otros proyectos
- **Facilidad de deploy:** Solo copiar archivos y ejecutar `docker-compose up`

### Ventajas de usar Migraciones

- **Versionado:** Cada cambio en la BD queda registrado
- **Reversible:** Podés deshacer cambios con `rollback`
- **Colaboración:** Otros devs pueden replicar la BD exacta
- **Documentación:** El código es la documentación

---

## 🎓 Recursos para Profundizar

**Documentación oficial:**

- Laravel: <https://laravel.com/docs>
- Docker: <https://docs.docker.com>
- Docker Compose: <https://docs.docker.com/compose>

**Comandos Artisan útiles:**

```bash
php artisan list              # Ver todos los comandos
php artisan make:migration    # Crear migración
php artisan make:model        # Crear modelo
php artisan make:controller   # Crear controlador
php artisan migrate:status    # Ver estado de migraciones
php artisan tinker            # REPL interactivo de Laravel
```

---

## ✅ Checklist de Verificación

Antes de la próxima sesión, verificá que:

- [ ] Docker Desktop está corriendo
- [ ] Los 3 contenedores están activos (`docker ps`)
- [ ] Podés acceder a <http://localhost:8000>
- [ ] VS Code tiene el proyecto abierto
- [ ] Entendés la diferencia entre imagen y contenedor
- [ ] Sabés usar la terminal integrada de VS Code

---

## 📝 Conclusión

**Logros de la Sesión 1:**

- ✅ Entorno completo configurado (Docker + Laravel + MySQL)
- ✅ Sistema funcionando localmente
- ✅ Base de datos creada con migraciones iniciales
- ✅ Comprensión de conceptos clave (Docker, migraciones, etc.)

**Tiempo total invertido:** ~3 horas (incluye instalación, configuración y aprendizaje)

**Estado del proyecto:**

- Base técnica: 100% ✅
- Funcionalidades ARDIP: 0% (próximas sesiones)

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1 - Tecnicatura Superior en Desarrollo de Software  
**Fecha:** 15 de Octubre, 2025
