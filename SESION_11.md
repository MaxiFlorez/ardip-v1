# SESIÓN 11 — I18n, validaciones, toggle de contraseña y README (15/11/2025)

## Objetivo

- Localizar al español la autenticación y el módulo de Perfil.
- Mostrar mensajes de validación legibles en español.
- Mejorar UX con botón Mostrar/Ocultar en campos de contraseña.
- Asegurar inicialización de Alpine sin Livewire y corregir navegación (dropdown y menú móvil).
- Ajustar README para reflejar la Arquitectura V1 Híbrida y limpiar avisos de Markdown.

---

## Cambios principales

- Idioma y validaciones
  - `config/app.php`: `locale = es` (por defecto).
  - `.env`: `APP_LOCALE=es`, `APP_FALLBACK_LOCALE=es`, `APP_FAKER_LOCALE=es_ES`.
  - `lang/es.json`: traducciones para Autenticación y Perfil (login, register, reset, verify, perfil y botones).
  - `lang/es/validation.php`: mensajes de validación en español (incluye reglas de contraseña) y mapeo de atributos.
  - Limpieza de cachés: `php artisan config:clear`, `cache:clear`, `view:clear`.

- UX de contraseñas
  - Nuevo componente: `resources/views/components/password-input.blade.php` con icono Mostrar/Ocultar.
  - Aplicado a: login, register, reset-password, confirm-password y Perfil (actualizar y eliminar cuenta).

- Navegación y Alpine
  - `resources/js/app.js`: iniciar Alpine si no existe Livewire (`Alpine.start()`).
  - `resources/views/layouts/navigation.blade.php`: mover `x-data` al `<nav>`, quitar duplicado interior y añadir `x-cloak` al menú móvil.

- README
  - Reemplazo de HTML inicial por título Markdown.
  - Actualización de sección V1 Híbrida y estado actual; correcciones de estilo y linter.

---

## Resultado

- Interfaz y validaciones en español.
- Campos de contraseña con toggle de visibilidad en todas las pantallas.
- Dropdown de usuario y menú móvil funcionando con Alpine sin Livewire.
- README más claro y sin advertencias iniciales.

---

## Referencias de commit

- "i18n + UI: Perfil/Autenticación en español, Alpine y toggle de contraseña" (rama `feature/buscador-dinamico`).

---

## Extensión Sesión 11 — Asistente de Carga Unificada (16/11/2025)

### Objetivo ampliado

Incorporar el nuevo Asistente de Carga (Procedimiento + Personas + Domicilios) y consolidar la lógica de creación en pasos, simplificando el modelo de órdenes judiciales y mejorando la mantenibilidad (eliminando dependencias de Alpine en formularios críticos).

### Cambios Técnicos

### Migración y Modelo

- Migración nueva: reemplazo de tres booleanos (`orden_allanamiento`, `orden_secuestro`, `orden_detencion`) por un único campo `orden_judicial` (string: allanamiento|secuestro|detencion|sin_orden).
- Actualización de `app/Models/Procedimiento.php`: se eliminaron casts obsoletos.

### Rutas nuevas

- `POST /carga/vincular` → `carga.vincular` (agregar persona vinculada).
- `POST /carga/vincular-domicilio` → `carga.vincularDomicilio` (agregar domicilio del hecho).
- `POST /carga` (`carga.store`) refactorizado: ahora solo finaliza el asistente y redirige a `procedimientos.index`.

### Controlador (`CargaCompletaController`)

- `create()`: admite `procedimiento_id` para sesión parcial; carga vinculados y domicilios del hecho ya agregados.
- `vincularPersona()`: crea Procedimiento si falta, valida y crea Domicilio principal + Persona, adjunta pivot (`situacion_procesal`, `pedido_captura`, `observaciones`).
- `vincularDomicilio()`: crea Procedimiento si falta, valida y crea Domicilio del hecho (incluye `coordenadas_gps`), lo adjunta al procedimiento.
- `store()`: dejó de crear recursos (solo finaliza la carga y muestra mensaje de éxito).

### Pestaña 1 (Datos Legales)

- Eliminado select de Brigada (ahora se inyecta automáticamente desde el usuario autenticado).
- Reemplazados 3 checkboxes de órdenes por select único `orden_judicial` requerido.
- Limpieza de campos obsoletos (resultado_* y booleans de orden).

### Pestaña 2 (Vinculados)

- Formulario completo de `Persona` (DNI 8 dígitos, nombres, apellidos, fecha_nacimiento, genero, alias, nacionalidad, estado_civil, foto, observaciones).
- Domicilio principal: todos los campos (`provincia_id`, `departamento_id`, `calle`, `numero`, `piso`, `depto`, `torre`, `monoblock`, `manzana`, `lote`, `casa`, `barrio`, `sector`).
- Campos pivot (`situacion_procesal`, `pedido_captura`, `observaciones`).
- Botón parcial "Cargar Vinculado" (envía a `carga.vincular`).
- Listado dinámico de vinculados cargados.
- Eliminado Alpine en esta pestaña; reemplazado por JS nativo para habilitar/deshabilitar `departamento_id` según provincia (San Juan).

### Pestaña 3 (Domicilios del Hecho y Mapa)

- Formulario de domicilio del hecho con mismos campos que domicilio principal + `coordenadas_gps`.
- Integración de Leaflet: clic en mapa llena `coordenadas_gps (lat,long)`.
- Botones: "Cargar Domicilio" (parcial) y "Finalizar Carga" (store).
- Listado de domicilios del hecho cargados.
- Eliminado Alpine para lógica de provincia/departamento; JS nativo reutiliza el mismo patrón de la Pestaña 2.

### JavaScript / UX

- Consolidado `@push('scripts')` en `create.blade.php` con lógica de habilitar/deshabilitar departamento para Pestañas 2 y 3.
- `layouts/app.blade.php` actualizado para renderizar `@stack('scripts')` antes de cierre de `<body>`.

### Resultados Asistente

- Asistente de Carga en tres pasos coherentes y desacoplados.
- Persistencia incremental: cada paso crea y vincula recursos sin esperar al final.
- Menor complejidad: un único campo `orden_judicial` para órdenes judiciales.
- Formularios más mantenibles (sin Alpine en secciones sensibles; JS simple y explícito).
- Flujo final claro: "Finalizar Carga" solo redirige, evitando duplicaciones.

### Próximos Pasos Sugeridos

- Agregar mensajes de ayuda contextual para cada opción de `orden_judicial`.
- Validaciones de duplicado: prevenir múltiples domicilios del hecho idénticos.
- Botón para remover vinculados / domicilios del hecho antes de finalizar.
- Auditoría de permisos (quién puede agregar vinculados una vez iniciado el procedimiento).

---

**Cierre Sesión 11:** Se completó la internacionalización original y se extendió la sesión implementando el Asistente de Carga modular con refactor de modelo, rutas, controlador y vistas (Pestañas 1–3) en la rama `feature/buscador-dinamico`.
