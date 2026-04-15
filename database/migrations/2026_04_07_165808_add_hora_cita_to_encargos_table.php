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
            $table->time('hora_cita')->nullable()->after('cita_revision');
            $table->boolean('recordatorio_enviado')->default(false)->after('cita_recogida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->dropColumn(['hora_cita', 'recordatorio_enviado']);
        });
    }
};
