<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::table('recurso', function (Blueprint $table) {
            $table->foreignId('tipo_recurso_id')
                ->after('id')
                ->constrained('tipo_recurso')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('recurso_version', function (Blueprint $table) {
            $table->dropForeign(['tipo_recurso_id']);
            $table->dropColumn('tipo_recurso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recurso', function (Blueprint $table) {
            $table->dropForeign(['tipo_recurso_id']);
            $table->dropColumn('tipo_recurso_id');
        });
    }
};
