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
        Schema::table('materiales', function (Blueprint $table) {
            $table->decimal('stock', 10, 2)->default(0)->after('precio_unitario');
            $table->decimal('stock_minimo', 10, 2)->default(0)->after('stock');
            $table->text('descripcion')->nullable()->after('stock_minimo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->dropColumn(['stock', 'stock_minimo', 'descripcion']);
        });
    }
};
