# ARDIP V1 - Documentación Sesión 2

## Migraciones, Modelos Eloquent y Seeders

**Fecha:** 19 de Octubre, 2025  
**Alumno:** Flores, Maximiliano  
**Proyecto:** Sistema ARDIP V1 - Dirección D-5, Policía de San Juan  
**Duración:** 1 hora 45 minutos

---

## 📋 Resumen Ejecutivo

En esta segunda sesión se creó la primera tabla completa del sistema ARDIP (brigadas) implementando el ciclo completo:

- Migración (estructura de la tabla)
- Modelo Eloquent (lógica de negocio)
- Seeder (datos iniciales)

Se cargaron las 8 brigadas reales de la Policía de San Juan y se implementó protección contra duplicados.

**Estado Final:** 10 brigadas en base de datos funcionando con Eloquent ✅

---

## 🎯 Objetivos Cumplidos

- [x] Dominar el flujo de trabajo con Docker
- [x] Crear primera migración personalizada (brigadas)
- [x] Crear primer modelo Eloquent (Brigada)
- [x] Entender diferencia entre SQL crudo y Eloquent
- [x] Configurar $fillable para seguridad
- [x] Crear primer Seeder con datos reales
- [x] Implementar lógica para evitar duplicados
- [x] Practicar CRUD completo con Tinker

---

## 🔄 Verificación Inicial - Ejercicio de Repaso

### Objetivo del Ejercicio

Verificar que recordaba conceptos de la sesión anterior y que Docker funcionara correctamente.

### Comandos Ejecutados

**1. Verificar estado de contenedores:**

```bash
docker ps
```

**Resultado inicial:** Error

```text
error during connect: ... open //./pipe/dockerDesktopLinuxEngine: 
El sistema no puede encontrar el archivo especificado.
```

**Análisis del error:**

- Docker Desktop no estaba corriendo
- Los comandos de Docker requieren que Docker Desktop esté activo
- Similar a XAMPP: necesita estar iniciado para funcionar

**2. Solución:**

- Abrir Docker Desktop desde el menú inicio
- Esperar a que inicie completamente
- Verificar ícono de ballena en barra de tareas

**3. Verificación exitosa:**

```bash
docker ps
```

**Resultado:**

```text
CONTAINER ID   IMAGE          STATUS              NAMES
768a3be05807   mysql:8.0      Up About a minute   ardip-mysql
73e9ab683c64   nginx:alpine   Up About a minute   ardip-nginx
8d4c305c82e3   ardip-app      Up About a minute   ardip-app
```

**Observación importante:**

- `CREATED: 3 days ago` → Contenedores creados hace 3 días
- `STATUS: Up About a minute` → Recién iniciados
- **Conclusión:** Los contenedores se apagaron al cerrar Docker Desktop y se reiniciaron automáticamente al abrirlo

---

## 🔄 Práctica: Controlar Contenedores Manualmente

### Apagar contenedores

```bash
docker-compose down
```

**¿Qué hace este comando?**

- Detiene los 3 contenedores
- Los elimina (no destruye las imágenes ni los datos)
- Libera los puertos (8000, 3306)

**Verificación:**

```bash
docker ps
```

Resultado: Lista vacía (no hay contenedores corriendo)

---

### Levantar contenedores nuevamente

```bash
docker-compose up -d
```

**¿Por qué fue más rápido esta vez?**

- Primera vez (Sesión 1): ~4 minutos (descargar imágenes, construir)
- Segunda vez: ~10 segundos (todo ya está en cache)
- El flag `-d` significa "detached" (en segundo plano)

**Verificación:**

```bash
docker ps
```

Resultado: 3 contenedores corriendo ✅

**Prueba final:**
Navegador → `http://localhost:8000` → Página de Laravel funcionando ✅

---

## 🗄️ Primera Migración: Tabla Brigadas

### Paso 1: Crear archivo de migración

**Comando:**

```bash
docker exec -it ardip-app php artisan make:migration create_brigadas_table
```

**Análisis del comando:**

- `docker exec -it ardip-app` → Ejecutar dentro del contenedor
- `php artisan make:migration` → Comando de Laravel para crear migración
- `create_brigadas_table` → Nombre descriptivo (Laravel detecta "brigadas")

**Resultado:**

```text
INFO  Migration [database/migrations/2025_10_19_170147_create_brigadas_table.php] created successfully.
```

**Observación del nombre del archivo:**

- `2025_10_19_170147` → Timestamp (año_mes_día_hora_minuto_segundo)
- `create_brigadas_table` → Nombre descriptivo
- **.php** → Es código PHP, no SQL

**¿Por qué el timestamp?**
Laravel ejecuta migraciones en orden cronológico. El timestamp asegura que se ejecuten en el orden correcto.

---

### Paso 2: Editar la migración

