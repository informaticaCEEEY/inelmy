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
        Schema::create('opcion_respuesta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_version_id')
                ->constrained('pregunta_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->text('contenido');
            $table->unsignedBigInteger('orden');
            $table->boolean('es_correcta')->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcion_respuesta');
    }
};
