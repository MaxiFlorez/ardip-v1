# SESIÓN 10 — Rediseño Visual (Estilo SIIS) y UX (15/11/2025)

## Objetivo

Unificar la experiencia visual con un layout profesional inspirado en SIIS: Sidebar oscuro fijo, Header fijo alineado, menú hamburguesa para colapsar el Sidebar, navegación ordenada y Footer fijo. Además, asegurar coherencia en páginas de autenticación.

---

## Rediseño del Layout (app.blade.php)

- Estructura base en flex con Sidebar fijo (`w-64`) y Header fijo:
  - Sidebar: `fixed inset-y-0 w-64 bg-gray-900 text-white z-40`.
  - Header: `fixed top-0 right-0 bg-gray-900 text-gray-100` con desplazamiento según Sidebar.
- Contenido principal:
  - `pt-16` para no quedar oculto bajo el Header fijo.
  - Margen izquierdo dinámico con Alpine para Sidebar colapsable (`lg:ml-64` cuando está abierto).
- Footer fijo centrado:
  - `fixed bottom-0 text-center bg-gray-800 text-gray-100 border-t`.
  - Ajuste dinámico del margen para no superponer contenido.

---

## Menú Hamburguesa y Sidebar Colapsable (Alpine.js)

- Estado global: `x-data="{ sidebarOpen: true }"` en `<body>`.
- Botón hamburguesa en el Header que alterna `sidebarOpen`.
- Transiciones suaves:
  - Sidebar: `transform transition-transform duration-300 ease-in-out`.
  - Header/Contenido/Footer: `transition-all duration-300 ease-in-out` para animar el desplazamiento lateral.
- Overlay en móviles (`lg:hidden`) que permite cerrar el Sidebar tocando fuera.

---

## Nueva Navegación (resources/views/layouts/sidebar.blade.php)

- Estilo: Sidebar oscuro (`bg-gray-900`, texto claro) con enlaces compactos.
- Orden definitivo de enlaces:
  1. Lista de Procedimientos → `route('procedimientos.index')`
  2. Nueva Carga → `route('carga.create')`
  3. Vinculados → `route('personas.index')`
  4. Localizaciones → `route('domicilios.index')`
- Eliminado: enlace “Escritorio” (dashboard) del menú principal.
- Íconos: Heroicons (folder/document-text, plus-circle, users, map-pin).
- Estado activo: uso de fondo `bg-gray-900` y texto claro para resaltar.

---

## Limpieza del Header (resources/views/layouts/navigation.blade.php)

- Header unificado con el tema oscuro (`bg-gray-900`) y contenido mínimo.
- Menú de usuario: muestra Nombre y Brigada del usuario actual.
- Corrección de contraste del dropdown:
  - Enlaces del menú (Perfil/Salir) con texto oscuro sobre fondo blanco.
  - Estilos responsive con contraste claro sobre fondo oscuro.

---

## Footer Fijo

- Agregado a `app.blade.php` un `<footer>` fijo, centrado (`text-center`) con el texto:

  `© 2025 - Flores, Maximiliano - Sistema ARDIP V1`

- Ajustes de `padding-bottom` en `<main>` para evitar que el contenido (incluido el mapa) quede oculto tras el Footer.

---

## Coherencia de Marca en Autenticación (guest.blade.php y login)

- Fondo oscuro en layout de invitado:
  - `bg-gray-900` en el contenedor raíz.
  - Texto claro (`text-gray-300`) fuera de la tarjeta.
- Tarjeta central se mantiene blanca (`bg-white`) para alto contraste.
- Login: reemplazo de “¿Olvidaste tu contraseña?” por enlace “Contactar con soporte” (mailto) acorde a un entorno interno administrado.

---

## Notas de Implementación

- Mapas Leaflet y Footer:
  - Se añadió `pb-28` en `<main>` y `z-index` alto al Footer para evitar superposiciones.
  - Los contenedores de mapa tienen `relative z-0` y su inicialización se difiere hasta que la pestaña es visible, llamando a `invalidateSize()` cuando corresponde.
- Accesibilidad: estados hover y foco ajustados en enlaces para contraste/legibilidad.

---

## Pruebas sugeridas

1. Abrir en escritorio y móvil/tablet; colapsar/expandir Sidebar con el botón hamburguesa.
2. Navegar entre “Lista de Procedimientos”, “Nueva Carga”, “Vinculados” y “Localizaciones”; validar estado activo.
3. Probar dropdown de usuario (Perfil/Salir) y menú responsive (contrastes).
4. En la pestaña “Ubicación” de los asistentes, verificar que el mapa no tape el Footer y que se redimensiona correctamente al mostrarse.