**Ubicación:** `database/migrations/2025_10_19_170147_create_brigadas_table.php`

**Código inicial generado:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brigadas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brigadas');
    }
};
```

**Análisis del código:**

- `up()` → Se ejecuta al hacer `php artisan migrate` (crear tabla)
- `down()` → Se ejecuta al hacer `php artisan migrate:rollback` (eliminar tabla)
- `$table->id()` → Crea columna `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `$table->timestamps()` → Crea `created_at` y `updated_at` (TIMESTAMP)

**¿Qué faltaba según la documentación?**
La tabla `brigadas` necesita:

- ✅ id (ya estaba)
- ❌ nombre (falta agregar)
- ✅ timestamps (ya estaba)

---

**Código modificado (agregada línea):**

```php
public function up(): void
{
    Schema::create('brigadas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');  // ← AGREGADA
        $table->timestamps();
    });
}
```

**¿Qué hace `$table->string('nombre')`?**

- Crea columna VARCHAR(255) llamada `nombre`
- Equivalente SQL: `nombre VARCHAR(255)`

**¿Por qué `string` y no `text`?**

- `string` → Hasta 255 caracteres (para textos cortos como nombres)
- `text` → Miles de caracteres (para textos largos como descripciones)
- "Brigada Central" tiene 15 caracteres → `string` es suficiente

---

### Paso 3: Ejecutar la migración

**Comando:**

```bash
docker exec -it ardip-app php artisan migrate
```

**Resultado:**

```text
INFO  Running migrations.
2025_10_19_170147_create_brigadas_table ........... 383.05ms DONE
```

**Observación importante:**

- Solo ejecutó la migración NUEVA (brigadas)
- NO volvió a ejecutar las 3 anteriores (users, cache, jobs)
- Laravel usa la tabla `migrations` para trackear qué ya ejecutó

**¿Cómo sabe Laravel qué migraciones ya ejecutó?**
Hay una tabla especial llamada `migrations` que registra:

- Nombre de cada migración ejecutada
- Fecha/hora de ejecución
- Número de lote (batch)

---

### Paso 4: Verificar que la tabla se creó

**Herramienta:** Laravel Tinker (REPL interactivo)

```bash
docker exec -it ardip-app php artisan tinker
```

**¿Qué es Tinker?**

- REPL = Read-Eval-Print Loop
- Consola interactiva de PHP/Laravel
- Permite ejecutar código PHP y ver resultados inmediatamente
- Útil para testing y debugging

**Comando en Tinker:**

```php
\DB::table('brigadas')->get();
```

**Resultado:**

```php
= Illuminate\Support\Collection {
    all: [],  // ← Array vacío
}
```

**Interpretación:**

- La tabla `brigadas` existe ✅
- Pero está vacía (sin registros) ✅
- Estructura creada correctamente

---

## 🎨 Primer Modelo Eloquent: Brigada

### ¿Qué es un Modelo en Laravel?

**Concepto en POO:**

- **Migración** = Estructura de la tabla (CREATE TABLE)
- **Modelo** = Clase PHP que representa los datos de esa tabla

**Analogía:**

- Migración = Plano arquitectónico de una casa
- Modelo = Objeto Casa que usás en tu código

---

### Paso 1: Crear el modelo

**Comando:**

```bash
docker exec -it ardip-app php artisan make:model Brigada
```

**Resultado:**

```text
INFO  Model [app/Models/Brigada.php] created successfully.
```

**Convención importante:**

- Tabla en BD: `brigadas` (plural, minúscula)
- Modelo (clase): `Brigada` (singular, PascalCase)
- Laravel conecta automáticamente `Brigada` → `brigadas`

**¿Por qué singular en el Modelo?**

```php
$brigada = new Brigada();  // Una brigada (objeto singular)
```

Representa UNA fila de la tabla. La tabla tiene muchas brigadas (plural).

---

### Paso 2: Contenido inicial del modelo

**Ubicación:** `app/Models/Brigada.php`

**Código generado:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brigada extends Model
{
    //
}
```

**Observaciones:**

- Clase completamente vacía
- Extiende de `Model` (hereda toda la funcionalidad de Eloquent)
- No dice explícitamente "usar tabla brigadas"
- Laravel lo deduce automáticamente por convención

**¿Cómo Laravel sabe qué tabla usar?**

- Clase `Brigada` → pluraliza → `brigadas`
- Clase `Persona` → pluraliza → `personas`
- Clase `User` → pluraliza → `users`

---

### Paso 3: Probar el modelo en Tinker

**Comando en Tinker:**

```php
App\Models\Brigada::all();
```

**Resultado:**

```php
= Illuminate\Database\Eloquent\Collection {
    all: [
      App\Models\Brigada {
        id: 1,
        nombre: "Brigada 1",
        created_at: null,
        updated_at: null,
      },
    ],
}
```

**Diferencia vs SQL crudo:**

**Antes (SQL crudo):**

```php
\DB::table('brigadas')->get();
// Devuelve objeto genérico
```

**Ahora (Eloquent):**

```php
App\Models\Brigada::all();
// Devuelve objeto de tipo Brigada
```

**Ventaja:** El objeto tipo `Brigada` puede tener métodos personalizados, relaciones, validaciones, etc.

---

## 🔐 Configurar $fillable para Seguridad

### El problema del Mass Assignment

**Escenario peligroso:**

```php
// Usuario malicioso envía estos datos desde un formulario
$datos = [
    'nombre' => 'Nueva Brigada',
    'es_admin' => 1  // ← Intenta hacerse admin
];

