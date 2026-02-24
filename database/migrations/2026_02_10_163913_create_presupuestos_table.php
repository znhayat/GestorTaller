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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encargo_id')->constrained('encargos')->onDelete('cascade');
            $table->decimal('precio_materiales', 10, 2)->default(0);
            $table->decimal('precio_horas', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('aceptado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
