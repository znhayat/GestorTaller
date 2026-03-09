<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'correo',
    ];
    // Un cliente tiene muchos vehículos 
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
