<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $table = 'alias';
    protected $fillable = [
        'persona_id',
        'alias',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
