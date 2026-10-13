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
        Schema::create('seccion_pregunta', function (Blueprint $table) {
            $table->foreignId('seccion_id')
                ->constrained('seccion')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('pregunta_version_id')
                ->constrained('pregunta_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedBigInteger('orden');
            $table->decimal('puntaje', 5, 2)->unsigned();
            $table->primary(['seccion_id', 'pregunta_version_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion_pregunta');
    }
};
