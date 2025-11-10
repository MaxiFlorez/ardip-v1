# ARDIP V1 - Documentación Sesión 7

## Vinculaciones (Personas/Domicilios) y CRUD Domicilios

**Fecha:** 9 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** ~2.5 horas  
**Estado:** ✅ Fase 1 (Funcionalidad) COMPLETADA

---

## 📋 Resumen Ejecutivo

En esta séptima sesión, se completó la funcionalidad principal del sistema: la **vinculación de datos**. Se implementó la lógica para conectar Personas y Domicilios a los Procedimientos. Además, se desarrolló el CRUD completo de Domicilios (Prioridad 2), dejando el backend y los módulos base 100% funcionales.

**Estado Final:** El sistema ahora permite gestionar los 3 módulos (Personas, Procedimientos, Domicilios) y crear las relaciones entre ellos. El proyecto está listo para la Fase 2 (Mejoras de Flujo de Trabajo y UX).

---

## 🎯 Parte 1: Vinculación de Personas a Procedimientos

Comenzamos implementando la funcionalidad para agregar personas a un procedimiento existente desde la vista de detalle (`show.blade.php`).

### 1.1. Actualización del Controlador (`ProcedimientoController`)

Se importó el modelo `App\Models\Persona`.

Se modificó el método `show()` para que, además de cargar el procedimiento, busque y envíe la lista de `$personasDisponibles` a la vista.

### 1.2. Actualización de la Vista (`show.blade.php`)

Se añadió un formulario (`<form>`) en la sección "Personas Involucradas".

Este formulario incluye un desplegable (`<select>`) que lista todas las `$personasDisponibles`.

Se añadió un desplegable para la `situacion_procesal` (Detenido, Notificado, etc.), basado en la migración de la `SESION_4.md`.

### 1.3. Creación del Backend (Ruta y Método)

* **Ruta:** Se añadió la ruta personalizada `POST` en `routes/web.php` para manejar la lógica:

    ```php
    Route::post('/procedimientos/{procedimiento}/vincular-persona', [ProcedimientoController::class, 'vincularPersona'])
            ->name('procedimientos.vincularPersona');
    ```

* **Método:** Se implementó el método `vincularPersona()` en `ProcedimientoController.php`.

    **Lógica Clave:**
    1. Valida los datos (`persona_id`, `situacion_procesal`).
    2. Verifica que la persona no esté ya vinculada (prevención de duplicados).
    3. Usa el método `attach()` de Eloquent para guardar la relación en la tabla pivote `procedimiento_personas`, incluyendo los datos extra (`situacion_procesal`, `observaciones`).
    4. Redirige de vuelta a la vista `show` con un mensaje de éxito.

**Resultado:** Confirmaste que la vinculación de personas funcionó perfectamente ("SI ANDA PERFECTO").

---

## 🎯 Parte 2: Vinculación de Domicilios a Procedimientos

Siguiendo el mismo patrón, implementamos la vinculación de domicilios.

### 2.1. Actualización del Controlador (`ProcedimientoController`)

Se importó el modelo `App\Models\Domicilio`.

Se actualizó el método `show()` para que también envíe la lista de `$domiciliosDisponibles`.

### 2.2. Actualización de la Vista (`show.blade.php`)

Se añadió un segundo formulario (`<form>`) en la sección "Domicilios Allanados".

Este formulario incluye un desplegable (`<select>`) con todos los `$domiciliosDisponibles`.

**Depuración:** Corregimos un error de HTML donde este formulario quedó anidado dentro de la lista `<ul>` de la sección.

### 2.3. Creación del Backend (Ruta y Método)

* **Ruta:** Se añadió la ruta personalizada `POST` en `routes/web.php`:

    ```php
    Route::post('/procedimientos/{procedimiento}/vincular-domicilio', [ProcedimientoController::class, 'vincularDomicilio'])
            ->name('procedimientos.vincularDomicilio');
    ```

* **Método:** Se implementó el método `vincularDomicilio()` en `ProcedimientoController.php`.

    **Lógica Clave:** Similar a Personas, usa `attach()` para guardar la relación en la tabla pivote `procedimiento_domicilios`.

**Resultado:** Confirmaste que la vinculación de domicilios también funcionó ("SI SI FUNCIONNA").

---

## 🎯 Parte 3: CRUD Domicilios (Prioridad 2)

Una vez completadas las vinculaciones, pasamos a tu Prioridad 2: el CRUD de Domicilios, ya que necesitábamos una forma de gestionarlos.

### 3.1. Backend (Controlador y Rutas)

Se creó el `DomicilioController` usando `php artisan make:controller DomicilioController --resource`.

Se registró la ruta `Route::resource('domicilios', DomicilioController::class);` en `routes/web.php`.

### 3.2. Vistas (Index, Create, Store)

Se implementó el método `index()` en el controlador.

Se creó la vista `domicilios/index.blade.php` (el listado).

Se implementaron los métodos `create()` y `store()` (con las 14 validaciones de campos flexibles de la `SESION_3.md`).

Se creó la vista `domicilios/create.blade.php` (el formulario de carga).

**Resultado:** Confirmaste que el listado y la creación funcionaron ("si funciona").

### 3.3. Vistas (Show, Edit, Update, Destroy)

Se implementaron todos los métodos restantes (`show`, `edit`, `update`, `destroy`) en `DomicilioController.php`.

Se crearon las vistas `domicilios/show.blade.php` y `domicilios/edit.blade.php`.

* **Depuración (Paso Clave 1):** Al probar los botones, la URL redirigía a `.../domicilios/#`.
  * **Solución:** Actualizamos los enlaces `href` en `domicilios/index.blade.php` para que apuntaran a las rutas correctas (`domicilios.show`, `domicilios.edit`, etc.).

* **Depuración (Paso Clave 2):** Al ver un domicilio, apareció el error `Call to undefined relationship [procedimientos]`.
  * **Causa:** El método `show()` llamaba a `$domicilio->load('procedimientos')`, pero la relación no estaba definida en el modelo.
  * **Solución:** Agregamos la relación `public function procedimientos()` al modelo `app/Models/Domicilio.php`.

* **Depuración (Paso Clave 3):** Al arreglar el modelo, apareció el error `FatalError: Cannot redeclare class App\Domicilio`.
  * **Causa:** Mi código de ejemplo usó el namespace incorrecto (`namespace App;`).
  * **Solución:** Corregimos el namespace a `namespace App\Models;` en `app/Models/Domicilio.php`.

### 3.4. Refactorización (Mejora de Código)

* **Análisis:** Revisamos las sugerencias de Copilot para mejorar la vista `domicilios/index.blade.php`.
* **Acción:** Implementamos las mejoras:
  * Se añadió el mensaje de éxito (`session('success')`).
  * Se mejoró la lógica de visualización de la dirección (usando `@php` y `trim()`).
  * Se mejoró la lógica de Barrio/Monoblock (usando `@if/@elseif`).

---

## 📊 Estado Actual del Proyecto

¡Felicidades! Con esta sesión, la **Fase 1 (Funcionalidad)** está **100% COMPLETA**.

* ✅ CRUD Personas (Funcional).
* ✅ CRUD Procedimientos (Funcional).
* ✅ CRUD Domicilios (Funcional y Refactorizado).
* ✅ Vinculaciones (Funcionales).
