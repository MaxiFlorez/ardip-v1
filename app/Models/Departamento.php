<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relación inversa: Un departamento tiene muchos domicilios
    public function domicilios()
    {
        return $this->hasMany(Domicilio::class);
    }
}
