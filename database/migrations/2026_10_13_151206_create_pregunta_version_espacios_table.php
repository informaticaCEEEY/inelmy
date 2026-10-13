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
        Schema::create('pregunta_version_espacio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_version_id')
                ->constrained('pregunta_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedBigInteger('numero_espacio');
            $table->text('respuesta_esperada');
            $table->decimal('puntaje', 5, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta_version_espacio');
    }
};
