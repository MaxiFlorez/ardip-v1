# ARDIP V1 - Documentación Sesión 3

## Tablas Domicilios y Procedimientos

**Fecha:** 30 de Octubre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** 3 horas

---

## 📋 Resumen Ejecutivo

En esta sesión se crearon dos tablas fundamentales del sistema: `domicilios` y `procedimientos`. El diseño de procedimientos se basó en documentos judiciales reales (órdenes de allanamiento) del Poder Judicial de San Juan, asegurando que la estructura refleje el flujo de trabajo real de las brigadas.

**Logros:**

- ✅ Tabla domicilios con 14 campos flexibles
- ✅ Tabla procedimientos con lógica policial completa
- ✅ Identificación de tabla `caracteristicas_fisicas` (pendiente)
- ✅ Diseño de flujo de carga de procedimientos

---

## 🎯 Tabla 1: DOMICILIOS

### Análisis de Requerimientos

**Problema identificado:** Los domicilios en San Juan tienen múltiples formatos:

- Casas normales: "Calle San Martín 1234"
- Departamentos: "Av. Libertador 567, Piso 3, Dpto B"
- Monoblocks: "Monoblock 14 Eva Perón, Torre C, Piso 5, Dpto 8"
- Lotes en barrios: "Manzana 12, Lote 7, Casa 15, Villa Hipódromo"
- Zona rural: "Ruta 40, Km 23"

**Decisión de diseño:** Campos separados pero todos opcionales (excepto localidad/provincia), permitiendo flexibilidad total.

---

### Estructura Final: DOMICILIOS

**Comando de creación:**

```bash
docker exec -it ardip-app php artisan make:migration create_domicilios_table
```

**Migración:**

```php
public function up(): void
{
    Schema::create('domicilios', function (Blueprint $table) {
        $table->id();
        
        // Campos OBLIGATORIOS
        $table->string('departamento', 100); // Rawson, Capital, Chimbas, etc.
        $table->string('provincia', 100)->default('San Juan');
        
        // Campos OPCIONALES
        $table->string('calle', 255)->nullable();
        $table->string('numero', 20)->nullable();
        $table->string('piso', 10)->nullable();
        $table->string('depto', 10)->nullable();
        $table->string('torre', 10)->nullable();
        $table->string('monoblock', 100)->nullable();
        $table->string('manzana', 20)->nullable();
        $table->string('lote', 20)->nullable();
        $table->string('casa', 20)->nullable();
        $table->string('barrio', 100)->nullable();
        $table->string('sector', 100)->nullable();
        $table->string('coordenadas_gps', 100)->nullable();
        
        $table->timestamps();
    });
}
```

**Modelo:**

```php
class Domicilio extends Model
{
    protected $guarded = ['id'];
}
```

**¿Por qué `$guarded` y no `$fillable`?**

- No hay campos sensibles en domicilios (no hay contraseñas, permisos, etc.)
- Más simple: protege solo el `id`, permite todo lo demás

---

### Seeder de Prueba

**Comando:**

```bash
docker exec -it ardip-app php artisan make:seeder DomicilioSeeder
```

**Contenido:**

```php
public function run(): void
{
    $domicilios = [
        // Casa normal
        [
            'departamento' => 'Capital',
            'calle' => 'San Martín',
            'numero' => '1234',
            'barrio' => 'Centro',
        ],
        
        // Departamento
        [
            'departamento' => 'Rawson',
            'calle' => 'Av. Libertador',
            'numero' => '567',
            'piso' => '3',
            'depto' => 'B',
            'barrio' => 'Centro',
        ],
        
        // Monoblock
        [
            'departamento' => 'Chimbas',
            'monoblock' => '14 Eva Perón',
            'torre' => 'C',
            'piso' => '5',
            'depto' => '8',
            'barrio' => 'Huarpes',
        ],
        
        // Lote en barrio
        [
            'departamento' => 'Pocito',
            'manzana' => '12',
            'lote' => '7',
            'casa' => '15',
            'barrio' => 'Villa Hipódromo',
        ],
        
        // Zona rural
        [
            'departamento' => 'Sarmiento',
            'calle' => 'Ruta 40',
            'numero' => 'Km 23',
            'sector' => 'Rural',
        ],
    ];

    foreach ($domicilios as $domicilio) {
        Domicilio::create($domicilio);
    }
}
```

