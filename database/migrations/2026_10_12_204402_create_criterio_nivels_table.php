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
        Schema::create('criterio_nivel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubrica_criterio_id')
                ->nullable()
                ->constrained('rubrica_criterio')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('puntaje', 5, 2)->unsigned();
            $table->unique(['rubrica_criterio_id', 'nombre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterio_nivel');
    }
};
