<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Procedimiento extends Model implements Auditable
{
    // Trait de auditoría
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'legajo_fiscal',
        'caratula',
        'es_positivo',
        'ufi_id', // Reemplaza a ufi_interviniente
        'fecha_procedimiento',
        'hora_procedimiento',
        'orden_allanamiento',
        'orden_secuestro',
        'orden_detencion',
        'brigada_id',
    ];

    /**
     * Campos que serán incluidos en el audit log
     * @var array
     */
    protected $auditInclude = [
        'legajo_fiscal',
        'caratula',
        'ufi_id', // Reemplaza a ufi_interviniente
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

    public function ufi()
    {
        return $this->belongsTo(Ufi::class);
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
    
    
    
        /**
         * Scope para filtrar por rango de fechas.
         */
        public function scopeRangoFecha($query, $periodo)
        {
            if (!$periodo) {
                return $query;
            }

            $hoy = Carbon::now();

            switch ($periodo) {
                case 'semana':
                    return $query->whereBetween('fecha_procedimiento', [$hoy->startOfWeek(), $hoy]);
                case 'mes':
                    return $query->whereBetween('fecha_procedimiento', [$hoy->startOfMonth(), $hoy]);
                case 'anio':
                    return $query->whereBetween('fecha_procedimiento', [$hoy->startOfYear(), $hoy]);
                default:
                    return $query;
            }
        }
    
    
    
        /**
    
         * Scope para filtrar por brigada.
    
         */
    
        public function scopeDeBrigada($query, $brigadaId)
    
        {
    
            if ($brigadaId) {
    
                return $query->where('brigada_id', $brigadaId);
    
            }
    
            return $query;
    
        }
    
    
    
        /**
    
         * Scope para filtrar procedimientos positivos.
    
         */
    
        public function scopePositivos($query)
    
        {
    
            return $query->where('es_positivo', true);
    
        }


    public function scopeFiltrar($query, array $filters)
    {
        $query->when($filters['legajo_fiscal'] ?? null, function ($query, $legajo) {
            $query->where('legajo_fiscal', 'like', '%' . $legajo . '%');
        })->when($filters['caratula'] ?? null, function ($query, $caratula) {
            $query->where('caratula', 'like', '%' . $caratula . '%');
        })->when(isset($filters['es_positivo']) && $filters['es_positivo'] !== '', function ($query) use ($filters) {
            // Acepta "1", "true", "on", "yes" como true y "0", "false", "off", "no", "" como false
            $query->where('es_positivo', filter_var($filters['es_positivo'], FILTER_VALIDATE_BOOLEAN));
        })->when($filters['ufi_id'] ?? null, function ($query, $ufi) {
            $query->where('ufi_id', $ufi);
        })->when($filters['brigada_id'] ?? null, function ($query, $brigada) {
            $query->where('brigada_id', $brigada);
        })->when(($filters['fecha_desde'] ?? null) && ($filters['fecha_hasta'] ?? null), function ($query) use ($filters) {
            // Usar whereBetween cuando ambas fechas están presentes
            $query->whereBetween('fecha_procedimiento', [$filters['fecha_desde'], $filters['fecha_hasta']]);
        }, function ($query) use ($filters) {
            // Fallback a where individuales si solo una de las fechas está presente
            $query->when($filters['fecha_desde'] ?? null, function ($query, $fecha_desde) {
                $query->where('fecha_procedimiento', '>=', $fecha_desde);
            })->when($filters['fecha_hasta'] ?? null, function ($query, $fecha_hasta) {
                $query->where('fecha_procedimiento', '<=', $fecha_hasta);
            });
        });
    }
    
    }