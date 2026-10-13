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
        Schema::create('respuesta_rubrica_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respuesta_id')
                ->constrained('respuesta')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('criterio_nivel_id')
                ->constrained('criterio_nivel')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('rubrica_criterio_id')
                ->constrained('rubrica_criterio')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta_rubrica_detalle');
    }
};
