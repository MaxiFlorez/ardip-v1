<<<<<<< HEAD
# ARDIP V1 - Documentación Sesión 5

## Sistema de Autenticación y CRUD Personas Completo

**Fecha:** 5 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** 4 horas  
**Estado:** ✅ COMPLETADO
=======
# ARDIP V1 - Documentación Sesión 5 (ACTUALIZADA)

## Sistema de Autenticación, CRUD Personas y Migración a Laravel Herd

**Fecha:** 5-8 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración Total:** 10 horas (divididas en 3 días)  
**Estado:** ✅ COMPLETADO CON MIGRACIÓN A HERD
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1

---

## 📋 Resumen Ejecutivo

<<<<<<< HEAD
En esta quinta sesión se implementó el sistema completo de autenticación con Laravel Breeze y se desarrolló el CRUD completo de Personas, alcanzando la primera funcionalidad principal del sistema ARDIP. El proyecto fue respaldado en GitHub para trabajo colaborativo y continuidad desde diferentes equipos.

**Estado Final:** Sistema con login funcional + CRUD Personas 100% operativo ✅
=======
Esta sesión fue crítica y se extendió más de lo planeado debido a problemas de rendimiento con Docker en Windows. Se implementó el sistema completo de autenticación con Laravel Breeze, se desarrolló el CRUD completo de Personas, y finalmente se migró todo el entorno de desarrollo de Docker a Laravel Herd para resolver los problemas de lentitud (de 10-15 segundos a <1 segundo por página).

**Estado Final:**

- ✅ Sistema con login funcional
- ✅ CRUD Personas 100% operativo
- ✅ Migración exitosa de Docker → Laravel Herd
- ✅ Rendimiento optimizado 10x
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1

---

## 🎯 Objetivos Cumplidos

<<<<<<< HEAD
### Autenticación y Seguridad
=======
### Fase 1: Autenticación y Seguridad (5 Nov)
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1

- [x] Laravel Breeze instalado y configurado
- [x] Sistema de login/logout funcionando
- [x] Registro de usuarios implementado
- [x] Rutas protegidas con middleware auth
- [x] Dashboard de bienvenida

<<<<<<< HEAD
### CRUD Personas - Backend
=======
### Fase 2: CRUD Personas (5-6 Nov)
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1

- [x] Rutas resource configuradas
- [x] PersonaController con 7 métodos completos
- [x] Validaciones de formularios
- [x] Sistema de upload de fotos
<<<<<<< HEAD
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
=======
- [x] 4 vistas completas (index, create, show, edit)

### Fase 3: Problemas y Migración (7-8 Nov)

- [x] Identificación del problema de rendimiento Docker/WSL2
- [x] Decisión de migrar a Laravel Herd
- [x] Migración completa del proyecto
- [x] Resolución de conflictos de puerto MySQL
- [x] Optimización de rendimiento lograda

---

## 📊 Cronología de Eventos

### Día 1 (5 Nov): Instalación Breeze + Inicio CRUD

- Instalación de Laravel Breeze
- Configuración de autenticación
- Creación de PersonaController
- Desarrollo de vistas index, create, show

### Día 2 (6 Nov): Completar CRUD + Problemas de rendimiento

- Vista edit.blade.php (con problemas)
- Identificación de lentitud extrema (10-15 seg/página)
- Intentos de optimización con Docker (volúmenes nombrados)
- Mejora parcial (30-40% más rápido, pero insuficiente)

### Día 3 (7-8 Nov): Migración a Herd

- Decisión de abandonar Docker para desarrollo
- Instalación de Laravel Herd
- Migración del proyecto
- Resolución de conflictos y configuración final
- CRUD Personas 100% funcional

---

## 🚀 Parte 1: Laravel Breeze (Completado en Docker)

### Instalación y Configuración

```bash
# Instalación desde Windows (Docker bloqueaba packagist)
composer require laravel/breeze --dev
php artisan breeze:install
```

**Stack seleccionado:** Blade (más simple para V1)

### Problema con Node.js en Docker

Se tuvo que modificar el Dockerfile para incluir Node.js:

```dockerfile
# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
```

### Compilación de Assets

```bash
docker exec -it ardip-app npm install
docker exec -it ardip-app npm run build
```

**Resultado:** Sistema de autenticación funcionando ✅

---

## 🔧 Parte 2: CRUD Personas - Desarrollo

### PersonaController

**Métodos implementados:**

1. `index()` - Listado de personas con cálculo de edad
2. `create()` - Formulario de alta
3. `store()` - Guardar nueva persona con validaciones
4. `show()` - Ver detalle completo
5. `edit()` - Formulario de edición
6. `update()` - Actualizar datos
7. `destroy()` - Eliminar persona

### Validaciones Implementadas

