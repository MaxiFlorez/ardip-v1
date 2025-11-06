# ARDIP V1 - Documentación Sesión 4

## Tabla Personas y Relaciones Eloquent

**Fecha:** 5 de Noviembre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** 2.5 horas

---

## 📋 Resumen Ejecutivo

En esta sesión se creó la tabla más importante del sistema: **personas**, junto con las tablas pivote que conectan procedimientos con personas y domicilios. Se implementaron relaciones Eloquent many-to-many completamente funcionales.

**Logros:**

- ✅ Tabla personas con 10 campos + accessor de edad
- ✅ Tabla procedimiento_personas con situación procesal
- ✅ Tabla procedimiento_domicilios
- ✅ Relaciones Eloquent bidireccionales funcionando
- ✅ Sistema probado exitosamente en Tinker

---

## 🎯 Tabla PERSONAS

### Decisiones de Diseño

**Campos obligatorios vs opcionales:**

- DNI único (8 dígitos) - identificación principal
- Fecha nacimiento (edad calculada automáticamente)
- Género como ENUM para consistencia

**Simplificaciones realizadas:**

- Solo "alias" (se eliminó "apodo" - eran duplicados)
- Edad no se guarda (se calcula desde fecha_nacimiento)
- Una sola foto (se puede actualizar, reemplaza la anterior)
- No se guardan huellas (existe otro sistema)
- Sin lugar de nacimiento (no relevante)

---

### Estructura Final

**Comando:**

```bash
docker exec -it ardip-app php artisan make:migration create_personas_table
```

**Migración:**

```php
public function up(): void
{
    Schema::create('personas', function (Blueprint $table) {
        $table->id();
        
        // Campos OBLIGATORIOS
        $table->string('dni', 8)->unique();
        $table->string('nombres', 100);
        $table->string('apellidos', 100);
        $table->date('fecha_nacimiento');
        $table->enum('genero', ['masculino', 'femenino', 'otro']);
        
        // Campos OPCIONALES
        $table->string('alias', 100)->nullable();
        $table->string('nacionalidad', 50)->default('Argentina');
        $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'concubinato'])->nullable();
        $table->string('foto', 255)->nullable();
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}
```

---

### Modelo con Accessor

**Archivo:** `app/Models/Persona.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Persona extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];
    
    // Accessor para calcular edad automáticamente
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
    
    // Relación con procedimientos
    public function procedimientos()
    {
        return $this->belongsToMany(Procedimiento::class, 'procedimiento_personas')
                    ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                    ->withTimestamps();
    }
}
```

**¿Qué hace el Accessor?**

```php
$persona->edad  // → 32 (calculado desde fecha_nacimiento)
```

No se guarda la edad en BD, Laravel la calcula en tiempo real.

---

### Prueba en Tinker

```php
App\Models\Persona::create([
    'dni' => '40469578',
    'nombres' => 'FABRICIO GABRIEL',
    'apellidos' => 'BARTOLACCINI',
    'fecha_nacimiento' => '1993-05-15',
    'genero' => 'masculino',
    'alias' => 'EL GATO'
]);

$persona = App\Models\Persona::find(1);
$persona->edad  // → 32
```

**Resultado:** ✅ Persona creada y edad calculada correctamente

---

## 🔗 Tabla Pivote: PROCEDIMIENTO_PERSONAS

### Análisis de Requerimientos

**Problema inicial:** ¿Cómo registrar el estado de cada persona en un procedimiento?

**Opciones descartadas:**

- "fue_detenido" (boolean) - Muy limitado
- "arresto" vs "detención" - Se simplificó a solo "detención"

**Solución final:** Campo `situacion_procesal` con múltiples estados posibles.

---

### Estructura Finaal

**Comando:**

```bash
docker exec -it ardip-app php artisan make:migration create_procedimiento_personas_table
```

**Migración:**

```php
public function up(): void
{
    Schema::create('procedimiento_personas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('procedimiento_id')->constrained('procedimientos')->onDelete('cascade');
        $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
        $table->enum('situacion_procesal', ['detenido', 'notificado', 'no_hallado', 'contravencion']);
        $table->boolean('pedido_captura')->default(false);
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });
}
```

