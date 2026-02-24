<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $fillable = ['encargo_id', 'ruta', 'descripcion'];

    // La foto pertenece a un encargo
    public function encargo() {
        return $this->belongsTo(Encargo::class);
    }
}