```php
$validated = $request->validate([
    'dni' => 'required|string|size:8|unique:personas,dni',
    'nombres' => 'required|string|max:100',
    'apellidos' => 'required|string|max:100',
    'fecha_nacimiento' => 'required|date|before:today',
    'genero' => 'required|in:masculino,femenino,otro',
    'alias' => 'nullable|string|max:100',
    'nacionalidad' => 'nullable|string|max:50',
    'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
    'foto' => 'nullable|image|max:2048',
    'observaciones' => 'nullable|string',
]);
```

### Sistema de Upload de Fotos

```php
if ($request->hasFile('foto')) {
    $path = $request->file('foto')->store('fotos_personas', 'public');
    $validated['foto'] = $path;
}
```

---

## 🐌 Parte 3: Problema de Rendimiento Identificado

### Síntomas

- Carga de páginas: 10-15 segundos
- Desarrollo imposible de mantener
- Frustración creciente

### Causa Raíz

```
Windows → WSL2 → Docker → Laravel
         ↑
    Cuello de botella
```

La sincronización de archivos entre Windows y WSL2 es extremadamente lenta, especialmente con las carpetas `vendor/` (40MB) y `node_modules/` (200MB).

### Intentos de Solución en Docker

1. **Volúmenes nombrados** - Mejora del 30-40% (insuficiente)
2. **Caché optimizada** - Mejora mínima
3. **Conclusión:** Docker no es viable para desarrollo Laravel en Windows

---

## 🚀 Parte 4: Migración a Laravel Herd

### ¿Por qué Herd?

- Diseñado específicamente para Laravel
- Nativo en Windows (sin WSL2)
- Incluye PHP, Nginx, MySQL preconfigurados
- Rendimiento 10x superior a Docker en Windows

### Proceso de Migración

#### Paso 1: Limpiar proyecto en Git

```bash
# Guardar documentación
git add Desktop/ARDIP/SESION_5.md
git commit -m "feat: Avance Sesion 5 - CRUD Personas (Falta fix de vista edit)"

# Limpiar archivos basura
git reset --hard HEAD
git clean -fd

# Agregar vista edit faltante
notepad resources\views\personas\edit.blade.php
git add resources/views/personas/edit.blade.php
git commit -m "fix: Agregar vista edit.blade.php completa"
git push
```

#### Paso 2: Instalar Herd

1. Descargar desde <https://herd.laravel.com/windows>
2. Instalar con configuración por defecto
3. Se instala en `C:\Users\javie\Herd`

#### Paso 3: Clonar proyecto limpio

```bash
cd C:\Users\javie\Herd
git clone https://github.com/MaxiFlorez/ardip-v1.git ardip
```

#### Paso 4: Configurar proyecto

```bash
cd ardip
composer install
copy .env.example .env
php artisan key:generate
php artisan storage:link
```

#### Paso 5: Instalar Node.js para Windows

- Descarga desde <https://nodejs.org/>
- Instalación estándar con "Add to PATH"

#### Paso 6: Compilar assets

```bash
npm install
npm run build
```

#### Paso 7: Configurar base de datos

**Problema encontrado:** Conflicto de puerto 3306

**Solución:** Usar el MySQL existente de XAMPP

```env
# .env configuración final
APP_URL=http://ardip.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ardip_db
DB_USERNAME=root
DB_PASSWORD=
```

#### Paso 8: Ejecutar migraciones

```bash
# Limpiar caché de configuración
php artisan config:clear

# Crear base de datos en phpMyAdmin
# http://localhost/phpmyadmin → Nueva BD: ardip_db

# Ejecutar migraciones
php artisan migrate
php artisan db:seed
```

---

## ✅ Parte 5: Verificación y Pruebas

### CRUD Personas - Funcionalidades Verificadas

| Función | Estado | Observaciones |
|---------|--------|---------------|
| Listar personas | ✅ | Calcula edad automáticamente |
| Crear persona | ✅ | Validaciones funcionando |
| Ver detalle | ✅ | Muestra foto y todos los datos |
| Editar persona | ✅ | Precarga datos correctamente |
| Eliminar persona | ✅ | Confirmación y eliminación exitosa |
| Upload de fotos | ✅ | Almacena en storage/app/public |

### Rendimiento Comparativo

| Métrica | Docker | Herd | Mejora |
|---------|--------|------|--------|
| Carga página inicial | 10-15 seg | 0.5-1 seg | 95% |
| Guardar formulario | 8-10 seg | 0.3-0.5 seg | 95% |
| Compilar assets | 45 seg | 8 seg | 82% |
| Experiencia desarrollo | Frustrante | Fluida | 💯 |

---

## 📊 Estado del Proyecto Post-Sesión 5

### Backend (85% completado)

```
✅ Autenticación (Breeze)
✅ Migraciones completas
✅ Modelos con relaciones
✅ CRUD Personas funcional
⏳ CRUD Procedimientos
⏳ CRUD Domicilios
```

### Frontend (30% completado)

```
✅ Login/Logout
✅ Vistas Personas (4/4)
⏳ Vistas Procedimientos
⏳ Vistas Domicilios
⏳ Dashboard
```

