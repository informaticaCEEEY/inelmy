<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaEspacio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respuesta_espacio';
    protected $fillable = ['respuesta_id', 'pregunta_version_id', 'pregunta_version_espacio_id',
        'contenido_respuesta', 'es_correcta', 'calificacion'];

    public function respuesta(): BelongsTo
    {
        return $this->belongsTo(Respuesta::class);
    }

    public function preguntaVersion(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersion::class);
    }

    public function preguntaVersionEspacio(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersionEspacio::class);
    }
}
