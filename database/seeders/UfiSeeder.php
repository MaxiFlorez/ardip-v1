<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ufi;
use Illuminate\Support\Facades\DB;

class UfiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ufis')->delete(); // Previene duplicados si se corre varias veces

        $ufis = [
            'UFI CAVIG', 
            'UFI ANIVI', 
            'UFI Delitos Especiales', 
            'UFI Delitos contra la Propiedad', 
            'UFI Delitos Informáticos y Estafas', 
            'UFI Genérica', 
            'UFI del Sistema de Flagrancia'
        ];

        foreach ($ufis as $ufi) {
            Ufi::create(['nombre' => $ufi]);
        }
    }
}
