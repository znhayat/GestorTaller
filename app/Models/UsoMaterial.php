<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoMaterial extends Model
{
    // Le indicamos a Laravel el nombre exacto de la tabla, ya que al ser 
    // un nombre compuesto se lia
    protected $table = 'usos_materiales';

    // Estos son los campos que permitimos rellenar de golpe. 
    // Es una barrera de seguridad para que no nos metan datos donde no toca.
    protected $fillable = ['encargo_id', 'material_id', 'cantidad', 'costo_total'];

    /**
     * Relación: Un uso de material pertenece a un encargo concreto.
     * Esto me permite saber, por ejemplo, en qué coche se usaron estos materiales.
     */
    public function encargo()
    {
        return $this->belongsTo(Encargo::class);
    }

    /**
     * Relación: Cada registro de esta tabla apunta a un material del almacén.
     * Con esto puedo sacar el nombre, la marca o el precio unitario del producto.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}