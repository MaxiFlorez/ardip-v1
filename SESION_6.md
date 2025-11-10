# ARDIP V1 - Documentación Sesión 6

## CRUD Personas (Final) y CRUD Procedimientos (Completo)

**Fecha:** 8 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** ~4 horas  
**Estado:** ✅ CRUD Procedimientos 100% Funcional

---

## 📋 Resumen Ejecutivo

En esta sexta sesión, se alcanzaron dos hitos cruciales. Primero, se finalizó por completo el módulo de **Personas**, corrigiendo el último bug pendiente en la funcionalidad de edición. Segundo, y como prioridad principal, se construyó desde cero el **CRUD completo de Procedimientos**, el núcleo del sistema. Este proceso incluyó la creación del controlador, las rutas, todas las vistas (index, create, show, edit) y la depuración de varios errores críticos relacionados con la carga de datos, la lógica del controlador y los estilos de las vistas.

**Estado Final:** El sistema ahora permite gestionar Personas y Procedimientos de manera integral (Listar, Crear, Ver, Editar y Eliminar), dejando la base funcional del proyecto sólidamente establecida.

---

## 🛠️ Parte 1: Finalización del CRUD Personas

Antes de comenzar con el nuevo módulo, era fundamental dejar el CRUD de Personas 100% operativo. El único pendiente era un bug en la vista de edición.

**Problema Identificado:** La vista `edit.blade.php` para editar una persona no funcionaba. Al hacer clic en "Editar" desde el listado, la página no cargaba los datos del usuario seleccionado o el formulario no enviaba la información correctamente.

**Solución Implementada:** Se revisó y corrigió el archivo `resources/views/personas/edit.blade.php`, aplicando tres cambios clave que son estándar en los formularios de edición de Laravel:

1. **Acción del Formulario:** Se aseguró que la acción del formulario apuntara a la ruta de actualización correcta, pasando el objeto `$persona`:

    ```html
    <form action="{{ route('personas.update', $persona) }}" method="POST">
    ```

2. **Método HTTP Spoofing:** Se añadió la directiva `@method('PUT')` dentro del formulario. Esto es crucial porque los formularios HTML solo soportan `GET` y `POST`. Laravel utiliza esta directiva para enrutar la petición al método `update` del controlador, que espera una petición `PUT` o `PATCH`.
3. **Precarga de Datos:** Se modificaron todos los campos del formulario para que mostraran los datos existentes de la persona que se está editando. Se utilizó la función `old()` de Laravel como fallback, para mantener los datos ingresados por el usuario si la validación falla.

    ```html
    <input type="text" name="dni" value="{{ old('dni', $persona->dni) }}">
    ```

**Resultado:** Con estas correcciones, el CRUD de Personas quedó 100% funcional.

---

## 🚔 Parte 2: Backend del CRUD Procedimientos (Prioridad 1)

Una vez solucionado lo anterior, nos enfocamos en la Prioridad 1: el módulo de Procedimientos.

### 2.1. Creación de Componentes

Se intentó crear el modelo y el controlador con el comando:
`php artisan make:model Procedimiento -cr`

El sistema arrojó una advertencia: `Model already exists.`. Esto era correcto, ya que el modelo `Procedimiento.php` se había creado en la Sesión 3. Se confirmó con `yes` para sobrescribir y crear únicamente el `ProcedimientoController.php` con los métodos de un recurso.

### 2.2. Configuración de Rutas

Se editó el archivo `routes/web.php` para registrar las nuevas rutas del CRUD.

1. **Importación del Controlador:** Se añadió la línea `use App\Http\Controllers\ProcedimientoController;`.
2. **Ruta de Recurso:** Se añadió la ruta `Route::resource('procedimientos', ProcedimientoController::class);` dentro del `Route::middleware('auth')->group(...)` para asegurar que solo usuarios autenticados pudieran acceder.

Para confirmar la correcta creación de las 7 rutas RESTful (index, create, store, show, edit, update, destroy), se utilizó el comando `php artisan route:list | findstr procedimientos` en la terminal de Windows, verificando la salida.

### 2.3. Verificación del Modelo

Se revisó el archivo `app/Models/Procedimiento.php` para confirmar que la estructura creada en sesiones anteriores era correcta. Se verificó la presencia de:

* `protected $guarded = ['id'];` para la asignación masiva.
* Los `$casts` para formatear fechas y campos booleanos (`orden_secuestro`, `orden_detencion`).
* Las tres relaciones Eloquent clave: `brigada()`, `personas()` y `domicilios()`.

---

## 🖥️ Parte 3: Frontend del CRUD Procedimientos (Vistas y Lógica)

Se procedió a construir la interfaz de usuario, implementando y depurando cada método del `ProcedimientoController`.

### 3.1. Listar Procedimientos (Index)

