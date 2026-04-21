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
        Schema::table('fotos', function (Blueprint $table) {
            $table->foreignId('encargo_id')->nullable()->change();
            $table->boolean('es_publica')->default(false);
            $table->string('titulo_galeria')->nullable();
            $table->string('categoria_badge')->nullable(); // Ej: 'primary', 'info'
            $table->string('categoria_texto')->nullable(); // Ej: 'Volantes'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fotos', function (Blueprint $table) {
            // Note: Changing back to non-nullable is omitted as it would fail if nulls exist
            $table->dropColumn(['es_publica', 'titulo_galeria', 'categoria_badge', 'categoria_texto']);
        });
    }
};
