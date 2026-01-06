# 🚀 Guía Rápida - Componentes Responsive

## Cómo Usar los Nuevos Componentes

### 1. Tarjeta de Estadísticas (Stat Card)

**Uso:**

```blade
<x-stat-card 
    title="Total de Procedimientos"
    value="156"
    icon="📋"
    color="blue"
    subtitle="Período: Enero 2026"
/>
```

**Colores disponibles:** `gray`, `green`, `blue`, `red`, `indigo`

**Ejemplo en Dashboard:**

```blade
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <x-stat-card title="Total Procedimientos" value="42" icon="📊" color="blue" />
    <x-stat-card title="Detenidos" value="15" icon="👮" color="red" />
    <x-stat-card title="Positivos" value="8" icon="✓" color="green" />
</div>
```

---

### 2. Tarjeta General (Card)

**Uso:**

```blade
<x-card title="Mi Sección" icon="🎯" description="Descripción opcional">
    <!-- Tu contenido aquí -->
    
    <x-slot name="actions">
        <button class="btn-primary">Acción 1</button>
        <button class="btn-secondary">Acción 2</button>
    </x-slot>
</x-card>
```

**Ejemplo con contenido:**

```blade
<x-card title="Información de Usuario" icon="👤">
    <p>Nombre: Juan Pérez</p>
    <p>Email: juan@example.com</p>
    
    <x-slot name="actions">
        <a href="#" class="btn-primary">Editar</a>
        <a href="#" class="btn-secondary">Cancelar</a>
    </x-slot>
</x-card>
```

---

### 3. Tabla Responsive

**Uso:**

```blade
<x-responsive-table :headers="['Nombre', 'Email', 'Rol', 'Acciones']">
    @foreach($users as $user)
        <tr>
            <td class="px-6 py-4">{{ $user->name }}</td>
            <td class="px-6 py-4">{{ $user->email }}</td>
            <td class="px-6 py-4">{{ $user->role }}</td>
            <td class="px-6 py-4 space-x-2">
                <a href="#">Editar</a> | <a href="#">Eliminar</a>
            </td>
        </tr>
    @endforeach
    
    <x-slot name="mobile">
        @foreach($users as $user)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p><strong>{{ $user->name }}</strong></p>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-600">Rol: {{ $user->role }}</p>
                <div class="mt-3 space-x-2">
                    <a href="#">Editar</a> | <a href="#">Eliminar</a>
                </div>
            </div>
        @endforeach
    </x-slot>
</x-responsive-table>
```

---

## Clases CSS Predefinidas

### Botones

```blade
<button class="btn-primary">Primario</button>
<button class="btn-secondary">Secundario</button>
<button class="btn-success">Éxito</button>
<button class="btn-danger">Peligro</button>

<!-- Ancho completo en móvil -->
<button class="btn-primary btn-responsive">Responsive</button>
```

### Formularios

```blade
<div class="form-group">
    <label class="form-label">Tu Nombre</label>
    <input type="text" class="form-input" placeholder="Escribe tu nombre">
</div>

<div class="form-group">
    <label class="form-label">Selecciona Opción</label>
    <select class="form-select">
        <option>Opción 1</option>
        <option>Opción 2</option>
    </select>
</div>
```

### Badges

```blade
<span class="badge badge-primary">Información</span>
<span class="badge badge-success">Exitoso</span>
<span class="badge badge-warning">Advertencia</span>
<span class="badge badge-danger">Error</span>
```

### Alertas

```blade
<div class="alert alert-info">Mensaje informativo</div>
<div class="alert alert-success">¡Operación exitosa!</div>
<div class="alert alert-warning">Ten cuidado</div>
<div class="alert alert-danger">Error en la operación</div>
```

---

## Grids Responsive

```blade
<!-- Grid de 3 columnas (1 móvil, 2 tablet, 3 desktop) -->
<div class="grid-responsive">
    <div>Item 1</div>
    <div>Item 2</div>
    <div>Item 3</div>
</div>

<!-- Grid de 2 columnas (1 móvil, 2 desktop) -->
<div class="grid-responsive-2">
    <div>Item 1</div>
    <div>Item 2</div>
</div>

<!-- Flex responsivo (vertical móvil, horizontal desktop) -->
<div class="flex-responsive">
    <div>Elemento 1</div>
    <div>Elemento 2</div>
</div>
```

---

## Breakpoints y Clases Responsive

### Cambiar contenido según pantalla

```blade
<!-- Oculto en móvil, visible en desktop -->
<div class="hidden md:block">
    Solo en desktop
</div>

<!-- Visible en móvil, oculto en desktop -->
<div class="md:hidden">
    Solo en móvil
</div>

<!-- Cambiar layout -->
<div class="flex flex-col md:flex-row gap-4">
    <!-- Vertical en móvil, horizontal en desktop -->
</div>

<!-- Cambiar tamaños -->
<h1 class="text-2xl md:text-4xl">
    Tamaño adaptativo
</h1>
```

---

## Tailwind Responsive Patterns

### Textos Adaptables

