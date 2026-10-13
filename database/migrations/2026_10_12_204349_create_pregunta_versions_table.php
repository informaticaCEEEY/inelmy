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
        Schema::create('pregunta_version', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estatus_version_id')
                ->constrained('estatus_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('pregunta_id')
                ->constrained('pregunta')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('tipo_pregunta_id')
                ->constrained('tipo_pregunta')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('recurso_version_id')
                ->nullable()
                ->constrained('recurso_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('rubrica_id')
                ->nullable()
                ->constrained('rubrica')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->text('enunciado');
            $table->timestamps();
            $table->unique(['pregunta_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta_version');
    }
};
