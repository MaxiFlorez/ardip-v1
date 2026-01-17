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

    /**
     * Accessor para obtener la dirección formateada de manera inteligente
     * Maneja elegantemente la falta de datos de texto
     */
    public function getDireccionFormateadaAttribute(): string
    {
        // Si tiene calle y altura
        if ($this->calle && $this->altura) {
            $partes = [$this->calle, $this->altura];
            
            // Agregar piso/depto si existen
            if ($this->piso || $this->depto) {
                $apartamento = [];
                if ($this->piso) $apartamento[] = "Piso {$this->piso}";
                if ($this->depto) $apartamento[] = "Depto {$this->depto}";
                $partes[] = implode(', ', $apartamento);
            }
            
            // Agregar localidad o barrio
            if ($this->localidad) {
                $partes[] = $this->localidad;
            } elseif ($this->barrio) {
                $partes[] = $this->barrio;
            }
            
            return implode(', ', $partes);
        }

        // Si tiene barrio/localidad pero sin calle
        if ($this->barrio || $this->localidad) {
            $ubicacion = [];
            if ($this->barrio) $ubicacion[] = "Barrio: {$this->barrio}";
            if ($this->localidad) $ubicacion[] = $this->localidad;
            if ($this->provincia) $ubicacion[] = $this->provincia;
            return implode(', ', $ubicacion);
        }

        // Si tiene coordenadas pero no dirección de texto
        if ($this->latitud && $this->longitud) {
            return "📍 Ubicación Georreferenciada";
        }

        // Si no tiene nada
        return "❓ Sin datos de dirección";
    }
}