```blade
<!-- Tamaño de fuente responsivo -->
<p class="text-sm md:text-base lg:text-lg">Texto adaptativo</p>

<!-- Peso de fuente responsivo -->
<p class="font-normal md:font-semibold">Bold en desktop</p>

<!-- Padding responsivo -->
<div class="p-2 md:p-4 lg:p-6">Padding adaptativo</div>
```

### Grid Responsivo

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- 1 col móvil, 2 cols tablet, 3 cols desktop -->
</div>
```

### Espaciado Responsive

```blade
<div class="space-y-2 md:space-y-4">
    <!-- 2 unidades en móvil, 4 en desktop -->
</div>
```

---

## Ejemplos Completos

### Ejemplo 1: Dashboard KPI

```blade
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-6">Dashboard</h1>
        
        <!-- Grid responsivo de KPIs -->
        <div class="grid-responsive">
            <x-stat-card title="Usuarios" value="1,234" icon="👥" color="blue" />
            <x-stat-card title="Ingresos" value="$45K" icon="💰" color="green" />
            <x-stat-card title="Advertencias" value="12" icon="⚠️" color="yellow" />
        </div>
    </div>
</div>
```

### Ejemplo 2: Tabla de Datos

```blade
<div class="max-w-7xl mx-auto px-4 md:px-8 py-6">
    <!-- Formulario de búsqueda -->
    <div class="mb-6 grid-responsive-2">
        <input type="text" class="form-input" placeholder="Buscar...">
        <button class="btn-primary">Buscar</button>
    </div>
    
    <!-- Tabla responsiva -->
    <x-responsive-table :headers="['ID', 'Nombre', 'Email', 'Acciones']">
        @foreach($items as $item)
            <tr>
                <td class="px-4 md:px-6 py-3">{{ $item->id }}</td>
                <td class="px-4 md:px-6 py-3">{{ $item->name }}</td>
                <td class="px-4 md:px-6 py-3">{{ $item->email }}</td>
                <td class="px-4 md:px-6 py-3 space-x-2">
                    <a href="#" class="text-blue-600 hover:underline">Editar</a>
                    <a href="#" class="text-red-600 hover:underline">Eliminar</a>
                </td>
            </tr>
        @endforeach
    </x-responsive-table>
</div>
```

### Ejemplo 3: Formulario Responsive

```blade
<div class="max-w-2xl mx-auto px-4 md:px-0 py-6">
    <x-card title="Nuevo Usuario" icon="➕" description="Crea un nuevo usuario en el sistema">
        <form method="POST" class="space-y-4">
            @csrf
            
            <!-- Grid responsivo -->
            <div class="grid-responsive-2">
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="role" class="form-select" required>
                    <option>Admin</option>
                    <option>Usuario</option>
                    <option>Invitado</option>
                </select>
            </div>
            
            <!-- Botones -->
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 md:flex-none">Crear</button>
                <button type="button" class="btn-secondary flex-1 md:flex-none">Cancelar</button>
            </div>
            
            <x-slot name="actions">
                <button type="submit" class="btn-primary btn-responsive">Guardar Usuario</button>
                <a href="#" class="btn-secondary btn-responsive">Cancelar</a>
            </x-slot>
        </form>
    </x-card>
</div>
```

---

## Tips y Mejores Prácticas

### ✅ DO's (Haz esto)

- Usa `grid-cols-1 md:grid-cols-2` para responsive
- Usa `hidden md:block` para mostrar/ocultar
- Usa `flex-col md:flex-row` para cambiar dirección
- Usa `px-4 md:px-6 lg:px-8` para espaciado adaptativo
- Usa `text-sm md:text-base lg:text-lg` para tipografía

### ❌ DON'Ts (Evita esto)

- No hardcodees tamaños fijos sin breakpoints
- No uses `px-10` en móviles (muy grande)
- No ocultes contenido importante en móvil
- No hagas botones muy pequeños (< 44x44px)
- No uses scroll horizontal en tablas

---

## Testeo en Diferentes Dispositivos

**Devtools Chrome:**

1. F12 → Device Toggle
2. Prueba en: iPhone SE, iPhone 12, iPad, Desktop

**Breakpoints a revisar:**

- 375px (móvil pequeño)
- 640px (móvil grande)
- 768px (tablet)
- 1024px (desktop)
- 1280px+ (desktop grande)

---

## Soporte y Troubleshooting

**Problema: Elemento se ve mal en móvil**

```blade
<!-- Debugging: añade borders temporales -->
<div class="border-2 border-red-500 md:border-0">
    Esto te ayuda a ver los límites
</div>
```

**Problema: Overflowing horizontal**

```blade
<!-- Solución: usa overflow-hidden en contenedor -->
<div class="overflow-hidden">
    <table class="w-full">...</table>
</div>
```

**Problema: Texto muy pequeño en móvil**

```blade
<!-- Solución: ajusta tamaño -->
<p class="text-xs sm:text-sm md:text-base">
    Tamaño mínimo 12px en móvil
</p>
```

---

**Última actualización:** 5 de enero de 2026
**Versión:** 1.0
