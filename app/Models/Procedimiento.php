<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Procedimiento extends Model implements Auditable
{
    // Trait de auditoría
    use \OwenIt\Auditing\Auditable;

    /**
     * Campos protegidos para asignación masiva
     */
    protected $guarded = ['id'];

    /**
     * Campos que serán incluidos en el audit log
     * @var array
     */
    protected $auditInclude = [
        'legajo_fiscal',
        'caratula',
        'ufi_interviniente',
    ];
    
    protected $casts = [
        'fecha_procedimiento' => 'date',
        'hora_procedimiento' => 'datetime:H:i',
        'orden_allanamiento' => 'boolean',
        'orden_secuestro' => 'boolean',
        'orden_detencion' => 'boolean',
    ];

    /**
     * Scope para búsqueda por legajo_fiscal o caratula
     */
    public function scopeBuscar($query, $texto)
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('legajo_fiscal', 'like', "%{$texto}%")
              ->orWhere('caratula', 'like', "%{$texto}%");
        });
    }
    
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