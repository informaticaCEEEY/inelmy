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
        Schema::create('aplicacion_sustentante_seccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aplicacion_sustentante_id')
                ->constrained('aplicacion_sustentante')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('seccion_id')
                ->constrained('seccion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('estatus_seccion_id')
                ->constrained('estatus_seccion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->timestamp('inicio');
            $table->timestamp('fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_sustentante_seccion');
    }
};
