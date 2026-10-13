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
        Schema::create('respuesta_espacio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('respuesta_id');
            $table->unsignedBigInteger('pregunta_version_id');
            $table->unsignedBigInteger('pregunta_version_espacio_id');
            $table->text('contenido_respuesta')->nullable();
            $table->boolean('es_correcta')->nullable();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['respuesta_id', 'pregunta_version_espacio_id'], 'respuesta_espacio_unica');

            $table->foreign(['respuesta_id', 'pregunta_version_id'])
                ->references(['id', 'pregunta_version_id'])
                ->on('respuesta')
                ->onUpdate('cascade')->restrictOnDelete();

                $table->foreign(['pregunta_version_espacio_id', 'pregunta_version_id'])
                ->references(['id', 'pregunta_version_id'])
                ->on('pregunta_version_espacio')
                ->onUpdate('cascade')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta_espacio');
    }
};
