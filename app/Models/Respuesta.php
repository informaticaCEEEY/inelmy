<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respuesta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respuesta';
    protected $fillable = ['aplicacion_sustentante_id', 'pregunta_version_id', 'opcion_respuesta_id',
        'evaluador_id', 'estado_calificacion_id', 'url_audio', 'es_correcta', 'calificacion', 'fecha_calificacion'];

    public function aplicacionSustentante(): BelongsTo
    {
        return $this->belongsTo(AplicacionSustentante::class);
    }

    public function preguntaVersion(): BelongsTo
    {
        return $this->belongsTo(PreguntaVersion::class);
    }

    public function opcionRespuesta(): BelongsTo
    {
        return $this->belongsTo(OpcionRespuesta::class);
    }

    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'evaluador_id');
    }

    public function estadoCalificacion(): BelongsTo
    {
        return $this->belongsTo(EstadoCalificacion::class);
    }
}
