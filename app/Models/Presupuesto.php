<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = ['encargo_id', 'precio_materiales', 'precio_horas', 'total', 'aceptado'];

// Cada presupuesto está ligado a un único encargo
    public function encargo() {
        return $this->belongsTo(Encargo::class);
    }
}
