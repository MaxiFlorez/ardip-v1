# ARDIP V1 - Documentación Sesión 8

## Mejora de UX: Buscador Dinámico y Normalización de Barrios

Fecha: 12 de Noviembre, 2025  
Alumno: Flores, Maximiliano  
Proyecto: Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
Duración: ~3.5 horas  
Estado: ✅ Completada (buscador operativo, formularios ordenados y guardando)

---

## Resumen Ejecutivo

Se consolidó la decisión estratégica de abandonar la FK `barrio_id` y volver a un campo de texto `barrio`, asistido por un Buscador Dinámico (Livewire + catálogo JSON cacheado). Esto simplifica la BD, evita problemas de codificación, mantiene la flexibilidad para cargar barrios no catalogados, y ofrece una UX más ágil.  
Además, se reordenaron los inputs de Provincia/Departamento, se extendió el accessor de dirección, se corrigieron advertencias de Alpine y accesibilidad, y se alineó el listado con eager loading y orden correcto.

---

## Objetivos Cumplidos

- Base de datos: vuelta a `domicilios.barrio` como texto; migraciones obsoletas neutralizadas (NO-OP).
- Catálogo JSON: `BarriosCatalog` con caché 24h y normalización (case/acentos).
- Livewire: componente `BuscarBarrioJson` con búsqueda a partir de 3 chars, selección por índice y campo oculto para enviar `barrio` al formulario.
- Formularios: `provincia` primero y `departamento` debajo, con habilitación condicional (solo San Juan).
- Accessor dirección: `direccion_completa` extendido con etiquetas MBLK/MZNA/LOTE/CASA, TORRE, PISO/DPTO y SECTOR, todo condicional.
- Listado: `DomicilioController@index` con eager loading (`provincia`, `departamento`), orden por `departamento_id` y `calle`, paginado.
- Livewire listado: `FiltroDomicilio` corrige eager loading y orden; vista usa `barrio` como texto (no relación) y se eliminó el filtro por Provincia.
- Alpine: eliminado doble inicio; integración con `deferLoadingAlpine` (Livewire v3) sin warnings.
- Accesibilidad: labels asociados y `id`/`aria-describedby` en el input del buscador de barrio.
- Brigadas y navegación: añadido `brigada_id` en `users`, seeders de brigadas y usuarios de prueba, y navegación mostrando la brigada del usuario y enlaces a Personas/Procedimientos/Domicilios.
- Tests: suite en verde; pruebas específicas para el componente de barrios.

---

## Flujo de Trabajo y Solución de Bugs

1) Reversión “barrios” tabla → catálogo JSON  
   - Problemas de codificación (acentos) y rigidez de FK.  
   - Servicio `app/Services/BarriosCatalog.php` con `Cache::remember(86400)` y normalización para búsqueda.

2) Componente Livewire `BuscarBarrioJson`  
   - Búsqueda tolerante a acentos/case; resultados limitados.  
   - Selección por índice para evitar issues con comillas/acentos.  
   - Campo oculto `name="barrio"` para envío confiable del valor.  
   - Fix de accesibilidad: `inputId`, `id` y `aria-describedby`.

3) Formularios y UX  
   - Reordenados: Provincia arriba, Departamento debajo, JS para habilitar solo si Provincia = San Juan.  
   - Integrado el componente en `create` y `edit`.

4) Dirección completa  
   - Accessor en `Domicilio` que compone: CALLE/NUM, MBLK, B° BARRIO, SECTOR, MZNA/LOTE/CASA, TORRE, PISO/DPTO (solo si hay datos).  
   - Etiquetas ajustadas por preferencia: MBLK, MZNA, LOTE.

5) Listado y rendimiento  
   - `index()` con `with(['provincia','departamento'])` y `orderBy('departamento_id')->orderBy('calle')`.  
   - `FiltroDomicilio` alinea eager loading/orden y vista usa `barrio` (texto) o MBLK.

6) Alpine y Livewire  
   - Se eliminó el doble `Alpine.start()` y se aplicó `window.deferLoadingAlpine` para coordinar un único arranque y evitar warnings.

7) Brigadas y navegación  
   - Se agregó `brigada_id` a `users` con FK a `brigadas`, seeders de brigadas y de usuarios de prueba asignados.  
   - La navegación ahora muestra la brigada del usuario e incluye accesos a Personas, Procedimientos y Domicilios.

---

## Cambios Clave en Código

- `app/Services/BarriosCatalog.php`: carga + caché + normalización de catálogo JSON.
- `app/Livewire/BuscarBarrioJson.php`: propiedades, selección por índice y `inputId` para accesibilidad.
- `resources/views/livewire/buscar-barrio-json.blade.php`: `id`, `aria-describedby`, lista de resultados clicable.
- `resources/views/domicilios/create.blade.php` y `edit.blade.php`: componente de barrio, labels asociados, provincia/departamento reordenados.
- `app/Models/Domicilio.php`: accessor `direccion_completa` extendido; mutadores de normalización.
- `app/Http/Controllers/DomicilioController.php`: `index()` con eager loading + orden + paginado.
- `app/Livewire/FiltroDomicilio.php` y `resources/views/livewire/filtro-domicilio.blade.php`: eager loading/orden corregidos, uso de `barrio` (texto) y MBLK.
- `resources/js/app.js`: integración Alpine + Livewire con `deferLoadingAlpine` (sin fallback que duplique inicio).
- `resources/views/layouts/navigation.blade.php`: navegación con enlaces a módulos y muestra de la brigada del usuario (desktop y responsive).
- `app/Models/User.php`: relación `brigada()` y `$fillable` con `brigada_id`.
- `database/migrations/2025_11_12_235900_add_brigada_to_users_table.php`: FK `brigada_id` en `users` (nullable) con `constrained('brigadas')`.
- `database/seeders/BrigadaSeeder.php` y `database/seeders/TestUsersSeeder.php`: catálogo de brigadas y usuarios de prueba asociados a brigadas.

---

## Pruebas y Estado

- Suite completa en verde.  
- Pruebas del componente de barrios: búsqueda (≥3 chars), acentos/case, selección por índice y envío correcto del valor.

---

## Cómo Probar Rápido

1) Crear/editar Domicilio  
   - Ruta: `Domicilios > Agregar`  
   - Seleccionar Provincia/Departamento (Depto solo habilitado en San Juan).  
   - Buscar barrio, seleccionar, guardar.

2) Listado  
   - `Domicilios > Listado`  
   - Ver orden por Departamento y Calle, paginado.  
   - Sin filtro por Provincia (simplificado según objetivo actual).

3) Navegación y Brigadas  
   - Ingresar con un usuario de prueba y verificar que en la navegación se muestre la brigada asignada y los enlaces a Personas/Procedimientos/Domicilios.

---

## Próximos Pasos (Sesión 9)

- Documentación en `README.md`: estructura del JSON, caché y uso del componente (pendiente).  
- Índices de performance: `provincia_id, departamento_id` (compuesto), `barrio`, `calle`.  
- Reemplazar el `<select>` de Personas por buscador dinámico (Livewire), con normalización similar.
