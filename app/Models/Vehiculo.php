<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    protected $fillable = ['cliente_id', 'marca', 'modelo'];

    // El vehículo pertenece a un cliente
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    // Un vehículo puede tener muchos encargos (reparaciones)
    public function encargos() {
        return $this->hasMany(Encargo::class);
    }
}