**Ejecución:**

```bash
docker exec -it ardip-app php artisan db:seed --class=DomicilioSeeder
```

**Resultado:** 7 domicilios en total (2 de prueba manual + 5 del seeder)

---

## 🎯 Tabla 2: PROCEDIMIENTOS

### Análisis Basado en Documentos Reales

Se analizaron documentos judiciales reales:

1. **Orden de Allanamiento** (emitida por el Colegio de Jueces)
2. **Parte de Allanamiento** (ejecutado por la brigada)

**Información clave extraída:**

**De la Orden Judicial:**

- Número fiscal: `MPF-SJ-28507-2025`
- Carátula completa del caso
- UFI interviniente: "UFI Delitos contra la Propiedad"
- Fiscal del caso: Dr. GERARDUZZI CRISTIAN
- Fecha orden: 20/10/2025
- Tipos de orden otorgada: Allanamiento + Secuestro + Detención

**Del Parte de Allanamiento:**

- Fecha ejecución: 22/10/2025
- Brigada ejecutora: Central D-5
- Ayudante fiscal presente: LAFONT RODRIGO
- Resultados: Detención positiva, Secuestro parcial

---

### Decisiones de Diseño

**Campos eliminados (simplificación):**

- ❌ Nombre del fiscal (no necesario para el registro)
- ❌ Ayudante fiscal (no necesario)
- ❌ Detalle exhaustivo de secuestros (se hace general)

**Campos clave identificados:**

**Órdenes otorgadas (checkboxes):**

- Allanamiento (siempre true)
- Secuestro (true/false según orden)
- Detención (true/false según orden)

**Resultados (solo si se otorgó la orden):**

- `positivo`: Se logró el objetivo
- `negativo`: No se logró el objetivo
- `no_aplica`: No se otorgó esa orden

**Lógica:** Si no te dieron orden de detención, el resultado es "no_aplica".

---

### Estructura Final

**Comando:**

```bash
docker exec -it ardip-app php artisan make:migration create_procedimientos_table
```

**Migración:**

```php
public function up(): void
{
    Schema::create('procedimientos', function (Blueprint $table) {
        $table->id();
        
        // Identificación
        $table->string('legajo_fiscal', 50);
        $table->text('caratula');
        
        // Fecha y hora
        $table->date('fecha_procedimiento');
        $table->time('hora_procedimiento')->nullable();
        
        // UFI y Brigada
        $table->string('ufi', 100)->default('UFI Delitos contra la Propiedad');
        $table->foreignId('brigada_id')->constrained('brigadas')->onDelete('restrict');
        $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
        
        // Órdenes otorgadas
        $table->boolean('orden_allanamiento')->default(true);
        $table->boolean('orden_secuestro')->default(false);
        $table->boolean('orden_detencion')->default(false);
        
        // Resultados
        $table->enum('resultado_secuestro', ['positivo', 'negativo', 'no_aplica'])->default('no_aplica');
        $table->enum('resultado_detencion', ['positivo', 'negativo', 'no_aplica'])->default('no_aplica');
        $table->text('secuestro_detalle')->nullable();
        
        // Observaciones
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}
```

**Modelo:**

```php
class Procedimiento extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'fecha_procedimiento' => 'date',
        'hora_procedimiento' => 'datetime:H:i',
        'orden_allanamiento' => 'boolean',
        'orden_secuestro' => 'boolean',
        'orden_detencion' => 'boolean',
    ];
}
```

**¿Qué hace `$casts`?**
Convierte tipos de datos automáticamente:

