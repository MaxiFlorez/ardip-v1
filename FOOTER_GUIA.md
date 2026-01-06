================================================================================
                    GUÍA DE FOOTERS - ARDIP SISTEMA
================================================================================

✅ FOOTERS DISPONIBLES:

1. FOOTER ESTÁNDAR (Recomendado)
   Ubicación: resources/views/components/footer.blade.php
   Uso: <x-footer />

   Características:
   ✓ Branding (Logo + versión)
   ✓ Información de copyright
   ✓ Botón de Soporte con email
   ✓ Link de Ayuda
   ✓ Indicador de estado del sistema
   ✓ Hora actual actualizada
   ✓ Tema oscuro profesional

   Dónde se usa:
   - layouts/app.blade.php (Dashboard, Procedimientos, Admin, etc)
   - Todas las vistas autenticadas

2. FOOTER MINIMALISTA
   Ubicación: resources/views/components/footer-minimal.blade.php
   Uso: <x-footer-minimal />

   Características:
   ✓ Compacto y ligero
   ✓ Branding simple
   ✓ Copyright
   ✓ Botón de soporte
   ✓ Tema claro

   Dónde se usa:
   - Para vistas simples
   - Modales
   - Páginas de error

================================================================================

📧 CAMBIAR EMAIL DE SOPORTE:

Editar en los archivos:

- resources/views/components/footer.blade.php - Línea: <soporte@ardip.local>
- resources/views/components/footer-minimal.blade.php - Línea: <soporte@ardip.local>

Cambiar a: <tu-email@dominion.com>

================================================================================

🎨 PERSONALIZAR COLORES:

Footer Estándar (Tema oscuro):

- bg-gray-900: Background principal
- bg-gray-950: Línea inferior

Footer Minimalista (Tema claro):

- bg-gray-50: Background
- text-primary-600: Color de links (cambiar primary por tu color)

================================================================================

✨ USO EN VISTAS:

Ya está incluido en:
✓ layouts/app.blade.php (Automático en todas las vistas autenticadas)
✓ layouts/guest.blade.php (Login, Registro, etc)

Para agregar en otra vista:
<x-footer />         <!-- Estándar oscuro -->
<x-footer-minimal /> <!-- Minimalista claro -->

================================================================================

📱 RESPONSIVO:

Ambos footers son completamente responsivos:

- Móvil: Items apilados verticalmente
- Tablet: Items en fila
- Desktop: Distribución balanceada

================================================================================

🔗 LINKS DE SOPORTE:

Actualmente configurado como:

- Email: mailto:soporte@ardip.local
- Ayuda: # (link vacío, personalizar según necesites)

Para cambiar "Ayuda":
Editar resources/views/components/footer.blade.php línea 38

================================================================================

✅ VERIFICACIÓN:

Para ver los footers en acción:

1. Navega a cualquier página autenticada
2. Scrollea hasta el final
3. Deberías ver el footer oscuro

Para login:

1. Navega a /login
2. Scrollea hasta el final
3. Deberías ver el footer oscuro en la página de login

================================================================================

✨ CARACTERÍSTICAS ESPECIALES:

Footer Estándar:

- Muestra estado del sistema (✓ Sistema operativo)
- Muestra hora actual en tiempo real
- Iconos de soporte y ayuda
- Botón de soporte con hover effect

Footer Minimalista:

- Más simple y compacto
- Ideal para vistas internas
- Menos "ruido" visual

================================================================================
