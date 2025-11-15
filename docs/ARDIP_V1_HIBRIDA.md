# ARDIP V1 Híbrida — Limpieza de Arquitectura

Estado a 2025-11-15

- Provincias/Departamentos: se mantienen tablas, modelos, seeders y selects.
- Barrios: eliminado todo uso relacional/catálogo; se usa campo de texto `barrio`.
- Domicilios conocidos: eliminado pivote `persona_domicilio` en código y migraciones neutralizadas.
- Livewire: removidos `@livewireStyles/@livewireScripts`, uso en vistas y dependencias de componentes.

Cambios clave

- `resources/views/layouts/app.blade.php`: sin directivas Livewire.
- `resources/views/domicilios/index.blade.php`: render del listado sin Livewire (paginado del controlador).
- `app/Http/Controllers/PersonaController.php`: sin métodos de vinculación/antigüedad de domicilios; `show()` carga `procedimientos` y `domicilio`.
- `app/Models/Persona.php`: eliminada relación `domicilios()` many-to-many; se mantiene `domicilio()`.
- `database/migrations/2025_11_13_*` y `2025_11_14_*` persona_domicilio: neutralizadas (no crean tabla).

Pendientes (manual/infra)

- Si se desea, eliminar físicamente archivos no referenciados (opcional):
  - `app/Livewire/*`
  - `resources/views/livewire/*`
  - `app/Services/BarriosCatalog.php`
  - `scripts/check_barrios.php`

Notas

- No se tocaron dependencias Composer; Livewire puede eliminarse en otra iteración si no se usará más.
- Formularios de Domicilio conservan selects de provincia/departamento y campo de texto `barrio`.