- Fechas → Objetos Carbon (fácil manipulación)
- Booleanos → `true/false` en lugar de 1/0
- Mejora la legibilidad del código

**¿Qué hace `->onDelete('restrict')`?**
Protección de integridad: NO permite borrar una brigada si tiene procedimientos asociados.

---

### Prueba en Tinker

**Creación de usuario de prueba (necesario para FK):**

```php
\DB::table('users')->insert([
    'name' => 'Admin Test',
    'email' => 'admin@ardip.test',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now()
]);
```

**Creación de procedimiento de prueba:**

```php
App\Models\Procedimiento::create([
    'legajo_fiscal' => 'MPF-SJ-28507-2025',
    'caratula' => 'C/ BARTOLACCINI FABRICIO GABRIEL S/ HURTO',
    'fecha_procedimiento' => '2025-10-22',
    'hora_procedimiento' => '14:30',
    'brigada_id' => 1,
    'usuario_id' => 1,
    'orden_secuestro' => true,
    'orden_detencion' => true,
    'resultado_secuestro' => 'positivo',
    'resultado_detencion' => 'positivo',
    'secuestro_detalle' => 'Prendas de vestir utilizadas en el hecho'
]);
```

**Resultado:** ✅ Procedimiento guardado exitosamente

---

## 🔗 Tablas Relacionadas Identificadas

### 1. `procedimiento_domicilios` (pivote)

**Propósito:** Un procedimiento puede allanar múltiples domicilios.

```php
- id
- procedimiento_id (FK)
- domicilio_id (FK)
- created_at
- updated_at
```

**Ejemplo:** Procedimiento MPF-SJ-28507-2025 allanó 3 domicilios diferentes.

---

### 2. `procedimiento_personas` (pivote)

**Propósito:** Un procedimiento puede involucrar múltiples personas acusadas.

```php
- id
- procedimiento_id (FK)
- persona_id (FK)
- fue_detenido (BOOLEAN)
- pedido_captura (BOOLEAN)
- archivo_pedido_captura (VARCHAR, NULLABLE) - futuro
- observaciones (TEXT, NULLABLE)
- created_at
- updated_at
```

**Casos de uso:**

#### Caso 1: Detenido en el lugar

```text
fue_detenido: true
pedido_captura: false
```

#### Caso 2: No estaba, queda prófugo

```text
fue_detenido: false
pedido_captura: true
```

#### Caso 3: Se presentó voluntariamente después

```text
fue_detenido: false
pedido_captura: false
observaciones: "Se presentó el 25/10"
```

---

## 📊 Flujo de Carga de un Procedimiento Completo

### Pantalla 1: Datos Generales

```text
- Legajo fiscal: MPF-SJ-28507-2025
- Carátula: [TEXT LARGO]
- Fecha: 22/10/2025
- Hora: 14:30
- Brigada: [SELECT]
```

### Pantalla 2: Órdenes Otorgadas

```text
☑ Allanamiento (siempre)
☑ Secuestro
☑ Detención
```

### Pantalla 3: Personas Acusadas

```text
Buscar persona existente o crear nueva
→ BARTOLACCINI FABRICIO GABRIEL
    ☑ Fue detenido
    ☐ Pedido de captura
   
[+ Agregar otra persona]
```

### Pantalla 4: Domicilios Allanados

```text
Buscar domicilio existente o crear nuevo
→ Barrio Aoma, Mza B, Casa 18, Santa Lucía

[+ Agregar otro domicilio]
```

### Pantalla 5: Resultados

```text
Secuestro: ○ Positivo ○ Negativo ○ No aplica
Detalle: "Prendas de vestir, celular Samsung"

Detención: ○ Positivo ○ Negativo ○ No aplica

Observaciones: [TEXTAREA]
```

**Orden de guardado:**

1. Guardar procedimiento → obtener `procedimiento_id`
2. Guardar relaciones en `procedimiento_personas`
3. Guardar relaciones en `procedimiento_domicilios`

