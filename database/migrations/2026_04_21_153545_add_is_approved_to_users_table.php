<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('email');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('is_approved');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'role']);
        });
    }
};
