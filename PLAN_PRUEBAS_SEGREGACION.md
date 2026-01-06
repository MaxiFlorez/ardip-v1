# Plan de Pruebas - Segregación de Funciones

**Objetivo:** Validar que la segregación de funciones está correctamente implementada.

---

## 📋 Tabla de Pruebas

### Grupo A: Super Admin Puro (Rol único: super_admin)

#### A1: Acceso a Dashboard

- **Ruta:** `GET /dashboard`
- **Esperado:** ❌ 403 Forbidden (Dashboard no es accesible para super_admin puro)
- **Verificación adicional:** El link NO aparece en el menú

#### A2: Acceso a Procedimientos

- **Ruta:** `GET /procedimientos`
- **Esperado:** ❌ 403 Forbidden (Acceso denegado - es operativo)
- **Verificación adicional:** El link NO aparece en el menú

#### A3: Acceso a Personas

- **Ruta:** `GET /personas`
- **Esperado:** ❌ 403 Forbidden (Acceso denegado - es operativo)

#### A4: Acceso a Documentos

- **Ruta:** `GET /documentos`
- **Esperado:** ❌ 403 Forbidden (Acceso denegado - es operativo)

#### A5: Acceso a Gestión de Usuarios

- **Ruta:** `GET /admin/users`
- **Esperado:** ✅ 200 OK (Permitido - es técnico)
- **Verificación:** El link SI aparece en el menú

#### A6: Acceso a Brigadas

- **Ruta:** `GET /admin/brigadas`
- **Esperado:** ✅ 200 OK (Permitido - es técnico super-admin)

#### A7: Acceso a UFIs

- **Ruta:** `GET /admin/ufis`
- **Esperado:** ✅ 200 OK (Permitido - es técnico super-admin)

---

### Grupo B: Admin (Rol único: admin)

#### B1: Acceso a Dashboard

- **Ruta:** `GET /dashboard`
- **Esperado:** ✅ 200 OK (Permitido - es admin)
- **Verificación:** El link SÍ aparece en el menú

#### B2: Acceso a Procedimientos

- **Ruta:** `GET /procedimientos`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)
- **Verificación:** El link SÍ aparece en el menú

#### B3: Acceso a Personas

- **Ruta:** `GET /personas`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)
- **Verificación:** El link SÍ aparece en el menú

#### B4: Acceso a Documentos

- **Ruta:** `GET /documentos`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)
- **Verificación:** El link SÍ aparece en el menú

#### B5: Acceso a Gestión de Usuarios

- **Ruta:** `GET /admin/users`
- **Esperado:** ✅ 200 OK (Permitido - es admin)
- **Verificación:** El link SÍ aparece en el menú

#### B6: Acceso a Brigadas

- **Ruta:** `GET /admin/brigadas`
- **Esperado:** ❌ 403 Forbidden (Denegado - solo super-admin)
- **Verificación:** El link NO aparece en el menú

#### B7: Acceso a UFIs

- **Ruta:** `GET /admin/ufis`
- **Esperado:** ❌ 403 Forbidden (Denegado - solo super-admin)
- **Verificación:** El link NO aparece en el menú

---

### Grupo C: Cargador (Rol único: panel-carga)

#### C1: Acceso a Procedimientos

- **Ruta:** `GET /procedimientos`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)

#### C2: Acceso a Personas

- **Ruta:** `GET /personas`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)

#### C3: Acceso a Documentos

- **Ruta:** `GET /documentos`
- **Esperado:** ✅ 200 OK (Permitido - es operativo)

#### C4: Acceso a Dashboard

- **Ruta:** `GET /dashboard`
- **Esperado:** ❌ 403 Forbidden (No es admin)

#### C5: Acceso a Gestión de Usuarios

- **Ruta:** `GET /admin/users`
- **Esperado:** ❌ 403 Forbidden (No es admin)

#### C6: Acceso a Brigadas

- **Ruta:** `GET /admin/brigadas`
- **Esperado:** ❌ 403 Forbidden (Solo super-admin)

---

### Grupo D: Consultor (Rol único: panel-consulta)

#### D1: Acceso a Procedimientos

- **Ruta:** `GET /procedimientos`
- **Esperado:** ✅ 200 OK (Permitido - acceso-operativo)

#### D2: Acceso a Personas

- **Ruta:** `GET /personas`
- **Esperado:** ✅ 200 OK (Permitido - acceso-operativo)

#### D3: Acceso a Documentos

- **Ruta:** `GET /documentos`
- **Esperado:** ✅ 200 OK (Permitido - acceso-operativo)

#### D4: Acceso a Dashboard

- **Ruta:** `GET /dashboard`
- **Esperado:** ❌ 403 Forbidden (No es admin)

---

## 🔄 Pruebas Adicionales: Múltiples Roles

### Grupo E: Super Admin + Admin (Roles múltiples)

Si existe un usuario con roles `super_admin` Y `admin` simultáneamente:

#### E1: Acceso a Procedimientos

- **Lógica:** Gate verifica `count() === 1` → **NO** es true (count = 2)
- **Esperado:** ✅ 200 OK (Se permite por el rol admin secundario)

#### E2: Acceso a Brigadas

- **Esperado:** ✅ 200 OK (Se permite por rol super_admin)

#### E3: Acceso a Dashboard

- **Esperado:** ✅ 200 OK (Se permite por rol admin)

