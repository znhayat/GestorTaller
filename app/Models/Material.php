<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';
    protected $fillable = ['nombre', 'tipo', 'unidad', 'precio_unitario'];

    // Un Material puede haber sido usado en muchos Encargos diferentes
    public function usos_materiales()
    {
        return $this->hasMany(UsoMaterial::class);
    }
}
