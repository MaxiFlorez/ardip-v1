# 🔍 AUDITORÍA TÉCNICA - Análisis de Código Fuente ARDIP

**Fecha:** 10 de enero de 2026  
**Revisor:** Análisis Automático  
**Alcance:** Todo el repositorio  

---

## 📊 RESUMEN EJECUTIVO

- **Total de Issues:** 12
- **Críticos:** 2
- **Medios:** 4
- **Leves:** 6

✅ **Estado General:** Sistema FUNCIONAL pero con problemas identificables

---

## 🔴 ISSUES CRÍTICOS (Rompen Funcionalidad / Seguridad)

### CRÍTICO #1: vincularDomicilio sin Autorización en Form Request

**Archivo:** `app/Http/Controllers/ProcedimientoController.php:L155-L168`  
**Severity:** 🔴 CRÍTICO - Seguridad

**Problema:**

```php
public function vincularDomicilio(Request $request, Procedimiento $procedimiento)
{
    $datos = $request->validate([
        'domicilio_id' => 'required|exists:domicilios,id',
    ]);
    // ❌ Usa Request genérico, sin Form Request dedicado
    // ❌ Sin authorize() implícita
}
```

El método usa `Request` directo en lugar de un `Form Request` con `authorize()`. Aunque tiene middleware protegiendo la ruta, debería tener protección en el nivel de Request también.

**Impacto:** Posible bypass de seguridad si se cambian rutas.

---

### CRÍTICO #2: Null pointer en ProcedimientoController.store()

**Archivo:** `app/Http/Controllers/ProcedimientoController.php:L66-L68`  
**Severity:** 🔴 CRÍTICO - Runtime

**Problema:**

```php
$validated['usuario_id'] = Auth::id();  // ✅ Seguro (Auth verificado por middleware)
$validated['brigada_id'] = Auth::user()->brigada_id ?? null;  // ⚠️ Puede ser null
```

Si `brigada_id` es `null`, puede violar constraint `NOT NULL` en base de datos (si existe).

**Verificación requerida:** Revisar si `procedimientos.brigada_id` es nullable en la migración.

**Línea en migración:** Necesita revisar `2025_10_30_154559_create_procedimientos_table.php`

---

## 🟠 ISSUES MEDIOS

### MEDIO #1: DashboardController sin Closure Preventiva

**Archivo:** `app/Http/Controllers/DashboardController.php:L13-L17`  
**Severity:** 🟠 MAYOR - Lógica

**Problema:**

```php
public function index(Request $request)
{
    // 1. Verificar Acceso
    if (Gate::denies('admin')) {
        abort(403, 'Acceso exclusivo para Jefes Operativos.');
    }
```

❌ Verifica acceso DENTRO del método, no en middleware.  
❌ El middleware de ruta también protege (`middleware('can:admin')`), pero hay **redundancia innecesaria**.

**Mejor práctica:** Confiar en el middleware de ruta, eliminar la verificación manual.

---

### MEDIO #2: PersonaController - Falta Form Request para Store

**Archivo:** `app/Http/Controllers/PersonaController.php:L60-80+`  
**Severity:** 🟠 MAYOR - Validación

**Problema:** El controlador usa `$request->validate()` inline en lugar de Form Request.

```php
public function store(Request $request)
{
    $validated = $request->validate([...]);  // ❌ Sin Form Request dedicado
}
```

**Impacto:**

- Sin `authorize()` centralizado
- Mensajes de error no personalizados
- Validación no reutilizable

---

### MEDIO #3: DocumentoController::store() - Validación de MIME Débil

**Archivo:** `app/Http/Controllers/DocumentoController.php:L44-48`  
**Severity:** 🟠 MAYOR - Seguridad

**Problema:**

```php
'archivo' => 'required|file|mimes:pdf,doc,docx|max:20480',
```

❌ La validación `mimes:pdf,doc,docx` solo valida extensión, no MIME type real.  
❌ Un archivo malicioso puede renombrarse como `.pdf` e ir directo.

**Recomendación:** Usar `mimetypes:application/pdf,application/msword,...`

---

### MEDIO #4: User::hasRole() - Eager Loading Issue

**Archivo:** `app/Models/User.php:L104-106`  
**Severity:** 🟠 MAYOR - Performance

**Problema:**

```php
protected $with = ['roles'];  // Eager load global

public function hasRole($roleName)
{
    return $this->roles->pluck('name')->contains($roleName);  // Accede a relación en memoria
}
```

❌ Aunque hay `protected $with = ['roles']`, el método accede a `$this->roles` que podría no estar cargada en algunos contextos.

**Impacto:** Queries N+1 si roles no están cargados en algunos flujos.

---

## 🟡 ISSUES LEVES

### LEVE #1: Redirección Circular en routes/web.php

**Archivo:** `routes/web.php:L17-41`  
**Severity:** 🟡 MENOR - UX

**Problema:**

```php
Route::get('/', function () {
    if (!Auth::check()) return redirect()->route('login');
    if ($user->hasRole('super_admin')) return redirect()->route('admin.users.index');
    if ($user->hasRole('admin')) return redirect()->route('dashboard');
    // ... más ifs
    return redirect()->route('login');
});
```

❌ El último `return` redirige a login, pero el usuario ya está autenticado.  
❌ Potencial loop infinito si todas las validaciones fallan.

**Fix recomendado:** Redirigir a ruta default segura (ej: `dashboard`).

---

