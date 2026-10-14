<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            ALTER TABLE respuesta
            ADD CONSTRAINT respuesta_un_solo_valor CHECK (
                (opcion_respuesta_id IS NOT NULL) +
                (contenido_respuesta IS NOT NULL) +
                (url_audio IS NOT NULL) <= 1
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE respuesta DROP CHECK respuesta_un_solo_valor');
    }
};
