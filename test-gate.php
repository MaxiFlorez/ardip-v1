<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

$user = User::whereHas('roles', function($q) {
    $q->where('name', 'super_admin');
})->first();

if (!$user) {
    echo "❌ No se encontró ningún usuario con rol super_admin\n";
    exit(1);
}

echo "Usuario encontrado: {$user->email}\n";
echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n\n";

echo "Testing Gates:\n";
echo "  - super-admin: " . (Gate::forUser($user)->allows('super-admin') ? '✅ PERMITE' : '❌ DENIEGA') . "\n";
echo "  - admin: " . (Gate::forUser($user)->allows('admin') ? '✅ PERMITE' : '❌ DENIEGA') . "\n";
echo "  - acceso-operativo: " . (Gate::forUser($user)->allows('acceso-operativo') ? '✅ PERMITE' : '❌ DENIEGA') . "\n";