Brigada::create($datos);  // ¿Debería permitirse?
```

**Sin protección:** Laravel crearía el registro con TODOS los campos, incluyendo `es_admin`.

**Con protección:** Laravel solo permite los campos declarados en `$fillable`.

---

### Solución: Definir campos permitidos

**Editar:** `app/Models/Brigada.php`

**Código modificado:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brigada extends Model
{
    protected $fillable = ['nombre'];
}
```

**¿Qué hace esta línea?**

- Declara explícitamente que solo `nombre` puede asignarse en masa
- `id`, `created_at`, `updated_at` quedan protegidos automáticamente
- Laravel los maneja internamente, el usuario no puede modificarlos

**Ejemplo de uso seguro:**

```php
Brigada::create(['nombre' => 'Brigada Central']);
// ✅ Permitido: nombre está en $fillable

Brigada::create(['id' => 999]);
// ❌ Ignorado: id NO está en $fillable
```

---

## ⚡ Eloquent vs SQL Crudo: Timestamps Automáticos

### Prueba práctica en Tinker

**Insertar con SQL crudo:**

```php
\DB::table('brigadas')->insert([
    'nombre' => 'Brigada 1'
]);
```

**Resultado al consultar:**

```php
App\Models\Brigada::find(1);
// created_at: null
// updated_at: null
```

**¿Por qué null?** SQL crudo no activa la lógica de Eloquent.

---

**Insertar con Eloquent:**

```php
$brigada = new App\Models\Brigada();
$brigada->nombre = 'Brigada 2';
$brigada->save();
```

**Resultado al consultar:**

```php
App\Models\Brigada::find(2);
// created_at: "2025-10-19 17:30:51"
// updated_at: "2025-10-19 17:30:51"
```

**¿Por qué tienen valor?** Eloquent automáticamente llena estos campos.

---

### Actualizar registros

**Comando:**

```php
$brigada = App\Models\Brigada::find(2);
$brigada->nombre = 'Brigada Antinarcóticos';
$brigada->save();
```

**Resultado:**

```php
App\Models\Brigada::find(2);
// created_at: "2025-10-19 17:30:51"  ← Se mantiene igual
// updated_at: "2025-10-19 18:28:15"  ← Se actualizó automáticamente
```

**Ventaja de Eloquent:**

- `created_at` se mantiene con la fecha original (nunca cambia)
- `updated_at` se actualiza automáticamente en cada `save()`
- Sin escribir código extra

---

## ⏰ Nota sobre Zona Horaria UTC

### Observación durante la práctica

**Hora real en Argentina:** 14:31 (GMT-3)  
**Hora guardada en BD:** 17:31 (UTC+0)  
**Diferencia:** 3 horas

### ¿Por qué esta diferencia?

MySQL guarda fechas en **UTC (Coordinated Universal Time)** por defecto.

**Ventajas de usar UTC:**

- Estándar internacional
- Evita problemas con cambios de horario de verano
- Facilita trabajo con usuarios en diferentes zonas horarias

**¿Cómo mostrarlo en hora argentina?**
Laravel tiene configuración en `config/app.php`:

```php
'timezone' => 'America/Argentina/Buenos_Aires'
```

Cuando Laravel muestra fechas, las convierte automáticamente a la zona horaria configurada.

**Para ARDIP:**

- BD guarda en UTC (interno)
- Sistema muestra en hora argentina (usuario)
- Brigadista en San Juan y auditor en Buenos Aires ven las horas correctas

---

## 🌱 Primer Seeder: Brigadas Reales

### ¿Qué es un Seeder?

**Problema a resolver:**
Cuando otro desarrollador (o tu profesor) clone el proyecto:

1. Ejecuta `docker-compose up -d` → Levanta contenedores ✅
2. Ejecuta `php artisan migrate` → Crea tablas ✅
3. **Problema:** Las tablas están vacías 😞

¿Tiene que cargar manualmente las brigadas una por una?

**Solución:** Seeder = Script que carga datos iniciales automáticamente

---

### Analogía con Migraciones

