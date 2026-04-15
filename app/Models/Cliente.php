<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

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

    protected static function booted()
    {
        static::deleting(function ($cliente) {
            $cliente->vehiculos()->delete();
        });
    }
}
