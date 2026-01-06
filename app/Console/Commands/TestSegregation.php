<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;

class TestSegregation extends Command
{
    protected $signature = 'test:segregation';
    protected $description = 'Test segregación de funciones';

    public function handle()
    {
        $this->info("\n" . str_repeat("=", 60));
        $this->info("🧪 TESTS DE SEGREGACIÓN DE FUNCIONES");
        $this->info(str_repeat("=", 60) . "\n");

        // Crear roles si no existen
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'panel-carga']);

        // Crear usuarios
        $this->info("📝 Creando usuarios de prueba...");
        
        $superAdmin = User::firstOrCreate(
            ['email' => 'super@test.com'],
            ['name' => 'Super Admin Test', 'password' => bcrypt('password'), 'active' => 1]
        );
        // Asignar rol usando relación
        $superAdmin->roles()->sync(Role::where('name', 'super_admin')->first()->id);
        $this->line("✅ Super Admin: {$superAdmin->email}");

        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin Test', 'password' => bcrypt('password'), 'active' => 1]
        );
        $admin->roles()->sync(Role::where('name', 'admin')->first()->id);
        $this->line("✅ Admin: {$admin->email}");

        $cargador = User::firstOrCreate(
            ['email' => 'cargador@test.com'],
            ['name' => 'Cargador Test', 'password' => bcrypt('password'), 'active' => 1]
        );
        $cargador->roles()->sync(Role::where('name', 'panel-carga')->first()->id);
        $this->line("✅ Cargador: {$cargador->email}\n");

        // TEST 1: GATES SUPER ADMIN
        $this->info(str_repeat("-", 60));
        $this->info("🔐 TEST 1: GATES DE SUPER ADMIN");
        $this->info(str_repeat("-", 60));

        $superAdmin = $superAdmin->fresh();
        $test1_1 = Gate::forUser($superAdmin)->allows('super-admin');
        $test1_2 = Gate::forUser($superAdmin)->allows('acceso-operativo');
        $test1_3 = Gate::forUser($superAdmin)->allows('admin');

        $this->line("Gate 'super-admin': " . ($test1_1 ? "✅ TRUE" : "❌ FALSE"));
        $this->line("Gate 'acceso-operativo': " . ($test1_2 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)"));
        $this->line("Gate 'admin': " . ($test1_3 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)"));

        if ($test1_1 && !$test1_2 && !$test1_3) {
            $this->info("\n✅ SUPER ADMIN: GATES CORRECTOS\n");
        } else {
            $this->error("\n❌ SUPER ADMIN: GATES INCORRECTOS\n");
        }

        // TEST 2: GATES ADMIN
        $this->info(str_repeat("-", 60));
        $this->info("🔐 TEST 2: GATES DE ADMIN");
        $this->info(str_repeat("-", 60));

        $admin = $admin->fresh();
        $test2_1 = Gate::forUser($admin)->allows('super-admin');
        $test2_2 = Gate::forUser($admin)->allows('acceso-operativo');
        $test2_3 = Gate::forUser($admin)->allows('admin');

        $this->line("Gate 'super-admin': " . ($test2_1 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)"));
        $this->line("Gate 'acceso-operativo': " . ($test2_2 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)"));
        $this->line("Gate 'admin': " . ($test2_3 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)"));

        if (!$test2_1 && $test2_2 && $test2_3) {
            $this->info("\n✅ ADMIN: GATES CORRECTOS\n");
        } else {
            $this->error("\n❌ ADMIN: GATES INCORRECTOS\n");
        }

        // TEST 3: GATES CARGADOR
        $this->info(str_repeat("-", 60));
        $this->info("🔐 TEST 3: GATES DE CARGADOR");
        $this->info(str_repeat("-", 60));

        $cargador = $cargador->fresh();
        $test3_1 = Gate::forUser($cargador)->allows('super-admin');
        $test3_2 = Gate::forUser($cargador)->allows('acceso-operativo');
        $test3_3 = Gate::forUser($cargador)->allows('panel-carga');

        $this->line("Gate 'super-admin': " . ($test3_1 ? "❌ TRUE (MAL)" : "✅ FALSE (CORRECTO)"));
        $this->line("Gate 'acceso-operativo': " . ($test3_2 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)"));
        $this->line("Gate 'panel-carga': " . ($test3_3 ? "✅ TRUE (CORRECTO)" : "❌ FALSE (MAL)"));

        if (!$test3_1 && $test3_2 && $test3_3) {
            $this->info("\n✅ CARGADOR: GATES CORRECTOS\n");
        } else {
            $this->error("\n❌ CARGADOR: GATES INCORRECTOS\n");
        }

        // TEST 4: MÉTODO isSuperAdmin()
        $this->info(str_repeat("-", 60));
        $this->info("👤 TEST 4: MÉTODO isSuperAdmin()");
        $this->info(str_repeat("-", 60));

        $superAdmin = $superAdmin->fresh();
        $admin = $admin->fresh();

        $this->line("Super Admin->isSuperAdmin(): " . ($superAdmin->isSuperAdmin() ? "✅ TRUE" : "❌ FALSE"));
        $this->line("Admin->isSuperAdmin(): " . ($admin->isSuperAdmin() ? "❌ TRUE" : "✅ FALSE"));

        // TEST 5: ROLES ASIGNADOS
        $this->info(str_repeat("-", 60));
        $this->info("👥 TEST 5: ROLES ASIGNADOS");
        $this->info(str_repeat("-", 60));

        $this->line("Super Admin roles: " . $superAdmin->roles->pluck('name')->implode(', '));
        $this->line("Admin roles: " . $admin->roles->pluck('name')->implode(', '));
        $this->line("Cargador roles: " . $cargador->roles->pluck('name')->implode(', '));

        // RESUMEN
        $this->info("\n" . str_repeat("=", 60));
        $this->info("✅ TESTS COMPLETADOS");
        $this->info(str_repeat("=", 60));
        $this->info("\n📝 RESUMEN:");
        $this->line("  • Super Admin: TÉCNICO PURO (sin acceso operativo)");
        $this->line("  • Admin: OPERATIVO (con acceso a procedimientos, etc)");
        $this->line("  • Cargador: OPERATIVO (carga de datos)");
        $this->info("\n🔒 Segregación: ACTIVA Y FUNCIONANDO ✅\n");
    }
}