| Concepto | Propósito | Ejemplo |
|----------|-----------|---------|
| **Migration** | Crea estructura (columnas) | CREATE TABLE brigadas |
| **Seeder** | Crea datos (filas) | INSERT INTO brigadas |

---

### Paso 1: Crear el Seeder

**Comando:**

```bash
docker exec -it ardip-app php artisan make:seeder BrigadaSeeder
```

**Resultado:**

```text
INFO  Seeder [database/seeders/BrigadaSeeder.php] created successfully.
```

---

### Paso 2: Código del Seeder

**Ubicación:** `database/seeders/BrigadaSeeder.php`

**Versión 1 - CON duplicados (problema inicial):**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brigada;

class BrigadaSeeder extends Seeder
{
    public function run(): void
    {
        $brigadas = [
            'Brigada Central',
            'Brigada Oeste',
            'Brigada Este',
            'Brigada Sur',
            'Brigada Norte',
            'Apoyo Investigativo',
            'Sustracción de Automotores',
            'Drogas Ilegales'
        ];

        foreach ($brigadas as $nombre) {
            Brigada::create(['nombre' => $nombre]);
        }
    }
}
```

**Problema identificado:**
Si ejecutás el seeder 2 veces, crea duplicados:

```text
Brigada Central
Brigada Central  ← Duplicado
Brigada Oeste
Brigada Oeste    ← Duplicado
```

---

**Versión 2 - SIN duplicados (solución final):**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brigada;

class BrigadaSeeder extends Seeder
{
    public function run(): void
    {
        $brigadas = [
            'Brigada Central',
            'Brigada Oeste',
            'Brigada Este',
            'Brigada Sur',
            'Brigada Norte',
            'Apoyo Investigativo',
            'Sustracción de Automotores',
            'Drogas Ilegales'
        ];

        foreach ($brigadas as $nombre) {
            if (!Brigada::where('nombre', $nombre)->exists()) {
                Brigada::create(['nombre' => $nombre]);
            }
        }
    }
}
```

**Lógica de protección:**

```php
if (!Brigada::where('nombre', $nombre)->exists())
```

Traducido: "Si NO existe una brigada con este nombre, crearla"

**Métodos de Eloquent usados:**

- `where('nombre', $nombre)` → Buscar por nombre
- `exists()` → ¿Existe al menos un registro?
- `!exists()` → Negación: ¿NO existe ningún registro?

---

### Paso 3: Ejecutar el Seeder

**Primera ejecución:**

```bash
docker exec -it ardip-app php artisan db:seed --class=BrigadaSeeder
```

**Resultado:**

```text
INFO  Seeding database.
```

**Verificar en Tinker:**

```php
App\Models\Brigada::count();
// = 10
```

**Análisis:**

- 2 brigadas de prueba (creadas antes en Tinker)
- 8 brigadas reales (creadas por el Seeder)
- Total: 10 brigadas ✅

---

**Segunda ejecución (prueba de duplicados):**

```bash
docker exec -it ardip-app php artisan db:seed --class=BrigadaSeeder
```

**Verificar en Tinker:**

```php
App\Models\Brigada::count();
// = 10  ← Sigue siendo 10, no aumentó
```

**¡Funcionó!** El Seeder detectó que ya existían y no creó duplicados ✅

---

**Tercera ejecución (verificación final):**

```bash
docker exec -it ardip-app php artisan db:seed --class=BrigadaSeeder
```

```php
App\Models\Brigada::count();
// = 10  ← Confirmado, no crea duplicados
```

---

## 📊 Brigadas Reales Cargadas en el Sistema

Según información proporcionada de la Policía de San Juan:

1. **Brigada Central**
2. **Brigada Oeste**
3. **Brigada Este**
4. **Brigada Sur**
5. **Brigada Norte**
6. **Apoyo Investigativo**
7. **Sustracción de Automotores**
8. **Drogas Ilegales**

**Total:** 8 brigadas operativas (más las 2 de prueba = 10 en BD)

**Nota:** El sistema está preparado para agregar más brigadas en el futuro sin modificar código (solo ejecutando el seeder con el array actualizado).

---

## 🔧 Comandos Útiles Aprendidos

### Docker

```bash
# Ver contenedores corriendo
docker ps

# Ver todos los contenedores (incluso detenidos)
docker ps -a

# Apagar y eliminar contenedores
docker-compose down

# Levantar contenedores en segundo plano
docker-compose up -d

# Ver logs de un contenedor
docker logs ardip-app
docker logs ardip-mysql
docker logs ardip-nginx

# Ejecutar comando en un contenedor
docker exec -it ardip-app [comando]
```

---

### Artisan (Laravel)