---

## 🆕 Tabla Identificada: CARACTERÍSTICAS FÍSICAS

**Necesidad detectada:** Registrar señas particulares para identificación policial.

**Propuesta de estructura:**

```php
caracteristicas_fisicas:
- id
- persona_id (FK)
- tipo (tatuaje, cicatriz, lunar, marca_nacimiento, quemadura)
- ubicacion (cara, cuello, brazo_izq, brazo_der, pierna_izq, pierna_der, 
             mano_izq, mano_der, torso, espalda)
- descripcion (TEXT) - "Tatuaje de dragón azul de 10cm"
- foto (VARCHAR, NULLABLE) - Ruta de imagen
- created_at
- updated_at
```

**Ventajas de tabla separada:**

- Persona sin características → 0 registros
- Persona con 10 características → 10 registros
- Filtros potentes: "Buscar todas las personas con tatuajes en brazo derecho"
- Fotos específicas de cada característica

**Pendiente:** Crear en próxima sesión (después de tabla `personas`)

---

## 📈 Progreso del Proyecto

### ✅ Backend Completado

```text
brigadas
├── Migración ✅
├── Modelo ✅
└── Seeder ✅ (8 brigadas reales)

domicilios
├── Migración ✅ (14 campos)
├── Modelo ✅
└── Seeder ✅ (5 casos variados)

procedimientos
├── Migración ✅ (17 campos)
├── Modelo ✅
└── Prueba ✅ (1 procedimiento real)
```

### ❌ Backend Pendiente

```text
personas (PRÓXIMA - LA MÁS IMPORTANTE)
├── DNI, nombres, apellidos, alias, apodo
├── Foto
└── Relaciones con domicilios y procedimientos

caracteristicas_fisicas
└── Después de personas

procedimiento_personas (pivote)
└── Después de personas

procedimiento_domicilios (pivote)
└── Rápida, después de procedimientos

Sistema de usuarios/roles
└── Adaptar tabla users existente
```

### ❌ Frontend

```text
Todo pendiente (se hará al final)
```

---

## 🎓 Conceptos Aprendidos

### 1. Diseño Flexible de Estructuras

**Problema:** Domicilios con formatos muy variados  
**Solución:** Campos separados pero todos opcionales (nullable)

**Lección:** A veces es mejor tener muchos campos opcionales que intentar forzar todo en un solo campo de texto.

---

### 2. Foreign Keys con Restricciones

```php
$table->foreignId('brigada_id')
      ->constrained('brigadas')
      ->onDelete('restrict');
```

**¿Qué protege?**

- No podés borrar una brigada con procedimientos asociados
- Mantiene integridad referencial
- Evita datos huérfanos

**Alternativas:**

- `cascade`: Borra procedimientos si borrás la brigada (peligroso)
- `set null`: Pone NULL si borrás la brigada (pierde información)
- `restrict`: NO permite borrar (lo más seguro)

---

### 3. Enums para Valores Predefinidos

```php
$table->enum('resultado_secuestro', ['positivo', 'negativo', 'no_aplica']);
```

**Ventajas:**

- Solo permite esos 3 valores (validación en BD)
- Más eficiente que VARCHAR
- Auto-documentación del código

**Cuándo usar ENUM:**

- Lista cerrada de opciones
- No va a cambiar frecuentemente
- Pocos valores (2-10)

---

### 4. Casts en Modelos

```php
protected $casts = [
    'fecha_procedimiento' => 'date',
    'orden_allanamiento' => 'boolean',
];
```

**¿Qué hace?**

- Laravel guarda todo como texto en MySQL
- `$casts` convierte automáticamente al leer/escribir
- Fechas → Objetos Carbon (métodos útiles)
- Booleanos → true/false (no "1"/"0")

**Beneficio:** Código más limpio y fácil de usar.

---

### 5. Tablas Pivote (Muchos a Muchos)

**Escenario:** Un procedimiento → muchas personas, Una persona → muchos procedimientos

