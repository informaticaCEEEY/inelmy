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
        Schema::create('seccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrumento_version_id')
                ->constrained('instrumento_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('tipo_seccion_id')
                ->constrained('tipo_seccion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedBigInteger('orden');
            $table->unsignedBigInteger('tiempo_asignado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion');
    }
};
