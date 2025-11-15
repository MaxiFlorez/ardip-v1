# SESIÓN 9 — Unificación Final y Eliminación de Normalización de Barrios (15/11/2025)

## Resumen Ejecutivo

En esta sesión completamos la consolidación del flujo de carga (Actuación + Persona + Domicilios) mediante el asistente de 3 pestañas, y ejecutamos la refactorización final: se abandonó la normalización de barrios (tablas, modelo, seeder, Livewire) y se volvió al campo texto simple `barrio` en `domicilios` y en el domicilio legal embebido del formulario. Se eliminaron migraciones obsoletas, se ajustaron controladores, vistas y validaciones, y se verificó que el sistema quede consistente solo con Provincias y Departamentos normalizados.

## Flujo de Carga Unificado (Decisión de Arquitectura)

- Se descartan los 3 CRUDs separados como origen principal de datos y se centraliza el alta en el Asistente Unificado:
  - Controlador: `CargaCompletaController` (métodos `create` y `store`).
  - Rutas: `carga.create` (`GET /carga/nueva`) y `carga.store` (`POST /carga`).
- Beneficios: menos pasos para el usuario, coherencia de validaciones y mutadores, y menores inconsistencias entre entidades.

Código de rutas (extracto):

```php
// routes/web.php (dentro del grupo auth)
Route::get('/carga/nueva', [CargaCompletaController::class, 'create'])->name('carga.create');
Route::post('/carga', [CargaCompletaController::class, 'store'])->name('carga.store');
```

## Objetivos Logrados

- Asistente único de carga funcionando (Procedimiento + Persona + Domicilios allanados + Domicilio legal).
- Persistencia de `hora_procedimiento` y `usuario_id` en `procedimientos`.
- Domicilio Legal vinculado por FK (`domicilio_id`) en `personas`.
- Eliminación completa de barrio normalizado (tablas, modelo, seeder, componente Livewire) → reemplazado por `barrio` texto.
- Limpieza de eager loads y relaciones inexistentes (`domicilios.barrio` removido).
- Formularios y listados muestran `barrio` como texto plano.

## Limpieza de Rutas (REST sólo para lectura/edición)

- Para `Personas`, `Procedimientos` y `Domicilios` se mantienen los recursos, pero se excluyen `create` y `store` (el alta se hace en el asistente):

```php
// routes/web.php
Route::resource('personas', PersonaController::class)->except(['create','store']);
Route::resource('procedimientos', ProcedimientoController::class)->except(['create','store']);
Route::resource('domicilios', DomicilioController::class)->except(['create','store']);
```

- El dashboard se dejó sólo con `auth` (sin `verified`) para simplificar entornos de prueba internos.

```php
Route::get('/dashboard', fn() => view('dashboard'))
   ->middleware(['auth'])
   ->name('dashboard');
```

## Refactor Barrios (Rollback & Limpieza)

Realizado:

1. Rollback y eliminación de migraciones obsoletas de barrios:
   - `2025_11_11_210843_modify_domicilios_for_barrios_table.php` (NO-OP) → borrada.
   - `2025_11_12_120001_drop_barrios_and_barrioid_from_domicilios.php` → borrada.
2. Modelo `Barrio.php`, seeder `BarrioSeeder.php`, JSON `barrios_sanjuan.json`, componente Livewire `BuscarBarrio` y vista asociados: inexistentes / eliminados.
3. Relación `barrio()` removida del modelo `Domicilio` (no aparece en el archivo actual).
4. Controladores actualizados para no cargar ni validar `barrio_id`.
5. Vistas reemplazadas: inputs de texto simples para barrio.

## Migraciones Vigentes Relevantes

- `add_domicilio_id_to_personas_table` (FK opcional, domicilio legal).
- `add_hora_procedimiento_to_procedimientos_table` (hora opcional de la actuación).
- Las migraciones de barrios ya no forman parte del historial activo.

## Modelos

`app/Models/Domicilio.php`:

- Mantiene relaciones: `provincia()` y `departamento()`.
- Accessor `direccion_completa` integra: calle + número, barrio texto (“B° …”), monoblock, sector, manzana/lote/casa, torre, piso/depto.
- Mutadores aplican normalización (mayúsculas / trim) a la mayoría de campos, incluida la asignación simple de `barrio`.

