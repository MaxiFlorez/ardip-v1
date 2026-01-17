<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'superadmin@ardip.gob.ar')->first();

if (!$user) {
    echo "❌ Usuario superadmin@ardip.gob.ar NO encontrado\n";
    exit(1);
}

echo "✅ Usuario encontrado:\n";
echo "   ID: {$user->id}\n";
echo "   Nombre: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   Activo: " . ($user->active ? 'Sí' : 'No') . "\n";
echo "\n📋 Roles asignados:\n";

if ($user->roles->isEmpty()) {
    echo "   ⚠️  NO tiene roles asignados\n";
} else {
    foreach ($user->roles as $role) {
        echo "   - {$role->name}\n";
    }
}

echo "\n🔍 Verificación de métodos:\n";
echo "   hasRole('super_admin'): " . ($user->hasRole('super_admin') ? '✅ true' : '❌ false') . "\n";
echo "   hasRole('admin'): " . ($user->hasRole('admin') ? '✅ true' : '❌ false') . "\n";
