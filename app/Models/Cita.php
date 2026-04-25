<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
   use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['encargo_id', 'fecha', 'hora', 'tipo', 'notas'];

    // Accessor MÀGIC per generar un codi auto sense afectar la base de dades
    public function getCodigoAttribute()
    {
        return 'CITA-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    // La cita siempre es para un encargo/reparación concreta
    public function encargo() {
        return $this->belongsTo(Encargo::class)->withTrashed();
    }
}
