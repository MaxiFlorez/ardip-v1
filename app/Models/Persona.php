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
    
    // Normaliza strings vacíos a null para evitar 'Data truncated' o valores inválidos de ENUM
    protected function nullIfEmpty($value)
    {
        return (isset($value) && $value !== '') ? $value : null;
    }

    public function setAliasAttribute($value)
    {
        $this->attributes['alias'] = $this->nullIfEmpty($value);
    }

    public function setNacionalidadAttribute($value)
    {
        // Si viene vacío, a null; el controlador puede definir un default ('Argentina') cuando corresponda
        $this->attributes['nacionalidad'] = $this->nullIfEmpty($value);
    }

    public function setEstadoCivilAttribute($value)
    {
        $this->attributes['estado_civil'] = $this->nullIfEmpty($value);
    }

    public function setGeneroAttribute($value)
    {
        $this->attributes['genero'] = $this->nullIfEmpty($value);
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = $this->nullIfEmpty($value);
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

    public function domicilio()
    {
        return $this->belongsTo(Domicilio::class);
    }
}