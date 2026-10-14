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
        Schema::table('seccion', function (Blueprint $table) {
            $table->unique(
                ['instrumento_version_id', 'orden'],
                'seccion_version_orden_unique'
            );
        });

        Schema::table('seccion_pregunta', function (Blueprint $table) {
            $table->unique(
                ['seccion_id', 'orden'],
                'seccion_pregunta_orden_unique'
            );
        });

        Schema::table('pregunta_version_espacio', function (Blueprint $table) {
            $table->unique(
                ['pregunta_version_id', 'numero_espacio'],
                'pregunta_espacio_numero_unique'
            );
        });

        Schema::table('opcion_respuesta', function (Blueprint $table) {
            $table->unique(
                ['pregunta_version_id', 'orden'],
                'opcion_pregunta_orden_unique'
            );
        });

        Schema::table('aplicacion_sustentante_seccion', function (Blueprint $table) {
            $table->unique(
                ['aplicacion_sustentante_id', 'seccion_id'],
                'aplicacion_sustentante_seccion_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aplicacion_sustentante_seccion', function (Blueprint $table) {
            $table->dropUnique('aplicacion_sustentante_seccion_unique');
        });

        Schema::table('opcion_respuesta', function (Blueprint $table) {
            $table->dropUnique('opcion_pregunta_orden_unique');
        });

        Schema::table('pregunta_version_espacio', function (Blueprint $table) {
            $table->dropUnique('pregunta_espacio_numero_unique');
        });

        Schema::table('seccion_pregunta', function (Blueprint $table) {
            $table->dropUnique('seccion_pregunta_orden_unique');
        });

        Schema::table('seccion', function (Blueprint $table) {
            $table->dropUnique('seccion_version_orden_unique');
        });
    }
};
