<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domicilio;

class DomicilioSeeder extends Seeder
{
    public function run(): void
    {
        $domicilios = [
            // 1. Casa normal
            [
                'departamento' => 'Capital',
                'calle' => 'San Martín',
                'numero' => '1234',
                'barrio' => 'Centro',
            ],
            
            // 2. Departamento
            [
                'departamento' => 'Rawson',
                'calle' => 'Av. Libertador',
                'numero' => '567',
                'piso' => '3',
                'depto' => 'B',
                'barrio' => 'Centro',
            ],
            
            // 3. Monoblock
            [
                'departamento' => 'Chimbas',
                'monoblock' => '14 Eva Perón',
                'torre' => 'C',
                'piso' => '5',
                'depto' => '8',
                'barrio' => 'Huarpes',
            ],
            
            // 4. Lote en barrio
            [
                'departamento' => 'Pocito',
                'manzana' => '12',
                'lote' => '7',
                'casa' => '15',
                'barrio' => 'Villa Hipódromo',
            ],
            
            // 5. Zona rural
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
}