* **Controlador (`index()`):** Se implementó la lógica para obtener los procedimientos de la base de datos. Se utilizó `Procedimiento::with('brigada')->...` para aplicar **Eager Loading**, una optimización clave que pre-carga la relación con `brigada` y evita el problema N+1 (una consulta por cada procedimiento en el bucle).
* **Vista (`index.blade.php`):** Se creó la vista con una tabla para mostrar los datos y los botones de acción (Ver, Editar, Eliminar).
* **Resultado:** Éxito. La vista se renderizó correctamente, mostrando una tabla vacía con el mensaje "No hay procedimientos registrados".

### 3.2. Crear Procedimiento (Create / Store)

* **Controlador (`create()` y `store()`):**
  * En `create()`, se añadió la lógica para obtener todas las brigadas (`Brigada::orderBy('nombre')->get()`) y pasarlas a la vista para poblar el menú desplegable.
  * En `store()`, se implementó la validación de los datos del request y la lógica para guardar el nuevo procedimiento, incluyendo la asignación del `usuario_id` (`Auth::id()`) y el manejo de los checkboxes para las órdenes.
* **Vista (`create.blade.php`):** Se creó el formulario de carga.

* **Depuración (Paso Clave):**
  * **Error 1: `Class "Brigada" not found`**.
    * **Causa:** El controlador intentaba usar el modelo `Brigada` y el facade `Auth` sin haberlos importado.
    * **Solución:** Se añadieron las sentencias `use App\Models\Brigada;` y `use Illuminate\Support\Facades\Auth;` al inicio de `ProcedimientoController.php`.
  * **Error 2: El menú desplegable de Brigadas aparecía vacío.**
    * **Diagnóstico:** Se utilizó `php artisan tinker` para ejecutar `App\Models\Brigada::count()` y se confirmó que devolvía `0`. La tabla estaba vacía.
    * **Causa:** Aunque la migración existía, nunca se habían poblado los datos iniciales en este nuevo entorno de Herd.
    * **Solución:** Se ejecutó el seeder específico: `php artisan db:seed --class=BrigadaSeeder`.
* **Resultado:** Tras las correcciones, el formulario cargó las 8 brigadas correctamente y se pudo guardar el primer procedimiento en la base de datos.

### 3.3. Ver Detalle (Show)

* **Controlador (`show()`):** Se implementó el método usando `load('brigada', 'personas', 'domicilios')` para cargar eficientemente todas las relaciones del procedimiento en una sola consulta.
* **Vista (`show.blade.php`):** Se creó la vista para mostrar los datos generales del procedimiento.
* **Resultado:** Éxito. La vista de detalle funcionó a la primera.

### 3.4. Editar Procedimiento (Edit / Update)

* **Controlador (`edit()` y `update()`):** Se implementaron ambos métodos, siguiendo la misma lógica que en `create/store` pero adaptada para la actualización de un registro existente.
* **Vista (`edit.blade.php`):** Se creó la vista copiando el contenido de `create.blade.php` y se adaptó para la edición (usando `@method('PUT')` y precargando datos).

* **Depuración (Paso Clave):**
  * **Error 1: CSS Roto (Modo Quirks).** La vista de edición se mostraba sin ningún estilo, como HTML plano.
    * **Diagnóstico:** La consola de desarrollador del navegador (F12) indicaba que la página se estaba renderizando en "Quirks Mode".
    * **Causa:** Se había olvidado envolver el contenido del archivo `edit.blade.php` con el componente de layout de la aplicación.
    * **Solución:** Se añadieron las etiquetas `<x-app-layout>` al inicio y `</x-app-layout>` al final del archivo.
  * **Error 2: `ParseError`.**
    * **Causa:** Un simple error de tipeo en una directiva de Blade: `@endSerror` en lugar de `@enderror`.
    * **Solución:** Se corrigió la directiva.
* **Resultado:** El formulario de edición cargó exitosamente, con los estilos correctos y los datos del procedimiento precargados.

### 3.5. Eliminar Procedimiento (Destroy)

* **Controlador (`destroy()`):** Se implementó el método `destroy()`, que simplemente elimina el registro y redirige al listado.
* **Vistas (`index.blade.php` y `show.blade.php`):** Se modificaron ambas vistas. Los enlaces de "Eliminar" se convirtieron en pequeños formularios que envían una petición `POST` con el método `DELETE` (`@method('DELETE')`). Se añadió una confirmación de JavaScript (`onsubmit="return confirm(...)"`) para prevenir eliminaciones accidentales.
* **Resultado:** La funcionalidad de eliminación quedó implementada de forma segura y funcional.

---

## 📊 Estado Actual del Proyecto

* **CRUD Personas:** 100% Completo y funcional.
* **CRUD Procedimientos:** 100% Completo y funcional.
* **Infraestructura:** El entorno de desarrollo con Laravel Herd y MySQL local se ha demostrado estable y de alto rendimiento.

El proyecto ha avanzado significativamente, con sus dos módulos más importantes ya operativos. La base está lista para la siguiente fase.

---

## 🚀 Próximos Pasos (Sesión 7)

1. **CRUD Domicilios:** Implementar el CRUD completo para la gestión de domicilios.
2. **Funcionalidad de Vinculación:** Desarrollar la lógica en la vista `procedimientos.show` para poder vincular Personas y Domicilios existentes a un Procedimiento.