**Valores de `situacion_procesal`:**

- `detenido`: Arrestado en el procedimiento
- `notificado`: Solo se le notificó de la causa
- `no_hallado`: No estaba en el domicilio
- `contravencion`: Infracción menor

**Campo `pedido_captura`:**

- `true`: Queda prófugo por ESTA causa
- `false`: No tiene pedido de captura

---

## 🔗 Tabla Pivote: PROCEDIMIENTO_DOMICILIOS

Tabla simple para vincular procedimientos con domicilios allanados.

**Comando:**

```bash
docker exec -it ardip-app php artisan make:migration create_procedimiento_domicilios_table
```

**Migración:**

```php
public function up(): void
{
    Schema::create('procedimiento_domicilios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('procedimiento_id')->constrained('procedimientos')->onDelete('cascade');
        $table->foreignId('domicilio_id')->constrained('domicilios')->onDelete('cascade');
        $table->timestamps();
    });
}
```

**Propósito:** Un procedimiento puede allanar múltiples domicilios simultáneamente.

---

## 🔄 Relaciones Eloquent

### En Modelo Procedimiento

**Archivo:** `app/Models/Procedimiento.php`

```php
public function brigada()
{
    return $this->belongsTo(Brigada::class);
}

public function personas()
{
    return $this->belongsToMany(Persona::class, 'procedimiento_personas')
                ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                ->withTimestamps();
}

public function domicilios()
{
    return $this->belongsToMany(Domicilio::class, 'procedimiento_domicilios')
                ->withTimestamps();
}
```

---

### En Modelo Persona

**Archivo:** `app/Models/Persona.php`

```php
public function procedimientos()
{
    return $this->belongsToMany(Procedimiento::class, 'procedimiento_personas')
                ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                ->withTimestamps();
}
```

---

### ¿Qué hace `withPivot()`?

Permite acceder a campos adicionales de la tabla pivote:

```php
$persona = $procedimiento->personas[0];
$persona->pivot->situacion_procesal  // "detenido"
$persona->pivot->pedido_captura      // false
$persona->pivot->observaciones       // "Detenido en el domicilio..."
```

---

## 🧪 Prueba de Relaciones en Tinker

### Vincular persona a procedimiento

```php
$procedimiento = App\Models\Procedimiento::find(2);
$persona = App\Models\Persona::find(1);

$procedimiento->personas()->attach($persona->id, [
    'situacion_procesal' => 'detenido',
    'pedido_captura' => false,
    'observaciones' => 'Detenido en el domicilio allanado'
]);
```

### Verificar relación desde procedimiento

```php
$procedimiento->personas;
// Devuelve colección con BARTOLACCINI
```

### Verificar relación desde persona

```php
$persona->procedimientos;
// Devuelve colección con procedimiento MPF-SJ-28507-2025
```

### Ver datos del pivote

```php
$procedimiento->personas[0]->pivot;

// Resultado:
situacion_procesal: "detenido"
pedido_captura: 0 (false)
observaciones: "Detenido en el domicilio allanado"
created_at: "2025-11-05 12:57:33"
updated_at: "2025-11-05 12:57:33"
```

**Resultado:** ✅ Relaciones bidireccionales funcionando perfectamente

---

## 📊 Normalización de Datos

### Discusión sobre mayúsculas/minúsculas

**Problema identificado:**
Diferentes usuarios pueden escribir de formas distintas:

- "Fabricio Gabriel" (primera mayúscula)
- "FABRICIO GABRIEL" (todo mayúsculas)
- "fabricio gabriel" (todo minúsculas)

**Solución propuesta:**
Normalización automática con mutadores en el modelo (se implementará después).

**Decisión para V1:**
Postergado para después del 14/11. Por ahora se acepta cualquier formato.

---

## 📝 Pendientes para V2

### 1. Tabla Barrios (Identificada en esta sesión)

**Contexto:** Se tiene archivo CSV con ~100+ barrios de San Juan.

