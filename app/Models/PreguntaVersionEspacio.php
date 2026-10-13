<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreguntaVersionEspacio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pregunta_version_espacio';
    protected $fillable = ['pregunta_version_id', 'numero_espacio', 'respuesta_esperada', 'puntaje'];

    public function preguntaVersion(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersion::class);
    }
}
