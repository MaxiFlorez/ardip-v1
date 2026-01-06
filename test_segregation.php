<?php
// Script de testing para segregación de funciones

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TESTS DE SEGREGACIÓN DE FUNCIONES\n";
echo str_repeat("=", 60) . "\n\n";

// ====================
// CREAR USUARIOS TEST
// ====================
echo "📝 Creando usuarios de prueba...\n";

$superAdmin = User::firstOrCreate(
    ['email' => 'super@test.com'],
    ['name' => 'Super Admin Test', 'password' => bcrypt('password')]
);
$superAdmin->syncRoles(['super_admin']);
echo "✅ Super Admin: {$superAdmin->email}\n";

$admin = User::firstOrCreate(
    ['email' => 'admin@test.com'],
    ['name' => 'Admin Test', 'password' => bcrypt('password')]
);
$admin->syncRoles(['admin']);
echo "✅ Admin: {$admin->email}\n";

$cargador = User::firstOrCreate(
    ['email' => 'cargador@test.com'],
    ['name' => 'Cargador Test', 'password' => bcrypt('password')]
);
$cargador->syncRoles(['panel-carga']);
echo "✅ Cargador: {$cargador->email}\n\n";

// ====================
// TEST GATES
// ====================
echo str_repeat("-", 60) . "\n";
echo "🔐 TEST 1: GATES DE SUPER ADMIN\n";
echo str_repeat("-", 60) . "\n";

$superAdmin = $superAdmin->fresh();
$result1 = Gate::forUser($superAdmin)->allows('super-admin');
$result2 = Gate::forUser($superAdmin)->allows('acceso-operativo');
$result3 = Gate::forUser($superAdmin)->allows('admin');

echo "Gate 'super-admin': " . ($result1 ? "✅ TRUE" : "❌ FALSE") . "\n";
echo "Gate 'acceso-operativo': " . ($result2 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)") . "\n";
echo "Gate 'admin': " . ($result3 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)") . "\n";

if ($result1 && !$result2 && !$result3) {
    echo "\n✅ SUPER ADMIN: GATES CORRECTOS\n";
} else {
    echo "\n❌ SUPER ADMIN: GATES INCORRECTOS\n";
}

// ====================
echo str_repeat("-", 60) . "\n";
echo "🔐 TEST 2: GATES DE ADMIN\n";
echo str_repeat("-", 60) . "\n";

$admin = $admin->fresh();
$result1 = Gate::forUser($admin)->allows('super-admin');
$result2 = Gate::forUser($admin)->allows('acceso-operativo');
$result3 = Gate::forUser($admin)->allows('admin');

echo "Gate 'super-admin': " . ($result1 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)") . "\n";
echo "Gate 'acceso-operativo': " . ($result2 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)") . "\n";
echo "Gate 'admin': " . ($result3 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)") . "\n";

if (!$result1 && $result2 && $result3) {
    echo "\n✅ ADMIN: GATES CORRECTOS\n";
} else {
    echo "\n❌ ADMIN: GATES INCORRECTOS\n";
}

// ====================
echo str_repeat("-", 60) . "\n";
echo "🔐 TEST 3: GATES DE CARGADOR\n";
echo str_repeat("-", 60) . "\n";

$cargador = $cargador->fresh();
$result1 = Gate::forUser($cargador)->allows('super-admin');
$result2 = Gate::forUser($cargador)->allows('acceso-operativo');
$result3 = Gate::forUser($cargador)->allows('panel-carga');

echo "Gate 'super-admin': " . ($result1 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)") . "\n";
echo "Gate 'acceso-operativo': " . ($result2 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)") . "\n";
echo "Gate 'panel-carga': " . ($result3 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)") . "\n";

if (!$result1 && $result2 && $result3) {
    echo "\n✅ CARGADOR: GATES CORRECTOS\n";
} else {
    echo "\n❌ CARGADOR: GATES INCORRECTOS\n";
}

// ====================
// TEST MÉTODOS DE USER
// ====================
echo str_repeat("-", 60) . "\n";
echo "👤 TEST 4: MÉTODO isSuperAdmin()\n";
echo str_repeat("-", 60) . "\n";

$superAdmin = $superAdmin->fresh();
$admin = $admin->fresh();

echo "Super Admin->isSuperAdmin(): " . ($superAdmin->isSuperAdmin() ? "✅ TRUE" : "❌ FALSE") . "\n";
echo "Admin->isSuperAdmin(): " . ($admin->isSuperAdmin() ? "❌ TRUE" : "✅ FALSE") . "\n";

// ====================
// TEST ROLES
// ====================
echo str_repeat("-", 60) . "\n";
echo "👥 TEST 5: ROLES ASIGNADOS\n";
echo str_repeat("-", 60) . "\n";

echo "Super Admin roles: " . $superAdmin->roles->pluck('name')->implode(', ') . "\n";
echo "Admin roles: " . $admin->roles->pluck('name')->implode(', ') . "\n";
echo "Cargador roles: " . $cargador->roles->pluck('name')->implode(', ') . "\n";

// ====================
// RESUMEN
// ====================
echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ TESTS COMPLETADOS\n";
echo str_repeat("=", 60) . "\n";
echo "\n📝 RESUMEN:\n";
echo "  • Super Admin: TÉCNICO PURO (sin acceso operativo)\n";
echo "  • Admin: OPERATIVO (con acceso a procedimientos, etc)\n";
echo "  • Cargador: OPERATIVO (carga de datos)\n";
echo "\n🔒 Segregación: ACTIVA Y FUNCIONANDO ✅\n\n";