**Decisión:** Dejarlo para V2 por falta de tiempo.

**Estructura propuesta:**

```php
barrios:
- id
- nombre
- departamento
- localidad
- created_at
- updated_at
```

**Cambio requerido en domicilios:**

```php
// De:
$table->string('barrio')->nullable();

// A:
$table->foreignId('barrio_id')->nullable()->constrained('barrios');
```

---

### 2. Mutadores de Normalización

**Pendiente:** Implementar en modelos para normalizar automáticamente:

```php
// Nombres/Apellidos → Primera mayúscula
protected function setNombresAttribute($value)
{
    $this->attributes['nombres'] = ucwords(strtolower($value));
}

// Alias → Todo mayúsculas
protected function setAliasAttribute($value)
{
    $this->attributes['alias'] = strtoupper($value);
}

// DNI → Solo números
protected function setDniAttribute($value)
{
    $this->attributes['dni'] = preg_replace('/[^0-9]/', '', $value);
}
```

**Se implementará:** Después de completar todos los CRUDs del frontend.

---

## 🔧 Comandos Ejecutados

```bash
# Personas
docker exec -it ardip-app php artisan make:migration create_personas_table
docker exec -it ardip-app php artisan migrate
docker exec -it ardip-app php artisan make:model Persona

# Pivotes
docker exec -it ardip-app php artisan make:migration create_procedimiento_personas_table
docker exec -it ardip-app php artisan make:migration create_procedimiento_domicilios_table
docker exec -it ardip-app php artisan migrate

# Pruebas
docker exec -it ardip-app php artisan tinker
```

---

## 📈 Progreso del Proyecto

### ✅ Backend Completado (70%)

```
brigadas
├── Migración ✅
├── Modelo ✅
├── Seeder ✅
└── Relaciones ✅

domicilios
├── Migración ✅
├── Modelo ✅
├── Seeder ✅
└── Relaciones ✅

procedimientos
├── Migración ✅
├── Modelo ✅
├── Relaciones ✅ (brigada, personas, domicilios)
└── Prueba ✅

personas
├── Migración ✅
├── Modelo ✅
├── Accessor edad ✅
└── Relaciones ✅ (procedimientos)

procedimiento_personas (pivote)
├── Migración ✅
└── Funcionando ✅

procedimiento_domicilios (pivote)
├── Migración ✅
└── Funcionando ✅
```

### ❌ Backend Pendiente (30%)

```text
caracteristicas_fisicas → V2
Sistema usuarios/roles → Próxima sesión
Validaciones/Mutadores → Después del frontend
Seeders de prueba → Después del frontend
```

### ❌ Frontend (0%)

```text
Autenticación → Sesión 5 (mañana)
CRUD Personas → Sesión 6-7
CRUD Procedimientos → Sesión 8-9
Dashboard → Sesión 10
Testing → Sesión 11
```

---

## 🗓️ Calendario Actualizado para 14/11

### Quedan 8 días

### Días 6-7 Nov (Mañana): Autenticación

- Laravel Breeze (instalación rápida)
- Login/Logout
- Usuarios de prueba

### Días 7-8 Nov: CRUD Personas

- Listado con búsqueda
- Formulario crear/editar
- Upload foto básico

### Días 9-10 Nov: CRUD Procedimientos

- Formulario simplificado
- Vincular personas/domicilios
- Resultados

### Días 11-12 Nov: Dashboard + CRUD Domicilios

- Estadísticas básicas
- CRUD domicilios simple
- Búsquedas

### Días 13 Nov: Testing

- Probar flujo completo
- Corregir bugs críticos

### Día 14 Nov: Demo

- Preparación final
- Presentación al D-5

---

## 🎓 Conceptos Aprendidos

### 1. Relaciones Many-to-Many

**Escenario:**

- Un procedimiento → múltiples personas
- Una persona → múltiples procedimientos

**Solución:** Tabla pivote con datos adicionales

**Ventajas:**

- Flexibilidad total
- Datos adicionales en el pivote (situación_procesal)
- Queries eficientes

---

