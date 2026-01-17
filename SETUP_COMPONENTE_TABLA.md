# Configuración del Componente de Tabla

## Por qué no se ven los cambios

Los cambios en el componente `<x-tabla>` requieren que el proyecto esté correctamente configurado y que los assets estén compilados. Si no ve los cambios, probablemente necesite:

1. **Instalar las dependencias de PHP** (Composer)
2. **Instalar las dependencias de Node** (npm)
3. **Compilar los assets de frontend** (Vite)
4. **Limpiar las cachés de Laravel**

## Pasos de Configuración

### 1. Instalar Dependencias de PHP

```bash
composer install
```

### 2. Configurar el Entorno

Si no existe el archivo `.env`:

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Crear la Base de Datos

```bash
touch database/database.sqlite
php artisan migrate
```

### 4. Instalar Dependencias de Node

```bash
npm install
```

### 5. Compilar Assets de Frontend (IMPORTANTE)

**Para desarrollo:**
```bash
npm run dev
```

**Para producción:**
```bash
npm run build
```

### 6. Limpiar Cachés de Laravel

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

## Verificación

Después de seguir estos pasos, el componente `<x-tabla>` debería funcionar correctamente en todas las vistas:

- `/admin/brigadas` - Gestión de Brigadas
- `/admin/ufis` - Gestión de UFIs
- `/documentos` - Biblioteca Digital
- `/personas` - Gestión de Personas
- `/domicilios` - Gestión de Domicilios
- `/procedimientos` - Búsqueda de Procedimientos

## Características del Componente

El nuevo componente incluye:

- ✅ Soporte completo para modo oscuro
- ✅ Diseño responsive (tabla en desktop, tarjetas en móvil)
- ✅ Estilos unificados de hover y bordes
- ✅ Botones de acción consistentes
- ✅ Contenedor con sombra y bordes redondeados

## Solución de Problemas

**Problema:** Los estilos no se aplican correctamente
- **Solución:** Ejecute `npm run build` para compilar los assets de Tailwind CSS

**Problema:** Error "View not found"
- **Solución:** Ejecute `php artisan view:clear`

**Problema:** Estilos antiguos persisten
- **Solución:** Limpie la caché del navegador (Ctrl+Shift+R o Cmd+Shift+R)

**Problema:** El componente no se encuentra
- **Solución:** Verifique que `resources/views/components/tabla.blade.php` existe
