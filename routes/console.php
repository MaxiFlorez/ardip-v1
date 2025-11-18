<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Procedimiento;
use App\Models\Persona;
use App\Models\Domicilio;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:reset-demo-data {--keep=} {--dry-run}', function () {
    $keepOpt = (string) ($this->option('keep') ?? '');
    $dryRun = (bool) $this->option('dry-run');

    // Resolver usuario a conservar por id o email; si no se encuentra, usar el primero
    $userToKeep = null;
    if ($keepOpt !== '') {
        if (filter_var($keepOpt, FILTER_VALIDATE_EMAIL)) {
            $userToKeep = User::where('email', $keepOpt)->first();
        } elseif (ctype_digit($keepOpt)) {
            $userToKeep = User::find((int)$keepOpt);
        }
    }
    if (!$userToKeep) {
        $userToKeep = User::orderBy('id')->first();
    }

    if (!$userToKeep) {
        $this->error('No se encontró ningún usuario para conservar. Abortando.');
        return 1;
    }

    $this->info('Usuario a conservar: #'.$userToKeep->id.' <'.$userToKeep->email.'>');

    if ($dryRun) {
        $this->warn('[DRY-RUN] No se realizarán cambios.');
    }

    try {
        // Deshabilitar FKs en MySQL/MariaDB
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $steps = [
            'Truncar pivote procedimiento_personas' => function(){ DB::table('procedimiento_personas')->truncate(); },
            'Truncar pivote procedimiento_domicilios' => function(){ DB::table('procedimiento_domicilios')->truncate(); },
            'Eliminar procedimientos' => function(){ Procedimiento::query()->delete(); },
            'Eliminar personas' => function(){ Persona::query()->delete(); },
            'Eliminar domicilios' => function(){ Domicilio::query()->delete(); },
            'Eliminar usuarios excepto el conservado' => function() use ($userToKeep){ User::where('id','!=',$userToKeep->id)->delete(); },
            'Borrar fotos de personas (storage/app/public/fotos_personas)' => function(){
                if (Storage::disk('public')->exists('fotos_personas')) {
                    Storage::disk('public')->deleteDirectory('fotos_personas');
                }
                Storage::disk('public')->makeDirectory('fotos_personas');
            },
        ];

        foreach ($steps as $label => $fn) {
            $this->line('- '.$label);
            if (!$dryRun) { $fn(); }
        }

        // Rehabilitar FKs
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        if ($dryRun) {
            $this->warn('DRY-RUN completado. No se aplicaron cambios.');
        } else {
            $this->info('Datos de prueba eliminados. Usuario conservado: #'.$userToKeep->id);
        }
    } catch (\Throwable $e) {
        // Asegurar reactivar FKs por si falla en medio
        try { DB::statement('SET FOREIGN_KEY_CHECKS=1'); } catch (\Throwable $e2) {}
        $this->error('Error al limpiar datos: '.$e->getMessage());
        return 1;
    }

    return 0;
})->purpose('Elimina datos de prueba y conserva un único usuario (por id/email o el primero)');
