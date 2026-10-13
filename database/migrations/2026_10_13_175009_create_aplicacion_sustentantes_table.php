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
        Schema::create('aplicacion_sustentante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aplicacion_id')
                ->constrained('aplicacion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('sustentante_id')
                ->constrained('usuario')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('estatus_aplicacion_id')
                ->constrained('estatus_aplicacion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unique(['aplicacion_id', 'sustentante_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_sustentante');
    }
};
