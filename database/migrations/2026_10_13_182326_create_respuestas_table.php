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
        Schema::create('respuesta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aplicacion_sustentante_id')
                ->constrained('aplicacion_sustentante')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('pregunta_version_id')
                ->constrained('pregunta_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedBigInteger('opcion_respuesta_id')->nullable();
            $table->foreignId('evaluador_id')
                ->nullable()
                ->constrained('usuario')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('estado_calificacion_id')
                ->constrained('estado_calificacion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->string('url_audio', 200)->nullable();
            $table->boolean('es_correcta')->nullable();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->dateTime('fecha_calificacion')->nullable();
            $table->timestamps();

            $table->unique(['aplicacion_sustentante_id', 'pregunta_version_id'], 'respuesta_unica');
            // requerido para la FK compuesta de respuesta_espacio
            $table->unique(['id', 'pregunta_version_id'], 'respuesta_id_pregunta_unique');

            $table->foreign(['opcion_respuesta_id', 'pregunta_version_id'])
                ->references(['id', 'pregunta_version_id'])
                ->on('opcion_respuesta')
                ->onUpdate('cascade')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta');
    }
};