`app/Models/Persona.php` (no modificado en esta sesión):

- Usa `domicilio_id` como FK al domicilio legal.

## Controladores Ajustados

`CargaCompletaController@store`:

- Validación de domicilio legal incluye: `domicilio_legal.barrio` (nullable, string, max:100).
- Crea `Domicilio` legal con `'barrio' => $dl['barrio'] ?? null`.
- Procedimiento se crea con `usuario_id` y defaults funcionales.
- Domicilios allanados se iteran con tolerancia (solo crea si hay calle); coordenadas opcionales concatenadas.

`ProcedimientoController@show/edit`:

- Eager load reducido: `brigada`, `personas`, `domicilios.provincia`, `domicilios.departamento` (sin `domicilios.barrio`).

`ProcedimientoController@update`:

- Validación de domicilio legal ahora incluye campo `domicilio_legal.barrio`.
- Crea nuevo domicilio legal textual y reasigna a persona si corresponde.

`DomicilioController@index/show`:

- Eager load con `departamento`, `provincia` únicamente.

## Formularios y Vistas

`resources/views/cargas/create.blade.php` (Pestaña 2):

- Agregado `<input type="text" name="domicilio_legal[barrio]">` y validación de errores.

`resources/views/domicilios/create.blade.php` y `edit.blade.php`:

- Sustituido componente Livewire de barrio por `<input type="text" name="barrio" …>` con `old()` y valor inicial en edición.

`resources/views/livewire/filtro-domicilio.blade.php`:

- Columna “Barrio / Tipo” simplificada: muestra texto barrio si existe, si no monoblock, si no “N/A”.

## Wizard (Estado Final)

Pestaña 1: Datos legales (legajo, carátula, fecha, hora, brigada).
Pestaña 2: Persona + Domicilio legal (incluye barrio texto).
Pestaña 3: Domicilios allanados + mapa Leaflet (lat/long opcional, múltiples ítems dinámicos).

Gestión de errores: selección automática de pestaña con errores (`$errorsPaso1/2/3`).

## Eager Loading Post‑Refactor

- Eliminado cualquier referencia a relación `barrio` en cargas de procedimientos y domicilios.
- Se mantiene enfoque en `provincia` y `departamento` (normalizados).

## Verificación Rápida

1. Migrar (si hubiera cambios nuevos):

```bash
php artisan migrate
```

2. Crear carga completa en `carga/create`:
   - Confirmar creación de Procedimiento, Persona y Domicilio legal con barrio texto.
   - Ver direccion_completa incluye “B° …” si se carga barrio.
3. Listar domicilios: comprobar columna Barrio usa texto directo.
4. Editar domicilio: modificar barrio y verificar actualización en listado y direccion_completa.

## Pendientes Futuro (Siguiente Iteración)

- Pivot “domicilios conocidos” (reintroducir sólo si aporta valor operativo).
- Búsqueda avanzada (scoring) combinando calle, barrio, sector y coordenadas.
- Validaciones condicionales: exigir lat/long cuando se marca tipo “georreferenciado”.
- Indexación full-text o Scout para acelerar búsquedas si crece el volumen.
- Limpieza de migraciones NO-OP históricas (ya iniciada) — confirmar ramas.

## Notas Finales

La simplificación de barrio a texto reduce fricción y evita sobrecostos de mantenimiento temprano. Provincias y Departamentos permanecen normalizados para análisis estadístico. El flujo unificado ahora cubre el 100% del caso de carga inicial y edición sin ramificaciones legacy.

Preparado para generar commit único de consolidación cuando se indique.

---

Para cualquier ampliación (geocodificado inverso, batch import, auditoría de cambios), lo abordamos en la próxima sesión.

---

## Conexión Usuario–Brigada (Asignación Operativa)

Objetivo: cada usuario pertenece a una Brigada para filtrar o anotar autoría de actuaciones.

1. Migración: `add_brigada_to_users_table` (FK opcional a `brigadas.id`).

2. Modelo `User.php`:

```php
public function brigada()
{
   return $this->belongsTo(Brigada::class);
}
```

3. Asignación rápida con Tinker (usuario de prueba):

```bash
php artisan tinker
>>> $u = App\Models\User::where('email','test@example.com')->first();
>>> $u->brigada_id = App\Models\Brigada::first()->id; $u->save();
```