### 2. Accessors en Eloquent

**¿Qué son?**
Métodos que calculan valores dinámicamente sin guardarlos en BD.

**Ejemplo:**

```php
public function getEdadAttribute()
{
    return Carbon::parse($this->fecha_nacimiento)->age;
}
```

**Ventaja:** Siempre actualizado, no hay que recalcular manualmente.

---

### 3. Constraints en Foreign Keys

```php
->onDelete('cascade')
```

**¿Qué significa?**

- Si borrás un procedimiento, se borran automáticamente sus relaciones en las tablas pivote
- Mantiene integridad referencial
- Evita registros huérfanos

**Alternativas:**

- `restrict`: No permite borrar (lo usamos en brigadas/usuarios)
- `set null`: Pone NULL en FK
- `cascade`: Borra en cascada (lo usamos en pivotes)

---

### 4. withPivot() en Relaciones

Permite acceder a campos adicionales de la tabla pivote:

```php
->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
```

Sin esto, solo tendrías acceso a los IDs.

---

## 💡 Decisiones de Arquitectura

### ¿Por qué no guardar edad?

**Opción A:** Guardar fecha_nacimiento + edad

- Problema: Hay que actualizar edad cada año

**Opción B:** Solo fecha_nacimiento + accessor ✅

- Ventaja: Siempre correcto automáticamente
- Laravel calcula en tiempo real

---

### ¿Por qué enum en situacion_procesal?

**Opción A:** String libre

- Problema: "detenido" vs "Detenido" vs "DETENIDO"

**Opción B:** Enum con valores fijos ✅

- Ventaja: Solo permite valores específicos
- MySQL valida en nivel de BD
- Más eficiente en espacio

---

### ¿Por qué onDelete('cascade') en pivotes?

**Lógica:** Si borrás un procedimiento, no tiene sentido mantener sus relaciones.

**Ejemplo:**

- Borrás procedimiento MPF-SJ-28507-2025
- Se borran automáticamente sus vínculos en procedimiento_personas
- Las personas siguen existiendo (no se borran)

---

## ✅ Checklist de Verificación

Antes de la próxima sesión:

**Backend:**

- [x] Tabla personas funcionando
- [x] Accessor de edad calculando correctamente
- [x] Relación procedimientos ↔ personas bidireccional
- [x] Tabla pivote con datos adicionales
- [x] Prueba exitosa en Tinker

**Pendientes anotados:**

- [ ] Tabla barrios (V2)
- [ ] Mutadores de normalización (después del frontend)
- [ ] Validaciones (después del frontend)

**Docker:**

- [ ] Contenedores corriendo
- [ ] Base de datos con datos de prueba

---

## 📊 Estadísticas de la Sesión

**Tiempo:** 2.5 horas  
**Tablas creadas:** 3 (personas + 2 pivotes)  
**Modelos creados:** 1 (Persona)  
**Relaciones configuradas:** 4 (bidireccionales)  
**Migraciones ejecutadas:** 3  
**Líneas de código:** ~150  
**Decisiones de diseño:** 5 importantes  

---

## 🎯 Sistema al 70% del Backend

**Funcionalidad actual:**

- ✅ Registrar brigadas
- ✅ Registrar domicilios
- ✅ Registrar personas
- ✅ Registrar procedimientos
- ✅ Vincular personas a procedimientos con estado
- ✅ Vincular domicilios a procedimientos
- ✅ Calcular edad automáticamente
- ✅ Consultar historial de persona
- ✅ Consultar personas en un procedimiento

**Lo que falta:**

- ❌ Interfaz visual (frontend)
- ❌ Sistema de login
- ❌ Búsquedas avanzadas UI
- ❌ Upload de fotos UI

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1 - Tecnicatura Superior en Desarrollo de Software  
**Fecha:** 5 de Noviembre, 2025  
**Sesión:** 4 de N

---

## FIN DEL DOCUMENTO - SESIÓN 4

**Próxima sesión:** Autenticación con Laravel Breeze (1.5-2h)  
**Objetivo:** Sistema de login funcionando para acceder al sistema
