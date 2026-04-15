<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['clientes', 'vehiculos', 'encargos', 'materiales', 'facturas', 'presupuestos', 'citas', 'fotos', 'usos_materiales'];
        foreach ($tables as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['clientes', 'vehiculos', 'encargos', 'materiales', 'facturas', 'presupuestos', 'citas', 'fotos', 'usos_materiales'];
        foreach ($tables as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
