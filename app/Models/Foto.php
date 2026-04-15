<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $fillable = ['encargo_id', 'ruta', 'descripcion'];

    // La foto pertenece a un encargo
    public function encargo() {
        return $this->belongsTo(Encargo::class)->withTrashed();
    }
}
