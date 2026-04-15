<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tablas = [
        'clientes',
        'vehiculos',
        'encargos',
        'presupuestos',
        'facturas',
        'materiales',
        'usos_materiales',
        'citas',
        'fotos'
    ];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            if (Schema::hasTable($tabla)) {
                if (!Schema::hasColumn($tabla, 'deleted_at')) {
                    Schema::table($tabla, function (Blueprint $table) {
                        $table->softDeletes();
                    });
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            if (Schema::hasTable($tabla)) {
                Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                    if (Schema::hasColumn($tabla, 'deleted_at')) {
                        $table->dropSoftDeletes();
                    }
                });
            }
        }
    }
};
