<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ufi extends Model
{
    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function procedimientos()
    {
        return $this->hasMany(Procedimiento::class);
    }
}
