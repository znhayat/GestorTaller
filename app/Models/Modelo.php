<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $fillable = ['marca_id', 'nombre'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
