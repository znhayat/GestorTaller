<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['encargo_id', 'importe_total', 'pagado', 'fecha_pago'];

    // La factura nace de un encargo finalizado
    public function encargo() {
        return $this->belongsTo(Encargo::class)->withTrashed();
    }
}
