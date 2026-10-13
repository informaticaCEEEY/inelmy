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
        Schema::create('instrumento_version', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estatus_version_id')
                ->constrained('estatus_version')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->foreignId('instrumento_id')
                ->constrained('instrumento')
                ->onUpdate('cascade')
                ->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->text('notas')->nullable();
            $table->unique(['instrumento_id', 'version']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumento_version');
    }
};
