# 🎯 Referencia Rápida - Responsive Design ARDIP

## Breakpoints

```
xs  320px   Móvil pequeño (iPhone SE)
sm  640px   Móvil estándar
md  768px   ⭐ Tablet (breakpoint principal)
lg  1024px  Desktop pequeño
xl  1280px  Desktop estándar
2xl 1536px  Desktop grande
```

## Patrones Rápidos

### Ocultar/Mostrar

```blade
<div class="hidden md:block">Solo en desktop</div>
<div class="md:hidden">Solo en móvil</div>
```

### Grid Responsivo

```blade
<!-- 1 col (móvil), 2 cols (tablet), 3 cols (desktop) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
```

### Flex Responsivo

```blade
<!-- Vertical (móvil), Horizontal (desktop) -->
<div class="flex flex-col md:flex-row gap-4">
```

### Tipografía

```blade
<h1 class="text-2xl md:text-3xl lg:text-4xl">Título</h1>
<p class="text-sm md:text-base lg:text-lg">Párrafo</p>
```

### Espaciado

```blade
<div class="p-4 md:p-6 lg:p-8">Padding adaptativo</div>
<div class="mt-2 md:mt-4 lg:mt-6">Margin adaptativo</div>
```

## Componentes

### Tarjeta de Estadística

```blade
<x-stat-card 
    title="Título"
    value="123"
    icon="📊"
    color="blue"
    subtitle="Subtítulo"
/>
```

**Colores:** gray, green, blue, red, indigo

### Tarjeta General

```blade
<x-card title="Título" icon="🎯" description="Desc">
    Contenido
    <x-slot name="actions">
        <button>Botón</button>
    </x-slot>
</x-card>
```

### Tabla Responsiva

```blade
<x-responsive-table :headers="['Col1', 'Col2', 'Col3']">
    <!-- Versión desktop con <tr> -->
    <x-slot name="mobile">
        <!-- Versión móvil con cards -->
    </x-slot>
</x-responsive-table>
```

## Clases CSS

### Botones

```
btn-primary    Azul indigo
btn-secondary  Gris
btn-success    Verde
btn-danger     Rojo
btn-responsive Ancho completo en móvil
```

### Formularios

```
form-group     Contenedor
form-label     Etiqueta
form-input     Input text
form-select    Select dropdown
```

### Badges

```
badge badge-primary    Azul
badge badge-success    Verde
badge badge-warning    Amarillo
badge badge-danger     Rojo
```

### Alertas

```
alert alert-info       Información
alert alert-success    Éxito
alert alert-warning    Advertencia
alert alert-danger     Error
```

## Grids Predefinidos

```blade
<!-- 3 columnas responsivo -->
<div class="grid-responsive">

<!-- 2 columnas responsivo -->
<div class="grid-responsive-2">

<!-- Flex responsivo -->
<div class="flex-responsive">
```

## Toque Final

### Hover Effect

```blade
<div class="hover:shadow-lg transition duration-200">
```

### Animación

```blade
<div class="animate-fadeIn">Fade in</div>
<div class="animate-slideInUp">Slide up</div>
```

### Dark Mode

```blade
<div class="bg-white dark:bg-gray-800">
    Tema claro y oscuro
</div>
```

## Verificación Rápida

### Móvil (375px)

- [ ] Sin scroll horizontal
- [ ] Textos legibles
- [ ] Botones ≥ 44x44px
- [ ] Navegación accesible

### Tablet (768px)

- [ ] Layout 2 columnas
- [ ] Campos bien espaciados
- [ ] Tabla legible o cards

### Desktop (1920px)

- [ ] Layout 3 columnas
- [ ] Tabla completa
- [ ] Máximo ancho respetado

## Ejemplos Rápidos

### Dashboard Responsive

```blade
<div class="grid-responsive">
    <x-stat-card title="Procedimientos" value="42" icon="📊" />
    <x-stat-card title="Detenidos" value="15" icon="👮" />
    <x-stat-card title="Positivos" value="8" icon="✓" />
</div>
```

### Formulario Responsive

```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="form-group">
        <label class="form-label">Campo 1</label>
        <input class="form-input">
    </div>
    <div class="form-group">
        <label class="form-label">Campo 2</label>
        <input class="form-input">
    </div>
</div>
```

### Tabla Responsiva

```blade
<x-responsive-table :headers="['Nombre', 'Email', 'Acciones']">
    @foreach($users as $user)
        <tr>
            <td class="px-4 md:px-6 py-3">{{ $user->name }}</td>
            <td class="px-4 md:px-6 py-3">{{ $user->email }}</td>
            <td class="px-4 md:px-6 py-3 space-x-2">Ver | Editar | Eliminar</td>
        </tr>
    @endforeach
    <x-slot name="mobile">
        @foreach($users as $user)
            <div class="card-responsive">
                <p><strong>{{ $user->name }}</strong></p>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <div class="mt-3 space-x-2">Ver | Editar | Eliminar</div>
            </div>
        @endforeach
    </x-slot>
</x-responsive-table>
```

## Atajos Útiles

```blade
<!-- Contenedor max-width responsive -->
<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">

<!-- Grid de 2 columnas-->
<div class="grid-responsive-2">

<!-- Flex apilado -->
<div class="flex flex-col md:flex-row gap-4">

<!-- Título adaptativo -->
<h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">

<!-- Padding adaptativo -->
<div class="p-4 md:p-6 lg:p-8">

<!-- Gap adaptativo -->
<div class="gap-3 md:gap-4 lg:gap-6">
```

## Troubleshooting

| Problema | Solución |
|----------|----------|
| Texto muy pequeño | `text-sm md:text-base` |
| Overflow horizontal | `overflow-hidden` o `text-ellipsis` |
| Botón pequeño | Mínimo `py-2 px-3` |
| Grid apretado | Aumentar `gap-4 md:gap-6` |
| Imagen grande | `w-full md:w-auto` |
| Espaciado inconsistente | Usar clases predefinidas |

## Documentación Completa

- 📘 **MEJORAS_RESPONSIVIDAD_COMPLETO.md** - Guía técnica
- 📗 **GUIA_COMPONENTES_RESPONSIVE.md** - Cómo usar componentes
- 📙 **RESUMEN_EJECUTIVO_RESPONSIVIDAD.md** - Visión general
- 📓 **EJEMPLOS_VISUALES_ANTES_DESPUES.md** - Comparativas
- 📕 **Esta documento** - Referencia rápida

## Reglas de Oro

✅ Mobile-first (estilos base para móvil)  
✅ Usa `md:` como breakpoint principal  
✅ Grid: 1 col → 2 cols → 3 cols  
✅ Botones mínimo 44x44px  
✅ Textos mínimo 12px en móvil  
✅ Sin scroll horizontal  
✅ Reutiliza componentes  
✅ Prueba en múltiples tamaños  

## Última Referencia

```blade
<!-- Contenedor responsivo completo -->
<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-12">
    
    <!-- Título responsivo -->
    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">
        Mi Página
    </h1>
    
    <!-- Grid responsivo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        
        <!-- Card responsive -->
        <x-card title="Card 1" icon="🎯">
            Contenido
        </x-card>
        
    </div>
    
</div>
```

---

**¿Necesitas más?** Consulta los documentos completos en el proyecto.

**Versión:** 1.0  
**Actualizado:** 5 enero 2026
