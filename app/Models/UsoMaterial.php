<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoMaterial extends Model
{
    protected $table = 'usos_materiales';
    protected $fillable = ['encargo_id', 'material_id', 'cantidad', 'costo_total'];

    public function encargo()
    {
        return $this->belongsTo(Encargo::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
