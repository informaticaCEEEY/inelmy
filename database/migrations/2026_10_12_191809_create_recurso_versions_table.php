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
        Schema::create('recurso_version', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recurso_id')
                ->constrained('recurso')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('tipo_recurso_id')
                ->constrained('tipo_recurso')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('estatus_version_id')
                ->constrained('estatus_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->string('titulo', 200);
            $table->unsignedInteger('version');
            $table->string('url_contenido', 200);
            $table->text('transcripcion');
            $table->unique(['recurso_id', 'version']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurso_version');
    }
};