#### E4: Menú Desktop

- **Esperado:** Muestra TODOS los links (Dashboard, Procedimientos, Brigadas, UFIs, Usuarios)

---

## 🧪 Scripts de Prueba Manuales

### Prueba 1: Verificar que Super Admin NO ve Procedimientos en menú

```bash
# 1. Login como super_admin
# 2. Ver el menú principal
# 3. Resultado esperado:
#    - Dashboard: NO visible
#    - Procedimientos: NO visible
#    - Personas: NO visible
#    - Documentos: NO visible
#    - Brigadas: VISIBLE
#    - UFIs: VISIBLE
#    - Gestión Usuarios: NO visible (por la lógica de exclusión)
```

### Prueba 2: Intentar acceso directo de Super Admin a Procedimientos

```bash
# 1. Login como super_admin
# 2. Navegar directamente a: http://localhost:8000/procedimientos
# 3. Resultado esperado: Página 403 Forbidden
```

### Prueba 3: Acceso de Admin a todos los operativos

```bash
# 1. Login como admin
# 2. Navegar a: /procedimientos → ✅ 200
# 3. Navegar a: /personas → ✅ 200
# 4. Navegar a: /documentos → ✅ 200
# 5. Intentar: /admin/brigadas → ❌ 403
```

### Prueba 4: Verificar que Cargador solo ve operativos

```bash
# 1. Login como cargador (panel-carga)
# 2. Verificar menú:
#    - Procedimientos: VISIBLE
#    - Personas: VISIBLE
#    - Documentos: VISIBLE
#    - Dashboard: NO visible
#    - Brigadas: NO visible
# 3. Intenta: GET /admin/users → ❌ 403
```

---

## 📊 Matriz de Prueba Consolidada

| Usuario | Ruta | Esperado | Estado | Nota |
|---------|------|----------|--------|------|
| Super Admin | GET /dashboard | ❌ 403 | ⬜ | No en menú |
| Super Admin | GET /procedimientos | ❌ 403 | ⬜ | No en menú |
| Super Admin | GET /admin/brigadas | ✅ 200 | ⬜ | En menú |
| Admin | GET /dashboard | ✅ 200 | ⬜ | En menú |
| Admin | GET /procedimientos | ✅ 200 | ⬜ | En menú |
| Admin | GET /admin/brigadas | ❌ 403 | ⬜ | No en menú |
| Cargador | GET /procedimientos | ✅ 200 | ⬜ | En menú |
| Cargador | GET /dashboard | ❌ 403 | ⬜ | No en menú |
| Consultor | GET /procedimientos | ✅ 200 | ⬜ | En menú |
| Consultor | GET /admin/users | ❌ 403 | ⬜ | No en menú |

---

## 🛠️ Comandos para Testing Automático

### Verificar Gates en Tinker

```bash
# Acceder a Tinker (REPL de Laravel)
php artisan tinker

# Obtener un usuario super_admin
$superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->first();

# Verificar gates
Gate::allows('super-admin', $superAdmin);  # Resultado: true
Gate::allows('acceso-operativo', $superAdmin);  # Resultado: false (¡Correcto!)
Gate::allows('admin', $superAdmin);  # Resultado: false (¡Correcto!)

# Obtener un usuario admin
$admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();

# Verificar gates
Gate::allows('acceso-operativo', $admin);  # Resultado: true
Gate::allows('super-admin', $admin);  # Resultado: false (¡Correcto!)
```

---

## ✅ Checklist de Validación

- [ ] Super Admin NO ve Procedimientos en menú
- [ ] Super Admin NO puede acceder a /procedimientos (403)
- [ ] Super Admin SÍ puede acceder a /admin/brigadas (200)
- [ ] Admin SÍ ve Procedimientos en menú
- [ ] Admin SÍ puede acceder a /procedimientos (200)
- [ ] Admin NO puede acceder a /admin/brigadas (403)
- [ ] Cargador SÍ puede acceder a /procedimientos (200)
- [ ] Cargador NO puede acceder a /admin/users (403)
- [ ] Consultor SÍ puede acceder a /procedimientos (200)
- [ ] Dashboard menú excluye super_admin puro
- [ ] Todos los cachés han sido limpiados
- [ ] No hay errores en laravel.log

---

## 📝 Registro de Ejecución

Usa esta sección para documentar tus pruebas:

### Prueba A1 (Super Admin - Dashboard)

- **Fecha:** ___________
- **Usuario test:** ___________
- **Resultado:** ✅ PASÓ / ❌ FALLÓ
- **Notas:** ___________

### Prueba B2 (Admin - Procedimientos)

- **Fecha:** ___________
- **Usuario test:** ___________
- **Resultado:** ✅ PASÓ / ❌ FALLÓ
- **Notas:** ___________

---

## 🚨 En Caso de Fallo

Si alguna prueba falla:

1. **Revisar cachés:**

   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan cache:clear
   ```

2. **Revisar logs:**

   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Revisar AppServiceProvider.php:**
   - Línea 44-83: Gates deben tener lógica de exclusión correcta

4. **Revisar routes/web.php:**
   - Línea 48-89: Rutas operativas deben tener `middleware('can:acceso-operativo')`

5. **Revisar navigation.blade.php:**
   - Línea 17-70: Menú debe tener lógica de exclusión

---

**Documento de pruebas versión 1.0**
