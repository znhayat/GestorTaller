<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['cliente_id', 'marca', 'modelo'];

    // El vehículo pertenece a un cliente
    public function cliente() {
        return $this->belongsTo(Cliente::class)->withTrashed();
    }

    // Un vehículo puede tener muchos encargos (reparaciones)
    public function encargos() {
        return $this->hasMany(Encargo::class);
    }

    protected static function booted()
    {
        static::deleting(function ($vehiculo) {
            $vehiculo->encargos()->delete();
        });
    }
}