```bash
# Crear migración
docker exec -it ardip-app php artisan make:migration create_tabla_table

# Ejecutar migraciones pendientes
docker exec -it ardip-app php artisan migrate

# Ver estado de migraciones
docker exec -it ardip-app php artisan migrate:status

# Revertir última migración
docker exec -it ardip-app php artisan migrate:rollback

# Crear modelo
docker exec -it ardip-app php artisan make:model NombreModelo

# Crear seeder
docker exec -it ardip-app php artisan make:seeder NombreSeeder

# Ejecutar un seeder específico
docker exec -it ardip-app php artisan db:seed --class=NombreSeeder

# Ejecutar todos los seeders
docker exec -it ardip-app php artisan db:seed

# Abrir Tinker (REPL interactivo)
docker exec -it ardip-app php artisan tinker
```

---

### Tinker (dentro de la consola interactiva)

```php
// Traer todos los registros
App\Models\Brigada::all();

// Contar registros
App\Models\Brigada::count();

// Buscar por ID
App\Models\Brigada::find(1);

// Buscar por condición
App\Models\Brigada::where('nombre', 'Brigada Central')->first();

// Verificar si existe
App\Models\Brigada::where('nombre', 'X')->exists();

// Crear nuevo registro
$b = new App\Models\Brigada();
$b->nombre = 'Nombre';
$b->save();

// Crear en una línea
App\Models\Brigada::create(['nombre' => 'Nombre']);

// Actualizar
$b = App\Models\Brigada::find(1);
$b->nombre = 'Nuevo Nombre';
$b->save();

// Eliminar
$b = App\Models\Brigada::find(1);
$b->delete();

// Salir de Tinker
exit
```

---

## 🧠 Conceptos Clave Reforzados

### 1. Docker: Contenedores vs Imágenes

**Analogía con POO:**

- **Imagen** = Clase (el molde/plantilla)
- **Contenedor** = Objeto (instancia corriendo)

```text
Clase Brigada           →    Imagen php:8.2-fpm
new Brigada()           →    docker run php:8.2-fpm
$brigada1, $brigada2    →    Contenedor 1, Contenedor 2
```

---

### 2. Flujo de Trabajo Laravel

```text
1. MIGRACIÓN (estructura)
   ↓
   php artisan make:migration create_tabla_table
   ↓
   Editar archivo en database/migrations/
   ↓
   php artisan migrate
   ↓
   Tabla creada en MySQL

2. MODELO (lógica)
   ↓
   php artisan make:model NombreModelo
   ↓
   Editar app/Models/NombreModelo.php
   ↓
   Definir $fillable
   ↓
   Usar en código: NombreModelo::all()

3. SEEDER (datos iniciales)
   ↓
   php artisan make:seeder NombreSeeder
   ↓
   Editar database/seeders/NombreSeeder.php
   ↓
   php artisan db:seed --class=NombreSeeder
   ↓
   Datos cargados en BD
```

---

### 3. Convenciones de Laravel

| Elemento | Convención | Ejemplo |
|----------|------------|---------|
| Tabla en BD | plural, minúscula | `brigadas` |
| Modelo (clase) | singular, PascalCase | `Brigada` |
| Migración | create_tabla_table | `create_brigadas_table` |
| Seeder | NombreSeeder | `BrigadaSeeder` |
| Archivo migración | timestamp_nombre.php | `2025_10_19_170147_create_brigadas_table.php` |

---

### 4. Eloquent: Ventajas sobre SQL Crudo

| Característica | SQL Crudo | Eloquent |
|----------------|-----------|----------|
| **Timestamps** | Hay que escribirlos manualmente | Automático |
| **Código** | SQL strings | Métodos PHP |
| **Validación** | Manual | Integrada en el modelo |
| **Relaciones** | JOINs complejos | `$brigada->procedimientos` |
| **Portabilidad** | Depende de BD (MySQL, PostgreSQL) | Funciona en cualquier BD |

---

### 5. Seguridad: Mass Assignment Protection

**Sin protección:**

```php
// Peligroso: acepta CUALQUIER campo
User::create($request->all());
```

**Con protección:**

```php
class User extends Model {
    protected $fillable = ['nombre', 'email'];
    // 'es_admin' NO está, Laravel lo protege
}
```

---

## 📁 Estructura de Archivos Creados/Modificados

```text
ARDIP/
├── app/
│   └── Models/
│       └── Brigada.php                                    (NUEVO - Modelo)
├── database/
│   ├── migrations/
│   │   └── 2025_10_19_170147_create_brigadas_table.php   (NUEVO - Migración)
│   └── seeders/
│       └── BrigadaSeeder.php                              (NUEVO - Seeder)
└── ... (otros archivos de Laravel)
```

---

## 🎓 Archivos Completos - Código Final

### 1. Migración: create_brigadas_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brigadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brigadas');
    }
};
```

---

### 2. Modelo: Brigada.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brigada extends Model
{
    protected $fillable = ['nombre'];
}
```

---

### 3. Seeder: BrigadaSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brigada;

class BrigadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brigadas = [
            'Brigada Central',
            'Brigada Oeste',
            'Brigada Este',
            'Brigada Sur',
            'Brigada Norte',
            'Apoyo Investigativo',
            'Sustracción de Automotores',
            'Drogas Ilegales'
        ];

        foreach ($brigadas as $nombre) {
            if (!Brigada::where('nombre', $nombre)->exists()) {
                Brigada::create(['nombre' => $nombre]);
            }
        }
    }
}
```

---

## 🚀 Próximos Pasos - Sesión 3

### Objetivos para la siguiente sesión

1. **Crear migración de `personas`** (tabla más compleja)
   - Múltiples campos (dni, nombres, apellidos, alias, etc.)
   - Foto (manejo de archivos)
   - Relación con domicilios

2. **Crear modelo `Persona`**
   - Configurar $fillable con todos los campos
   - Definir validaciones

3. **Relaciones Eloquent**
   - Una persona puede tener múltiples domicilios
   - Una persona puede estar en múltiples procedimientos
   - Concepto de relaciones 1:N (uno a muchos)

4. **Seeder de personas** (datos de prueba)
   - Crear personas ficticias para testing
   - Protección contra duplicados por DNI

---

## 📊 Estado Actual del Proyecto ARDIP

### ✅ Completado

```text
INFRAESTRUCTURA:
✅ Docker configurado (3 contenedores)
✅ Laravel 12.34.0 instalado
✅ MySQL 8.0 conectado
✅ Nginx sirviendo en puerto 8000

BASE DE DATOS:
✅ Tabla users (Laravel por defecto)
✅ Tabla cache (Laravel por defecto)
✅ Tabla jobs (Laravel por defecto)
✅ Tabla brigadas (ARDIP - primera tabla personalizada)

MODELOS ELOQUENT:
✅ Modelo Brigada funcionando
✅ $fillable configurado
✅ Timestamps automáticos funcionando

SEEDERS:
✅ BrigadaSeeder con 8 brigadas reales
✅ Protección contra duplicados implementada
```

### 📝 Pendiente

```text
TABLAS POR CREAR:
❌ personas
❌ domicilios
❌ procedimientos
❌ participaciones (relación persona-procedimiento)
❌ auditoria

FUNCIONALIDADES:
❌ Sistema de autenticación (login/logout)
❌ Roles y permisos (ADMIN, ADMIN_BRIGADA, etc.)
❌ CRUD de personas
❌ CRUD de procedimientos
❌ Sistema de búsqueda
❌ Upload de fotos
❌ Reportes
```

---

## 💡 Lecciones Aprendidas - Reflexiones

### 1. Sobre la Configuración de Docker

**Pregunta inicial:** "¿Debo crear un contenedor que incluya MySQL, el servidor y Laravel todo en uno?"

**Respuesta aprendida:** No. Es mejor separar responsabilidades:

- 1 contenedor = 1 servicio
- Facilita mantenimiento
- Permite reutilización
- Si MySQL falla, no afecta a Laravel

**Principio aplicado:** Single Responsibility Principle (SRP)

---

### 2. Sobre Memorizar vs Entender

**Cita del estudiante:**
> "Es medio complicado la configuración, me parecen cosas que nunca me voy a aprender de memoria como un programador verdadero"

**Realidad profesional:**
Los programadores seniors tampoco memorizan todo. Lo importante es:

- ✅ Entender QUÉ hace cada cosa
- ✅ Saber DÓNDE buscar cuando lo necesites
- ✅ Tener documentación de referencia
- ❌ NO hace falta memorizar sintaxis exacta

**Analogía:** Un cirujano no memoriza cada paso de cada cirugía, pero sí entiende anatomía y tiene protocolos de referencia.

---

### 3. Sobre la Práctica y el Aprendizaje

**Observación:** Después de 2 días sin tocar el proyecto, algunos conceptos no estaban frescos.

**Estrategia de aprendizaje espaciado:**

- Primera vez: 3 horas con ayuda
- Segunda vez: 1 hora consultando doc
- Tercera vez: 30 minutos
- Décima vez: 10 minutos automático

**Recomendación:** Practicar el ciclo completo (migración → modelo → seeder) con otra tabla simple antes de la próxima sesión.

---

### 4. Sobre Sesiones de Trabajo

**Cita del estudiante:**
> "Me gusta que elegiste terminar la sesión para continuar otro día, eso es importante porque a veces me quedo hasta tarde y me quemo la cabeza"

**Ventaja del descanso:**
Durante el sueño, el cerebro consolida lo aprendido. Mejor:

- ✅ Sesiones cortas (1-2 horas) pero productivas
- ✅ Dormir y volver al día siguiente
- ✅ Repasar antes de continuar
- ❌ Maratones de 8 horas que generan frustración

---

## 🔄 Ejercicio Propuesto para Practicar

Antes de la próxima sesión, intentá crear SOLO (sin ayuda) una tabla simple para practicar el flujo completo:

### Tabla: `roles`

**Estructura simple:**

- id
- nombre (ej: "ADMIN", "INVESTIGADOR", "AUDITOR")
- timestamps

**Pasos a seguir:**

1. Crear migración
2. Editar migración (agregar campo nombre)
3. Ejecutar migración
4. Crear modelo Role
5. Configurar $fillable
6. Crear RoleSeeder con 4 roles
7. Ejecutar seeder
8. Verificar en Tinker

**Objetivo:** Reforzar el ciclo completo sin ayuda, usando esta documentación como referencia.

**Tiempo estimado:** 20-30 minutos

---

## 📚 Recursos para Profundizar

### Documentación Oficial

**Laravel:**

- Migraciones: <https://laravel.com/docs/migrations>
- Eloquent ORM: <https://laravel.com/docs/eloquent>
- Seeders: <https://laravel.com/docs/seeding>

**Docker:**

- Docker Compose: <https://docs.docker.com/compose/>
- Comandos básicos: <https://docs.docker.com/engine/reference/commandline/docker/>

---

### Videos Recomendados (YouTube)

**Laravel en Español:**

- "Curso Laravel desde Cero" - Rimorsoft
- "Laravel y Eloquent" - Victor Arana

**Docker:**

- "Docker para Principiantes" - Pelado Nerd
- "Docker Compose Explicado" - hdeleon.net

---

## 🐛 Problemas Comunes y Soluciones

### Problema 1: Error "cannot connect to Docker"

**Síntoma:**

```text
error during connect: ... open //./pipe/dockerDesktopLinuxEngine
```

**Causa:** Docker Desktop no está corriendo

**Solución:**

1. Abrir Docker Desktop
2. Esperar a que inicie (ícono de ballena en barra de tareas)
3. Ejecutar comando de nuevo

---

### Problema 2: "Class 'Brigada' not found"

**Síntoma:**

```text
Error: Class 'Brigada' not found
```

**Causa:** Olvidaste el namespace completo

**Solución:**

```php
// ❌ Incorrecto
Brigada::all();

