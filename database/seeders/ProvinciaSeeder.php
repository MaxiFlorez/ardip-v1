<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provincia;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            'San Juan', 'Buenos Aires', 'Catamarca', 'Córdoba', 'Corrientes', 
            'Chaco', 'Chubut', 'Entre Ríos', 'Formosa', 'Jujuy', 
            'La Pampa', 'La Rioja', 'Mendoza', 'Misiones', 'Neuquén', 
            'Río Negro', 'Salta', 'San Luis', 'Santa Cruz', 'Santa Fe', 
            'Santiago del Estero', 'Tierra del Fuego', 'Tucumán', 'CABA'
        ];

        foreach ($provincias as $nombre) {
            Provincia::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
