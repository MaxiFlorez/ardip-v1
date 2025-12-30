<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ufi extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public $timestamps = false; // Las UFIs son datos maestros, no requieren timestamps.

    public function procedimientos()
    {
        return $this->hasMany(Procedimiento::class);
    }
}
