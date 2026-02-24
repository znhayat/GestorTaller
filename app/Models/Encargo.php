<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Encargo extends Model
{
    use HasFactory;
    protected $fillable = ['vehiculo_id', 'descripcion', 'estado', 'fecha_entrada', 'fecha_salida'];

    // El Encargo pertenece a un Vehículo específico
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación 1 a 1: Cada encargo tiene un presupuesto único
    public function presupuesto()
    {
        return $this->hasOne(Presupuesto::class);
    }

    // Relación 1 a 1: Cada encargo genera una factura final
    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    // Relaciones 1 a N: Un trabajo puede tener varias fotos, citas y uso de piezas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }
    public function usos_materiales()
    {
        return $this->hasMany(UsoMaterial::class);
    }
}