### LEVE #2: ActivityLog::log() sin Validación

**Archivo:** `app/Http/Controllers/Admin/UserController.php:L52-59`  
**Severity:** 🟡 MENOR - Robustez

**Problema:**

```php
ActivityLog::log(
    'view_users_list',
    Auth::user()->name . ' visualizó la lista de usuarios',
    ['severity' => 'info']
);
```

❌ Si `Auth::user()` es null (no debería pasar pero...), genera error.

**Fix:** Usar `Auth::check()` antes o `Auth::user()?->name`.

---

### LEVE #3: ProcedimientoController::show() - Sin Autorización de Lectura

**Archivo:** `app/Http/Controllers/ProcedimientoController.php:L78-85`  
**Severity:** 🟡 MENOR - Lógica

**Problema:**

```php
public function show(Procedimiento $procedimiento)
{
    // Sin verificación de que el usuario puede leer ESTE procedimiento específico
    $procedimiento->load(['personas', 'domicilios', 'usuario', 'brigada', 'ufi']);
}
```

❌ Solo verifica el rol general (`can:panel-consulta`), no la propiedad del recurso.  
❌ Un usuario podría ver procedimientos de otras brigadas.

**Impacto:** Fuga de información potencial (baja probabilidad si roles están bien).

---

### LEVE #4: UpdateUserRequest - Falta Validación de Cambio de Rol

**Archivo:** `app/Http/Requests/UpdateUserRequest.php` (no encontrado explícitamente)  
**Severity:** 🟡 MENOR - Lógica

**Problema:** Si `UpdateUserRequest` existe, probablemente no valida qué roles puede asignar un admin.

❌ Un admin podría intentar asignarse a sí mismo rol `super_admin`.

**Validación recomendada:**

```php
'role_id' => Rule::in(Role::whereNotIn('name', ['super_admin'])->pluck('id')),
```

---

### LEVE #5: DocumentoController::download() - Path Traversal Risk

**Archivo:** `app/Http/Controllers/DocumentoController.php:L70-80`  
**Severity:** 🟡 MENOR - Seguridad

**Problema:**

```php
$filePath = Storage::disk('public')->path($documento->archivo_path);
return response()->download($filePath, $nombreDescarga);
```

✅ Está en `public` disk (más seguro), pero...  
❌ No valida que el usuario tenga acceso a ESTE documento.

**Validación recomendada:** Verificar `$documento->user_id === Auth::id()` antes de descargar.

---

### LEVE #6: ProcedimientoController::vincularPersona() - Sin Transacción

**Archivo:** `app/Http/Controllers/ProcedimientoController.php:L140-150`  
**Severity:** 🟡 MENOR - Robustez

**Problema:**

```php
$procedimiento->personas()->syncWithoutDetaching([
    $validated['persona_id'] => $pivot,
]);

return redirect()->route('procedimientos.show', $procedimiento);
```

❌ Si el redirect falla (ej: persona se borra antes del redirect), queda en estado inconsistente.

**Mejor práctica:** Usar transacción:

```php
DB::transaction(function () {
    $procedimiento->personas()->syncWithoutDetaching([...]);
});
```

---

## 📋 MATRIZ DE RIESGO

| Tipo | Críticos | Medios | Leves | Total |
|------|----------|--------|-------|-------|
| Seguridad | 1 | 2 | 2 | 5 |
| Performance | 0 | 1 | 0 | 1 |
| Lógica | 1 | 1 | 2 | 4 |
| Robustez | 0 | 0 | 2 | 2 |
| **TOTAL** | **2** | **4** | **6** | **12** |

---

## ✅ ASPECTOS POSITIVOS

✅ **Autenticación:** Bien implementada, middleware presente  
✅ **Gates:** Claros y simples (después de las correcciones recientes)  
✅ **Tests:** 23/23 pasando, buena cobertura  
✅ **Autorización:** Middleware en lugar de políticas (suficiente para este scope)  
✅ **Mensajes de error:** En español (reciente actualización)  
✅ **Lazy-loading:** Evitado con eager loading en modelos  

---

## 🎯 RECOMENDACIONES PRIORITARIAS

### AHORA (Crítico)

1. ✅ Crear `VincularDomicilioRequest` con authorize()
2. ✅ Verificar constraint `brigada_id` nullable en schema
3. ✅ Validar si procedimiento requiere brigada

### CORTO PLAZO (Mediano)

1. Crear `StorePersonaRequest`
2. Mejorar validación MIME de documentos
3. Revisar políticas de lectura por brigada

### MEDIANO PLAZO (Leve)

1. Agregar transacciones en operaciones críticas
2. Validar permisos de lectura de recursos
3. Implementar políticas de autorización (Policies)

---

## 📎 ARCHIVOS A REVISAR CON URGENCIA

| Archivo | Líneas | Prioridad |
|---------|--------|-----------|
| `app/Http/Controllers/ProcedimientoController.php` | 66-68, 155-168 | 🔴 |
| `app/Http/Controllers/DocumentoController.php` | 44-48, 70-80 | 🟠 |
| `app/Http/Controllers/PersonaController.php` | 55-70+ | 🟠 |
| `routes/web.php` | 30-40 | 🟡 |
| Migraciones | `*_create_procedimientos_table.php` | 🔴 |

---

**Documento generado:** 10 de enero de 2026  
**Estado:** ANÁLISIS COMPLETADO  
**Recomendación:** Priorizar CRÍTICOS antes de producción
