# Sesión 12 — Cierre

Fecha: 16/11/2025  
Rama: `feature/buscador-dinamico`

## Objetivo

Actualizar el módulo de Procedimientos para reflejar el nuevo modelo de dato `orden_judicial`, modernizar las vistas `index` y `show` (Fase 12), y adaptar edición/actualización (Fase 13 parcial) eliminando el uso de los booleanos antiguos.

## Cambios clave

- Orden y carga de datos en controlador:
  - `ProcedimientoController@index`: ordena por `latest('fecha_procedimiento')->latest('hora_procedimiento')`.
  - `ProcedimientoController@show`: carga relaciones `brigada`, `personas`, `domicilios`.
- Listado (index):
  - `resources/views/procedimientos/index.blade.php`: agregada columna `Orden Judicial`; removido botón "Eliminar" en tabla y cards móviles.
- Detalle (show):
  - `resources/views/procedimientos/show.blade.php`: reemplazados los 3 booleanos por `orden_judicial` en "Datos Generales"; nuevas secciones "Personas Vinculadas" (incluye `pivot->situacion_procesal`) y "Domicilios del Hecho Vinculados" (dirección completa); removido botón "Eliminar".
- Edición/Actualización (Fase 13 parcial):
  - `resources/views/procedimientos/edit.blade.php`: incorporado `<select name="orden_judicial">` con las 4 opciones definidas; muestra valor actual.
  - `ProcedimientoController@update`: validación y guardado de `orden_judicial`.
  - Alineación de creación/validación: `ProcedimientoController@store` actualizado a validar las mismas 4 opciones.
- Asistente de Carga (consistencia):
  - `resources/views/cargas/create.blade.php`: el `<select name="orden_judicial">` usa las 4 opciones acordadas.
  - `resources/views/procedimientos/create.blade.php`: mismo set de opciones para evitar desajustes.

## Opciones válidas de `orden_judicial`

1. Detención en caso de secuestro positivo  
2. Detención directa  
3. Notificación al acusado  
4. Secuestro y notificación

## Archivos modificados

- Controlador
  - `app/Http/Controllers/ProcedimientoController.php`
    - `index()`: orden descendente por fecha y hora.
    - `show()`: `load('brigada','personas','domicilios')`.
    - `store()`: validación y persistencia de `orden_judicial` (4 opciones).
    - `update()`: validación y persistencia de `orden_judicial` (4 opciones).
- Vistas Procedimientos
  - `resources/views/procedimientos/index.blade.php`: columna `Orden Judicial` + se elimina acción "Eliminar".
  - `resources/views/procedimientos/show.blade.php`: muestra `orden_judicial`; nuevas secciones de vinculaciones; sin "Eliminar".
  - `resources/views/procedimientos/edit.blade.php`: `<select name="orden_judicial">` con 4 opciones.
  - `resources/views/procedimientos/create.blade.php`: `<select name="orden_judicial">` con 4 opciones.
- Vista Asistente
  - `resources/views/cargas/create.blade.php`: `<select name="orden_judicial">` con 4 opciones.

## Verificaciones y limpieza

- Búsqueda en vistas: sin referencias residuales a booleanos antiguos (`orden_allanamiento`, `orden_secuestro`, `orden_detencion`, `orden_exhibicion`).
- Alineación de opciones: todas las rutas de carga/edición utilizan el mismo set de 4 opciones de `orden_judicial`.

## Notas

- Se mantiene la eliminación deshabilitada en `index` y `show` para prevenir bajas accidentales.
- Las migraciones antiguas con booleanos pueden permanecer por historia; la UI y el flujo actual operan con el campo string `orden_judicial`.

## Próximos pasos sugeridos

- Añadir pruebas de Feature para `index` (orden y columna) y `show` (secciones y contenido de pivote).
- Normalizar en BD un tipo enumerado/check constraint para `orden_judicial` (opcional, según motor).
- Verificar exportaciones/reportes que refieran a órdenes para que usen el nuevo campo.
