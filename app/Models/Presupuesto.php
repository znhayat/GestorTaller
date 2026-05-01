<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'encargo_id', 
        'estimacion_inicial', 
        'precio_materiales', 
        'precio_horas', 
        'total', 
        'aceptado', 
        'notas'
    ];

// Cada presupuesto está ligado a un único encargo
    public function encargo() {
        return $this->belongsTo(Encargo::class)->withTrashed();
    }
}
