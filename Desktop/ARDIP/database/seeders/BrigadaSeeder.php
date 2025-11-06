<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brigada;

class BrigadaSeeder extends Seeder
{
    public function run(): void
    {
        $brigadas = [
            'Brigada Central',
            'Brigada Oeste',
            'Brigada Este',
            'Brigada Sur',
            'Brigada Norte',
            'Apoyo Investigativo',
            'Sustracción de Automotores',
            'Drogas Ilegales'
        ];

         foreach ($brigadas as $nombre) {
        if (!Brigada::where('nombre', $nombre)->exists()) {
            Brigada::create(['nombre' => $nombre]);
        }
    }
}
}   