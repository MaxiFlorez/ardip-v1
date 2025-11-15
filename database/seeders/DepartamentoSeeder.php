<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            '25 de Mayo', '9 de Julio', 'Albardón', 'Angaco', 'Calingasta', 
            'Capital', 'Caucete', 'Chimbas', 'Iglesia', 'Jáchal', 
            'Pocito', 'Rawson', 'Rivadavia', 'San Martín', 'Santa Lucía', 
            'Sarmiento', 'Ullum', 'Valle Fértil', 'Zonda'
        ];

        foreach ($departamentos as $nombre) {
            Departamento::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
