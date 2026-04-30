<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $fillable = [
        'encargo_id', 
        'ruta', 
        'tipo',
        'relacion_id',
        'descripcion',
        'es_publica',
        'titulo_galeria',
        'categoria_badge',
        'categoria_texto'
    ];

    // Relación para fotos de Antes y Después (Desde el Antes hacia el Después)
    public function despues() {
        return $this->hasOne(Foto::class, 'relacion_id');
    }

    // Relación inversa (Desde el Después hacia el Antes)
    public function antes() {
        return $this->belongsTo(Foto::class, 'relacion_id');
    }

    // La foto puede pertenecer a un encargo
    public function encargo() {
        return $this->belongsTo(Encargo::class)->withTrashed();
    }
}
