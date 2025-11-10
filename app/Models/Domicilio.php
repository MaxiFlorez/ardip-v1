<?php

namespace App\Models;

use App\Models\Procedimiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domicilio extends Model
{
    use HasFactory;

    /**
     * Los atributos que no se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Define la relación "muchos a muchos" con Procedimiento.
     * Un domicilio puede estar en muchos procedimientos.
     */
    public function procedimientos()
    {
        // Usamos la tabla pivote que definimos en Sesión 3 y 4
        return $this->belongsToMany(Procedimiento::class, 'procedimiento_domicilios')
                    ->withTimestamps();
    }
}