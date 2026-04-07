<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Solo actualizamos los estados existentes si es necesario
        // Los nuevos estados posibles son: 'Cita Agendada', 'En Revision', 'Presupuesto Enviado', 'Aceptado', 'En Produccion', 'Entregado', 'Cancelado'
        Schema::table('encargos', function (Blueprint $table) {
            // No necesitamos modificar la estructura, solo documentamos
        });
    }

    public function down()
    {
        //
    }
};