// ✅ Correcto
App\Models\Brigada::all();

// O importar al inicio del archivo
use App\Models\Brigada;
Brigada::all();
```

---

### Problema 3: "Add [nombre] to fillable property"

**Síntoma:**

```text
Illuminate\Database\Eloquent\MassAssignmentException
Add [nombre] to fillable property to allow mass assignment
```

**Causa:** Intentaste usar `create()` sin definir `$fillable`

**Solución:**

```php
class Brigada extends Model
{
    protected $fillable = ['nombre'];  // ← Agregar esta línea
}
```

---

### Problema 4: Puerto 8000 ya en uso

**Síntoma:**

```text
Error: port is already allocated
```

**Causa:** Otro servicio está usando el puerto 8000

**Solución temporal:**
Cambiar puerto en `docker-compose.yml`:

```yaml
nginx:
  ports:
    - "8080:80"  # Usar 8080 en lugar de 8000
```

**Solución permanente:**
Detener el servicio que está usando el puerto 8000 (buscar con `netstat -ano | findstr :8000` en Windows)

---

## ✅ Checklist de Verificación

Antes de la próxima sesión, verificá que:

### Dockerr

- [ ] Docker Desktop está instalado
- [ ] Docker Desktop inicia correctamente
- [ ] `docker ps` muestra los 3 contenedores
- [ ] `http://localhost:8000` muestra Laravel

### Base de Datos

- [ ] Tabla `brigadas` existe
- [ ] Hay 10 brigadas en la tabla
- [ ] Los timestamps funcionan correctamente

### Código

- [ ] Modelo `Brigada.php` tiene `$fillable`
- [ ] Seeder `BrigadaSeeder.php` tiene protección contra duplicados
- [ ] Podés ejecutar el seeder múltiples veces sin duplicar

### Comandos

- [ ] Recordás cómo levantar contenedores (`docker-compose up -d`)
- [ ] Recordás cómo apagar contenedores (`docker-compose down`)
- [ ] Recordás cómo abrir Tinker
- [ ] Recordás comandos básicos de Eloquent

---

## 📝 Notas Adicionales del Estudiante

**Espacio para agregar tus propias anotaciones:**

```text
- Conceptos que me costaron:
  

- Conceptos que entendí bien:
  

- Dudas para la próxima sesión:
  

- Cosas que quiero investigar más:
  

```

---

## 🎯 Objetivos de Aprendizaje Cumplidos

### Conocimientos Técnicos

- [x] Entender el flujo de trabajo con Docker
- [x] Crear migraciones en Laravel
- [x] Entender convenciones de nombres (singular/plural)
- [x] Usar Eloquent ORM básico
- [x] Configurar $fillable para seguridad
- [x] Crear seeders con lógica condicional
- [x] Usar Tinker para debugging

