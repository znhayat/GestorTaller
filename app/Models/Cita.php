<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
   protected $fillable = ['encargo_id', 'fecha', 'hora'];

    // La cita siempre es para un encargo/reparación concreta
    public function encargo() {
        return $this->belongsTo(Encargo::class);
    }
}
