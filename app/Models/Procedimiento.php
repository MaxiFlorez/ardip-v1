<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Procedimiento extends Model implements Auditable
{
    use HasFactory; // enables factory() helper in tests
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'legajo_fiscal', 'caratula', 'es_positivo', 'ufi_id', 'usuario_id',
        'fecha_procedimiento', 'hora_procedimiento',
        'orden_allanamiento', 'orden_secuestro', 'orden_detencion',
        'brigada_id', 'resultado_secuestro', 'resultado_detencion', 'secuestro_detalle', 'observaciones',
    ];

    protected $auditInclude = ['legajo_fiscal', 'caratula', 'ufi_id'];
    
    protected $casts = [
        'fecha_procedimiento' => 'date',
        'hora_procedimiento' => 'datetime:H:i',
        'orden_allanamiento' => 'boolean',
        'orden_secuestro' => 'boolean',
        'orden_detencion' => 'boolean',
    ];

    // --- SCOPES PARA DASHBOARD (CORREGIDOS) ---

    public function scopeRangoFecha($query, $periodo)
    {
        if (!$periodo || $periodo === 'historico') return $query;

        $now = Carbon::now();

        return match ($periodo) {
            'semana' => $query->whereBetween('fecha_procedimiento', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'mes'    => $query->whereMonth('fecha_procedimiento', $now->month)->whereYear('fecha_procedimiento', $now->year),
            'anio'   => $query->whereYear('fecha_procedimiento', $now->year),
            default  => $query,
        };
    }

    public function scopeDeBrigada($query, $brigadaId)
    {
        return $brigadaId ? $query->where('brigada_id', $brigadaId) : $query;
    }

    public function scopePositivos($query)
    {
        return $query->where('es_positivo', true);
    }

    public function scopeBuscar($query, $texto)
    {
        if (trim($texto ?? '') === '') return $query;
        return $query->where(function ($q) use ($texto) {
            $q->where('legajo_fiscal', 'like', "%{$texto}%")
              ->orWhere('caratula', 'like', "%{$texto}%");
        });
    }

    public function scopeFiltrar($query, array $filters)
    {
        return $query
            ->when($filters['legajo_fiscal'] ?? null, function ($q, $legajo) {
                $q->where('legajo_fiscal', 'like', '%' . $legajo . '%');
            })
            ->when($filters['caratula'] ?? null, function ($q, $caratula) {
                $q->where('caratula', 'like', '%' . $caratula . '%');
            })
            ->when(isset($filters['es_positivo']) && $filters['es_positivo'] !== '', function ($q) use ($filters) {
                $q->where('es_positivo', filter_var($filters['es_positivo'], FILTER_VALIDATE_BOOLEAN));
            })
            ->when($filters['ufi_id'] ?? null, function ($q, $ufi) {
                $q->where('ufi_id', $ufi);
            })
            ->when($filters['brigada_id'] ?? null, function ($q, $brigada) {
                $q->where('brigada_id', $brigada);
            })
            ->when(($filters['fecha_desde'] ?? null) && ($filters['fecha_hasta'] ?? null), function ($q) use ($filters) {
                $q->whereBetween('fecha_procedimiento', [$filters['fecha_desde'], $filters['fecha_hasta']]);
            }, function ($q) use ($filters) {
                $q->when($filters['fecha_desde'] ?? null, function ($query, $fecha_desde) {
                    $query->where('fecha_procedimiento', '>=', $fecha_desde);
                })->when($filters['fecha_hasta'] ?? null, function ($query, $fecha_hasta) {
                    $query->where('fecha_procedimiento', '<=', $fecha_hasta);
                });
            });
    }

    // --- RELACIONES ---
    public function brigada()
    {
        return $this->belongsTo(Brigada::class);
    }

    public function ufi()
    {
        return $this->belongsTo(Ufi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'procedimiento_personas')
                    ->withPivot('situacion_procesal', 'pedido_captura', 'observaciones')
                    ->withTimestamps();
    }
    
    public function domicilios()
    {
        return $this->belongsToMany(Domicilio::class, 'procedimiento_domicilios')->withTimestamps();
    }
}
