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
        Schema::table('encargos', function (Blueprint $table) {
            $table->date('fecha_inicio_trabajo')->nullable()->after('estado');
            $table->time('hora_inicio_trabajo')->nullable()->after('fecha_inicio_trabajo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio_trabajo', 'hora_inicio_trabajo']);
        });
    }
};
