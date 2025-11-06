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
}