### Infraestructura (100% completado)

```
✅ Entorno de desarrollo (Herd)
✅ Base de datos (MySQL)
✅ Control de versiones (Git)
✅ Repositorio (GitHub)
✅ Assets compilados (Vite)
```

---

## 🎓 Lecciones Aprendidas

### 1. Sobre Docker en Windows

- Docker + WSL2 + Windows = Problemas de rendimiento
- Para desarrollo Laravel en Windows: usar herramientas nativas
- Docker sigue siendo excelente para producción/Linux

### 2. Sobre la Toma de Decisiones

- No aferrarse a una tecnología si no funciona
- Pragmatismo > Purismo tecnológico
- El mejor stack es el que te permite ser productivo

### 3. Sobre Laravel Herd

- Instalación en minutos vs horas con Docker
- Configuración automática para Laravel
- URLs bonitas automáticas (ardip.test)
- Rendimiento nativo sin capas de virtualización

### 4. Sobre el Debugging

- Los conflictos de puerto son comunes
- `php artisan config:clear` resuelve muchos problemas
- phpMyAdmin es útil para verificación rápida

---

## 🛠 Configuración Final de Desarrollo

### Stack Actual

- **PHP:** Via Herd (múltiples versiones disponibles)
- **Servidor Web:** Nginx (incluido en Herd)
- **Base de Datos:** MySQL de XAMPP (puerto 3306)
- **Node/NPM:** v20.18.0 (instalado en Windows)
- **Control de Versiones:** Git + GitHub

### URLs del Proyecto

- **Aplicación:** <http://ardip.test>
- **phpMyAdmin:** <http://localhost/phpmyadmin>
- **GitHub:** <https://github.com/MaxiFlorez/ardip-v1>

### Comandos Frecuentes

```bash
# Desarrollo
php artisan serve  # Ya no necesario con Herd
npm run dev       # Para desarrollo con hot-reload
npm run build     # Para producción

# Base de datos
php artisan migrate:fresh --seed  # Resetear BD

# Caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📝 Archivos Clave Modificados/Creados

```
ardip/
├── app/Http/Controllers/
│   └── PersonaController.php ✨ (CRUD completo)
├── resources/views/personas/
│   ├── index.blade.php ✨
│   ├── create.blade.php ✨
│   ├── show.blade.php ✨
│   └── edit.blade.php ✨ (corregido el 8/Nov)
├── routes/web.php ✏️ (rutas de personas)
├── .env ✏️ (configurado para Herd)
└── storage/app/public/fotos_personas/ 📁 (uploads)
```

---

## ✅ Checklist de Verificación Final

- [x] Herd instalado y funcionando
- [x] Proyecto clonado en C:\Users\javie\Herd\ardip
- [x] Base de datos ardip_db creada
- [x] Migraciones ejecutadas
- [x] Seeders ejecutados (brigadas)
- [x] Login funciona
- [x] CRUD Personas completo
- [x] Upload de fotos funciona
- [x] GitHub actualizado
- [x] Rendimiento óptimo (<1 seg/página)

---

## 🎯 Preparación para Sesión 6

### Tareas Inmediatas

1. **CRUD Procedimientos** (más complejo, 4-5 horas)
   - Formulario multi-step
   - Vinculación con personas
   - Vinculación con domicilios
   - Registro de resultados

2. **CRUD Domicilios** (simple, 1-2 horas)
   - Formulario básico
   - Listado y búsqueda

3. **Dashboard** (2-3 horas)
   - Estadísticas
   - Últimos procedimientos
   - Accesos rápidos

### Tiempo Restante

- **Fecha actual:** 8 de Noviembre
- **Fecha demo:** 14 de Noviembre
- **Días disponibles:** 6 días
- **Confianza:** ALTA ✅

---

## 💬 Notas del Estudiante

"La migración a Herd fue la mejor decisión. Perdí tiempo inicial con Docker pero aprendí mucho sobre debugging y resolución de problemas. Ahora el desarrollo es fluido y puedo enfocarme en las funcionalidades en lugar de pelear con el entorno."

---

## 🏆 Conclusión

A pesar de los desafíos técnicos, esta sesión fue extremadamente productiva:

1. ✅ Sistema de autenticación implementado
2. ✅ CRUD Personas 100% funcional
3. ✅ Problemas de rendimiento resueltos definitivamente
4. ✅ Entorno de desarrollo optimizado
5. ✅ Proyecto bien organizado en Git

El proyecto ARDIP está en excelente estado para completarse antes del 14 de Noviembre. La experiencia ganada con la resolución de problemas será valiosa para el futuro desarrollo.

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1 - Tecnicatura Superior en Desarrollo de Software  
**Fecha:** 5-8 de Noviembre, 2025  
**Sesión:** 5 de N (Extendida y Completada)

---

## FIN DEL DOCUMENTO - SESIÓN 5 ACTUALIZADA
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1
