<?php
// migration add_cita_revision_to_encargos_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->date('cita_revision')->nullable();
            $table->date('cita_recogida')->nullable();
            $table->text('notas_internas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->dropColumn(['cita_revision', 'cita_recogida', 'notas_internas']);
        });
    }
};