### Habilidades de Desarrollo

- [x] Debugging de errores comunes
- [x] Lectura de mensajes de error
- [x] Uso de terminal integrada en VS Code
- [x] Organización de archivos en proyecto Laravel

### Habilidades Blandas

- [x] Hacer preguntas cuando algo no se entiende
- [x] Reconocer cuándo tomar un descanso
- [x] Documentar el progreso del proyecto
- [x] Reflexionar sobre el proceso de aprendizaje

---

## 📈 Progreso del Proyecto

```text
Sprint 1-2 (Base): 40% completado
├── Setup proyecto ✅ (100%)
├── Autenticación ❌ (0%)
└── Dashboard básico ❌ (0%)

Sprint 2-3 (CRUD Personas): 0% completado
├── Migración personas ❌
├── Modelo Persona ❌
└── CRUD completo ❌

Sprint 3-4 (Procedimientos): 0% completado

Sprint 4-5 (Sistema Avanzado): 0% completado

Sprint 5-6 (Testing y Deploy): 0% completado
```

**Tiempo invertido hasta ahora:** ~5 horas (2 sesiones)

**Tiempo estimado restante:** ~40-50 horas

**Fecha límite:** Fines de noviembre 2025

**Conclusión:** El ritmo es adecuado, vamos bien de tiempo ✅

---

## 💬 Feedback del Estudiante

**Lo que funcionó bien:**

- Explicaciones paso a paso
- Hacer preguntas para que piense antes de dar soluciones
- Documentación detallada
- Sesiones de duración apropiada

**Lo que mejorar:**

- (Espacio para feedback del estudiante)

---

## 🎓 Conclusión de la Sesión 2

En esta sesión lograste crear tu primera tabla completa del sistema ARDIP siguiendo las mejores prácticas de Laravel:

1. **Migración** → Define estructura en BD
2. **Modelo** → Maneja lógica en código
3. **Seeder** → Carga datos iniciales

Entendiste conceptos clave:

- Convenciones de Laravel (singular/plural)
- Seguridad ($fillable)
- Diferencia entre Eloquent y SQL crudo
- Protección contra duplicados

Ya no estás solo "copiando y pegando" código. Estás **entendiendo** qué hace cada parte y **por qué** lo hace así.

**Próximo desafío:** Tabla `personas` con relaciones. Es más complejo, pero ya tenés la base.

---

**Preparado por:** Claude (Asistente IA)  
**Para:** Flores, Maximiliano  
**Proyecto:** ARDIP V1 - Tecnicatura Superior en Desarrollo de Software  
**Fecha:** 19 de Octubre, 2025  
**Sesión:** 2 de N

---

## 📎 Anexos

### A. Diagrama del Flujo de Trabajo

```text
┌─────────────────────────────────────────────────────┐
│                   DESARROLLADOR                      │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  1. Crear Migración                                 │
│     php artisan make:migration create_X_table       │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  2. Editar Migración                                │
│     Definir columnas con $table->tipo('nombre')     │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  3. Ejecutar Migración                              │
│     php artisan migrate                             │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  4. Crear Modelo                                    │
│     php artisan make:model NombreModelo             │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  5. Configurar Modelo                               │
│     Definir $fillable                               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  6. Crear Seeder (opcional)                         │
│     php artisan make:seeder NombreSeeder            │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  7. Ejecutar Seeder                                 │
│     php artisan db:seed --class=NombreSeeder        │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  8. Usar Modelo en Código                           │
│     NombreModelo::all()                             │
│     NombreModelo::create([...])                     │
└─────────────────────────────────────────────────────┘
```

### B. Comparación: Antes vs Después

**ANTES (PHP Puro):**

```php
// Conectar a BD
$conn = mysqli_connect("localhost", "user", "pass", "db");

// Crear tabla
$sql = "CREATE TABLE brigadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)";
mysqli_query($conn, $sql);

// Insertar dato
$sql = "INSERT INTO brigadas (nombre, created_at, updated_at) 
        VALUES ('Brigada Central', NOW(), NOW())";
mysqli_query($conn, $sql);

// Consultar
$sql = "SELECT * FROM brigadas";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    echo $row['nombre'];
}
```

**AHORA (Laravel):**

```php
// Migración (se ejecuta una vez)
Schema::create('brigadas', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->timestamps();
});

// Insertar
Brigada::create(['nombre' => 'Brigada Central']);

// Consultar
$brigadas = Brigada::all();
foreach($brigadas as $brigada) {
    echo $brigada->nombre;
}
```

**Ventajas:**

- ✅ Menos código
- ✅ Más legible
- ✅ Timestamps automáticos
- ✅ Protección contra SQL injection
- ✅ Versionado de BD con migraciones

---

## FIN DEL DOCUMENTO - SESION 2