**Solución:** Tabla intermedia `procedimiento_personas`

```text
procedimientos     procedimiento_personas     personas
─────────────      ──────────────────────     ────────
id: 1              proc_id: 1, pers_id: 5     id: 5
legajo: MPF-..     proc_id: 1, pers_id: 8     dni: 40469578
                   proc_id: 2, pers_id: 5     nombre: FABRICIO
```

**Ventaja:** Flexibilidad total, datos normalizados.

---

## 🔧 Comandos Ejecutados en Esta Sesión

```bash
# Domicilios
docker exec -it ardip-app php artisan make:migration create_domicilios_table
docker exec -it ardip-app php artisan migrate
docker exec -it ardip-app php artisan make:model Domicilio
docker exec -it ardip-app php artisan make:seeder DomicilioSeeder
docker exec -it ardip-app php artisan db:seed --class=DomicilioSeeder

# Procedimientos
docker exec -it ardip-app php artisan make:migration create_procedimientos_table
docker exec -it ardip-app php artisan migrate
docker exec -it ardip-app php artisan make:model Procedimiento

# Verificación
docker exec -it ardip-app php artisan tinker
```

---

## 📝 Notas Importantes

### Sobre Seeders

**¿Por qué NO creamos seeder para procedimientos?**

- Los procedimientos son datos reales del trabajo policial
- No tiene sentido crear datos ficticios
- Se cargarán cuando los brigadistas usen el sistema

**Seeders solo para:**

- Datos de catálogo (brigadas)
- Datos de prueba (domicilios de testing)
- Datos iniciales del sistema (roles, permisos)

---

### Sobre la Decisión de Backend Primero

**Decisión tomada:** Completar todas las tablas y modelos antes del frontend.

**Ventajas:**

- Base de datos sólida sin cambios posteriores
- Relaciones claras desde el inicio
- Frontend solo conecta con backend ya funcional

**Orden sugerido:**

1. ✅ brigadas, domicilios, procedimientos
2. → personas (próxima)
3. → caracteristicas_fisicas
4. → tablas pivote
5. → Frontend completo

---

## 🚀 Próxima Sesión: Tabla PERSONAS

La tabla más importante y compleja del sistema:

**Campos principales:**

- DNI (único)
- Nombres, apellidos
- Alias, apodo
- Fecha nacimiento, edad
- Nacionalidad, estado civil
- Foto (manejo de archivos)

**Relaciones:**

- Con domicilios (1 persona → muchos domicilios históricos)
- Con procedimientos (1 persona → muchos procedimientos)
- Con características físicas (1 persona → muchas características)

**Desafíos:**

- Manejo de uploads de fotos
- Validación de DNI único
- Cálculo automático de edad desde fecha nacimiento

---

## ✅ Checklist de Verificación

Antes de la próxima sesión:

**Docker:**

- [ ] Docker Desktop corriendo
- [ ] 3 contenedores activos
- [ ] `http://localhost:8000` funciona

**Base de Datos:**

- [ ] Tabla brigadas: 10 registros
- [ ] Tabla domicilios: 7 registros
- [ ] Tabla procedimientos: 1-2 registros de prueba

**Archivos:**

- [ ] `Domicilio.php` con `$guarded`
- [ ] `Procedimiento.php` con `$guarded` y `$casts`
- [ ] Seeders funcionando sin duplicados

---

## 📊 Estadísticas de la Sesión

**Tiempo total:** 3 horas  
**Tablas creadas:** 2 (domicilios, procedimientos)  
**Modelos creados:** 2  
**Seeders creados:** 1  
**Migraciones ejecutadas:** 2  
**Líneas de código:** ~200  
**Documentos judiciales analizados:** 2  
**Tablas identificadas para futuro:** 3 (pivotes + características)

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1 - Tecnicatura Superior en Desarrollo de Software  
**Fecha:** 30 de Octubre, 2025  
**Sesión:** 3 de N

---
