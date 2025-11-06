<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'fecha_procedimiento' => 'date',
        'hora_procedimiento' => 'datetime:H:i',
        'orden_allanamiento' => 'boolean',
        'orden_secuestro' => 'boolean',
        'orden_detencion' => 'boolean',
    ];
    
    // Relaciones
    public function brigada()
    {
        return $this->belongsTo(Brigada::class);
    }
    
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'procedimiento_personas')
                    ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                    ->withTimestamps();
    }
    
    public function domicilios()
    {
        return $this->belongsToMany(Domicilio::class, 'procedimiento_domicilios')
                    ->withTimestamps();
    }
}