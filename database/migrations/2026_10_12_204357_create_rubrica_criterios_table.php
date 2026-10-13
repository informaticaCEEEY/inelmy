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
        Schema::create('rubrica_criterio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubrica_id')
                ->nullable()
                ->constrained('rubrica')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->string('nombre', 250);
            $table->decimal('peso', 5, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrica_criterio');
    }
};
