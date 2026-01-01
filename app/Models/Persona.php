<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Persona extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function getNombreCompletoAttribute()
    {
        return trim(($this->apellidos ?? '') . ' ' . ($this->nombres ?? ''));
    }
    
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
    
    // Relación con procedimientos
    public function procedimientos()
    {
        return $this->belongsToMany(Procedimiento::class, 'procedimiento_personas')
                    ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                    ->withTimestamps();
    }

    // Relación con alias
    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }

    // Relación con domicilios (histórico y habitual)
    public function domicilios()
    {
        return $this->belongsToMany(Domicilio::class, 'persona_domicilio')
                    ->withPivot('es_habitual', 'observaciones', 'desde', 'hasta')
                    ->withTimestamps();
    }

    // Helper: obtener domicilio habitual actual
    public function domicilioHabitual()
    {
        return $this->domicilios()->wherePivot('es_habitual', true)->first();
    }
}