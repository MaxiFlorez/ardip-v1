<?php

namespace App\Models;

use App\Models\Procedimiento;
use App\Models\Provincia; // <--- IMPORTAR EL NUEVO MODELO PROVINCIA
use App\Models\Departamento; // <--- IMPORTAR EL NUEVO MODELO DEPARTAMENTO
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
    protected $guarded = ['id']; //

    // ==============================================
    // ====== INICIO: RELACIÓN AÑADIDA =========
    // ==============================================

    /**
     * Relación: Un domicilio PERTENECE A una provincia.
     */
    public function provincia()
    {
        // Laravel conectará automáticamente 'provincia_id' a la tabla 'provincias'.
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Relación: Un domicilio PERTENECE A un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    

    // ==============================================
    // ====== FIN: RELACIÓN AÑADIDA =============
    // ===============================================

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

    // ==============================================
    // ====== ACCESSOR (DIRECCIÓN COMPLETA) =========
    // ==============================================
    public function getDireccionCompletaAttribute(): string
    {
        $parts = [];
        if (!empty($this->calle)) {
            $parts[] = trim($this->calle . (empty($this->numero) ? '' : ' ' . $this->numero));
        }
        if (!empty($this->monoblock)) {
            $parts[] = 'MBLK: ' . $this->monoblock;
        }
        if (!empty($this->barrio)) {
            $parts[] = 'B° ' . $this->barrio; // usamos campo texto 'barrio'
        }
        // Sector (opcional)
        if (!empty($this->sector)) {
            $parts[] = 'SECTOR: ' . $this->sector;
        }
        // Detalle de manzana/lote/casa (opcional y condicionado por existencia)
        $detalle = [];
        if (!empty($this->manzana)) {
            $detalle[] = 'MZNA ' . $this->manzana;
        }
        if (!empty($this->lote)) {
            $detalle[] = 'LOTE ' . $this->lote;
        }
        if (!empty($this->casa)) {
            $detalle[] = 'CASA ' . $this->casa;
        }
        if (!empty($detalle)) {
            $parts[] = implode(' ', $detalle);
        }
        // TORRE (si existe)
        if (!empty($this->torre)) {
            $parts[] = 'TORRE ' . $this->torre;
        }
        // PISO/DPTO (combinado si existen)
        $pisoDepto = [];
        if (!empty($this->piso)) {
            $pisoDepto[] = 'PISO ' . $this->piso;
        }
        if (!empty($this->depto)) {
            $pisoDepto[] = 'DPTO ' . $this->depto;
        }
        if (!empty($pisoDepto)) {
            $parts[] = implode(' ', $pisoDepto);
        }
        return implode(' - ', $parts);
    }

    // ==============================================
    // ====== MUTADORES (NORMALIZACIÓN A MAYÚSCULAS) ======
    // ==============================================

    /**
     * Normaliza el campo 'calle' a mayúsculas antes de guardar.
     */
    public function setCalleAttribute($value)
    {
        $this->attributes['calle'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'numero' eliminando espacios extras.
     */
    public function setNumeroAttribute($value)
    {
        $this->attributes['numero'] = $value ? trim($value) : null;
    }

    /**
     * Normaliza el campo 'piso' a mayúsculas.
     */
    public function setPisoAttribute($value)
    {
        $this->attributes['piso'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'depto' a mayúsculas.
     */
    public function setDeptoAttribute($value)
    {
        $this->attributes['depto'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'torre' a mayúsculas.
     */
    public function setTorreAttribute($value)
    {
        $this->attributes['torre'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'monoblock' a mayúsculas.
     */
    public function setMonoblockAttribute($value)
    {
        $this->attributes['monoblock'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'manzana' a mayúsculas.
     */
    public function setManzanaAttribute($value)
    {
        $this->attributes['manzana'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'lote' a mayúsculas.
     */
    public function setLoteAttribute($value)
    {
        $this->attributes['lote'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'casa' a mayúsculas.
     */
    public function setCasaAttribute($value)
    {
        $this->attributes['casa'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'barrio' (texto libre del autocompletado JSON).
     */
    public function setBarrioAttribute($value)
    {
        $this->attributes['barrio'] = $value ? trim($value) : null;
    }

    /**
     * Normaliza el campo 'sector' a mayúsculas.
     */
    public function setSectorAttribute($value)
    {
        $this->attributes['sector'] = $value ? strtoupper(trim($value)) : null;
    }

    /**
     * Normaliza el campo 'coordenadas_gps' eliminando espacios extras.
     */
    public function setCoordenadasGpsAttribute($value)
    {
        $this->attributes['coordenadas_gps'] = $value ? trim($value) : null;
    }
}